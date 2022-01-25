<?php
/**
 * Handles the Cart rules or the cart.
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_BOGOF_Cart_Rules Class
 */
class WC_BOGOF_Cart_Rules implements IteratorAggregate, Countable {

	/**
	 * BOGO rules
	 *
	 * @var array
	 */
	protected $rules;

	/**
	 * Cart rules
	 *
	 * @var array
	 */
	protected $cart_rules;

	/**
	 * Cart item cart rules references.
	 *
	 * @var array
	 */
	protected $cart_rules_ref;

	/**
	 * BOGO Rules version.
	 *
	 * @var string
	 */
	protected $rules_version;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->rules          = false;
		$this->cart_rules     = array();
		$this->cart_rules_ref = array();
		$this->rules_version  = WC_Cache_Helper::get_transient_version( 'bogof_rules' );
	}

	/**
	 * Returns all cart rules.
	 *
	 * @return array
	 */
	public function get_all() {
		return $this->cart_rules;
	}

	/**
	 * Returns a cart rule by ID.
	 *
	 * @param string $id Cart rule ID.
	 * @return WC_BOGOF_Cart_Rule|false
	 */
	public function get( $id ) {
		return $this->exists( $id ) ? $this->cart_rules[ $id ] : false;
	}

	/**
	 * Does the cart rule exists?
	 *
	 * @param string $id Cart rule ID.
	 */
	public function exists( $id ) {
		return isset( $this->cart_rules[ $id ] );
	}

	/**
	 * Returns the cart rules by cart item key.
	 *
	 * @param string $cart_item_key The cart item key.
	 * @return array
	 */
	public function get_by_cart_item_key( $cart_item_key ) {
		$cart_rules = array();
		if ( isset( $this->cart_rules_ref[ $cart_item_key ] ) ) {
			foreach ( $this->cart_rules_ref[ $cart_item_key ] as $id ) {
				if ( $this->exists( $id ) ) {
					$cart_rules[] = $this->get( $id );
				}
			}
		}
		return $cart_rules;
	}

	/**
	 * Returns the cart item keys related to the cart rule.
	 *
	 * @param string $id Cart rule ID.
	 * @return array
	 */
	public function get_cart_item_keys( $id ) {
		$keys = array();
		foreach ( $this->cart_rules_ref as $cart_item_key => $cart_rule_ids ) {
			if ( in_array( $id, $cart_rule_ids, true ) ) {
				$keys[] = $cart_item_key;
			}
		}
		return $keys;
	}

	/**
	 * Returns the cart rules IDs.
	 *
	 * @return array
	 */
	public function ids() {
		return array_keys( $this->cart_rules );
	}

	/**
	 * Sorts the cart rules.
	 */
	public function sort() {
		uasort( $this->cart_rules, array( $this, 'sort_callback' ) );

		$uniq = array();

		foreach ( $this->cart_rules as $id => $cart_rule ) {
			if ( $cart_rule->get_rule()->get_exclude_other_rules() && $cart_rule->match() ) {
				$uniq[ $id ] = $cart_rule;
				break;
			}
		}

		// Add the individual rules.
		if ( ! empty( $uniq ) ) {
			$rule_id = current( $uniq )->get_rule()->get_id();
			foreach ( $this->cart_rules as $id => $cart_rule ) {
				if ( $rule_id === $cart_rule->get_rule()->get_id() ) {
					$uniq[ $id ] = $cart_rule;
				}
			}

			$this->cart_rules = $uniq;
		}
	}

	/**
	 * Adds a cart rule.
	 *
	 * @param array $cart_item Cart item data.
	 */
	public function add( $cart_item ) {

		if ( false === $this->rules ) {
			$data_store  = WC_Data_Store::load( 'bogof-rule' );
			$this->rules = $data_store->get_rules();
		}

		$changed       = false;
		$product_id    = isset( $cart_item['data'] ) && is_callable( array( $cart_item['data'], 'get_id' ) ) ? $cart_item['data']->get_id() : false;
		$cart_item_key = isset( $cart_item['key'] ) ? $cart_item['key'] : false;

		foreach ( $this->rules as $rule ) {

			if ( ! (
					$product_id &&
					$cart_item_key &&
					$rule->is_enabled() &&
					$rule->is_available_for_current_user_role() &&
					$rule->is_buy_product( $product_id ) &&
					$this->check_usage_limit( $rule )
					)
			) {
				// Do nothing.
				continue;
			}

			$rule_id = $rule->get_id();

			if ( 'buy_a_get_a' === $rule->get_type() ) {
				$classname = 'WC_BOGOF_Cart_Rule_Buy_A_Get_A';
				$rule_id  .= '-' . $product_id;
			} elseif ( 'cheapest_free' === $rule->get_type() ) {
				$classname = 'WC_BOGOF_Cart_Rule_Cheapest_Free';
			} elseif ( $rule->is_individual() ) {
				$classname = 'WC_BOGOF_Cart_Rule_Individual';
				$rule_id  .= '-' . $product_id;
			} else {
				$classname = 'WC_BOGOF_Cart_Rule';
			}

			if ( empty( $this->cart_rules[ $rule_id ] ) ) {
				// Add the rule.
				$this->cart_rules[ $rule_id ] = new $classname( $rule );
				$this->cart_rules[ $rule_id ]->set_id( $rule_id );

				if ( is_callable( array( $this->cart_rules[ $rule_id ], 'set_product_id' ) ) ) {
					$this->cart_rules[ $rule_id ]->set_product_id( $product_id );
				}

				$changed = true;
			}

			if ( ! isset( $this->cart_rules_ref[ $cart_item_key ] ) ) {
				$this->cart_rules_ref[ $cart_item_key ] = array();
			}
			$this->cart_rules_ref[ $cart_item_key ][] = $rule_id;
		}

		if ( did_action( 'wc_bogof_cart_rules_loaded' ) && $changed ) {
			// Sort rules.
			$this->sort();
		}
	}

	/**
	 * Check the usage limit.
	 *
	 * @param int $rule WC_BOGOF_Rule object.
	 * @return bool
	 */
	protected function check_usage_limit( $rule ) {
		$check = true;
		if ( $rule->get_usage_limit_per_user() > 0 ) {
			$total_uses = $rule->get_used_by_count( wc_bogof_user_ids() ) + $this->get_rule_uses_in_cart( $rule->get_id() );
			$check      = $total_uses < $rule->get_usage_limit_per_user();
		}
		return $check;
	}

	/**
	 * Returns the number of times a rule is used in the cart.
	 *
	 * @param int $rule_id Rule ID.
	 * @return int
	 */
	public function get_rule_uses_in_cart( $rule_id ) {
		$count = 0;
		foreach ( $this->get_all() as $cart_rule_id => $cart_rule ) {
			if ( $cart_rule->get_rule_id() === $rule_id && WC_BOGOF_Cart::cart_rule_in_use( $cart_rule_id ) ) {
				$count++;
			}
		}
		return $count++;
	}

	/**
	 * Did the user update the BOGO rules?
	 *
	 * @return bool
	 */
	public function is_update_required() {
		$session_value = WC()->session->get( 'wc_bogof_rules_version' );
		return ! empty( $session_value ) && $session_value !== $this->get_rules_version();
	}

	/**
	 * Add a rule to the array of rules removed by the user.
	 *
	 * @param string $id Cart Rule ID.
	 */
	public function remove_by_user( $id ) {
		$removed_rules        = WC()->session->get( 'wc_bogof_removed_rules', array() );
		$removed_rules        = is_array( $removed_rules ) ? $removed_rules : array();
		$removed_rules[ $id ] = 1;
		WC()->session->set( 'wc_bogof_removed_rules', $removed_rules );
	}

	/**
	 * Restore a rule of the array of rules removed by the user.
	 *
	 * @param string $cart_item_key Cart item key.
	 */
	public function restore_by_user( $cart_item_key ) {
		$removed_rules = WC()->session->get( 'wc_bogof_removed_rules', array() );

		if ( ! empty( $removed_rules ) ) {

			$cart_rules = $this->get_by_cart_item_key( $cart_item_key );

			foreach ( $cart_rules as $cart_rule ) {
				if ( isset( $removed_rules[ $cart_rule->get_id() ] ) ) {
					unset( $removed_rules[ $cart_rule->get_id() ] );
				}
			}

			if ( 0 < count( $removed_rules ) ) {
				WC()->session->set( 'wc_bogof_removed_rules', $removed_rules );
			} else {
				unset( WC()->session->wc_bogof_removed_rules );
			}
		}
	}

	/**
	 * Update the free items.
	 */
	public function update_cart() {
		// Get removed cart rules.
		$removed_rules = WC()->session->get( 'wc_bogof_removed_rules', array() );

		// Cart rules refresh.
		foreach ( $this->cart_rules as $cart_rule_id => $cart_rule ) {

			if ( is_callable( array( $cart_rule, 'update_free_items_qty' ) ) ) {

				$add_to_cart = empty( $removed_rules[ $cart_rule->get_id() ] );

				// Update free items.
				$cart_rule->update_free_items_qty( $add_to_cart );
			}
		}

		// Set the BOGO rules version after update.
		WC()->session->set( 'wc_bogof_rules_version', $this->get_rules_version() );
	}

	/**
	 * Get the rules version.
	 *
	 * @return string
	 */
	protected function get_rules_version() {
		return md5( $this->rules_version . implode( ',', array_keys( $this->cart_rules ) ) );
	}

	/**
	 * Callback function to sort the cart rules array.
	 *
	 * @param WC_BOGOF_Cart_Rule $a Cart rule to compare.
	 * @param WC_BOGOF_Cart_Rule $b Cart rule to compare.
	 * @return int
	 */
	protected function sort_callback( $a, $b ) {
		$count = array(
			'a' => 0,
			'b' => 0,
		);

		$cmp = $b->get_rule()->get_priority() - $a->get_rule()->get_priority();

		if ( 0 === $cmp && $a->get_rule()->get_exclude_other_rules() && $b->get_rule()->get_exclude_other_rules() && $a->get_rule()->get_minimum_amount() !== $b->get_rule()->get_minimum_amount() ) {
			// Order by minimum amount.
			$cmp = floatval( $a->get_rule()->get_minimum_amount() ) > floatval( $b->get_rule()->get_minimum_amount() ) ? -1 : 1;
		}

		if ( 0 === $cmp ) {
			// Order by number of items cover.
			foreach ( array( 'a', 'b' ) as $rule ) {
				$id = ${$rule}->get_id();
				foreach ( $this->cart_rules_ref as $rule_ref ) {
					if ( in_array( $id, $rule_ref, true ) ) {
						$count[ $rule ]++;
					}
				}
			}

			if ( $count['a'] === $count['b'] ) {
				// Order by buy quantity.
				foreach ( array( 'a', 'b' ) as $rule ) {
					$count[ $rule ] += intval( ${$rule}->get_rule()->get_min_quantity() );
				}
			}

			$cmp = $count['a'] - $count['b'];
		}
		return $cmp;
	}

	/**
	 * IteratorAggregate implementation.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator( $this->cart_rules );
	}

	/**
	 * Countable.
	 *
	 * @return int
	 */
	public function count() {
		return count( $this->cart_rules );
	}
}
