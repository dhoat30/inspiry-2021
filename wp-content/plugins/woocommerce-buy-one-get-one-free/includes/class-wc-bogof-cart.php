<?php
/**
 * WooCommerce Buy One Get One Free cart actions.
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_BOGOF_Cart Class
 */
class WC_BOGOF_Cart {


	/**
	 * Cart rules
	 *
	 * @var WC_BOGOF_Cart_Rules
	 */
	public static $cart_rules = null;

	/**
	 * Calculate cart subtotal.
	 *
	 * @var WC_BOGOF_Cart_Totals
	 */
	private static $cart_totals = null;

	/**
	 * Init hooks
	 */
	public static function init() {
		add_filter( 'woocommerce_get_cart_item_from_session', array( __CLASS__, 'get_cart_item_from_session' ), 9999, 3 );
		add_action( 'woocommerce_cart_loaded_from_session', array( __CLASS__, 'cart_loaded_from_session' ), 20 );
		add_action( 'woocommerce_cart_loaded_from_session', array( __CLASS__, 'validate_free_items' ), 30 );
	}

	/**
	 * Update product with the flags.
	 *
	 * @param array  $session_data Session data.
	 * @param array  $values Values.
	 * @param string $key Item key.
	 * @return array
	 */
	public static function get_cart_item_from_session( $session_data, $values, $key ) {
		if ( isset( $values['_bogof_free_item'] ) ) {
			$session_data = self::set_cart_item_free( $session_data, $values['_bogof_free_item'] );
		} elseif ( isset( $values['_bogof_discount'] ) && is_array( $values['_bogof_discount'] ) ) {
			$session_data['data']->_bogof_discount = new WC_BOGOF_Cart_Item_Discount( $session_data, $values['_bogof_discount'] );
		}
		return $session_data;
	}

	/**
	 * Cart loaded from session.
	 */
	public static function cart_loaded_from_session() {
		if ( did_action( 'wc_bogof_cart_rules_loaded' ) ) {
			// Only do it once.
			return;
		}
		self::init_cart_rules();
		self::init_hooks();

		do_action( 'wc_bogof_cart_rules_loaded' );
	}

	/**
	 * Load available rules.
	 */
	private static function init_cart_rules() {
		self::$cart_rules = new WC_BOGOF_Cart_Rules();
		$cart_contents    = WC()->cart->get_cart_contents();
		foreach ( $cart_contents as $cart_item_key => $cart_item ) {
			if ( self::is_free_item( $cart_item ) ) {
				continue;
			}
			self::$cart_rules->add( $cart_item );
		}

		self::$cart_rules->sort();
	}

	/**
	 * Init hooks.
	 */
	private static function init_hooks() {
		// Free items and discounts.
		add_filter( 'woocommerce_add_cart_item_data', array( __CLASS__, 'add_cart_item_data' ), 9999, 3 );
		add_filter( 'woocommerce_add_cart_item', array( __CLASS__, 'add_cart_item' ), 9999 );
		add_action( 'woocommerce_add_to_cart', array( __CLASS__, 'add_to_cart' ), 5 );
		add_action( 'woocommerce_cart_item_removed', array( __CLASS__, 'cart_item_removed' ), 5 );
		add_action( 'woocommerce_after_cart_item_quantity_update', array( __CLASS__, 'cart_item_set_quantity' ), 5 );
		add_action( 'woocommerce_cart_item_restored', array( __CLASS__, 'cart_item_restored' ) );

		// Process orders.
		add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'checkout_validation' ) );
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'checkout_create_order' ) );
		add_action( 'woocommerce_checkout_create_order_line_item', array( __CLASS__, 'checkout_create_order_line_item' ), 10, 3 );

		// Handle product price.
		add_filter( 'woocommerce_product_get_price', array( __CLASS__, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( __CLASS__, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( __CLASS__, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( __CLASS__, 'get_free_product_price' ), 9999, 2 );

		// Check cart items.
		add_action( 'woocommerce_check_cart_items', array( __CLASS__, 'check_cart_items' ) );

		// Display add to cart messages.
		add_filter( 'woocommerce_add_to_cart_redirect', array( __CLASS__, 'add_to_cart_messages' ) );
		add_filter( 'woocommerce_update_cart_action_cart_updated', array( __CLASS__, 'add_to_cart_messages' ) );

		// Coupons.
		add_action( 'woocommerce_applied_coupon', array( __CLASS__, 'cart_update' ), 5 );
		add_action( 'woocommerce_removed_coupon', array( __CLASS__, 'cart_update' ), 5 );
		add_filter( 'woocommerce_coupon_is_valid', array( __CLASS__, 'coupon_is_valid' ), 9999, 2 );
		add_filter( 'woocommerce_coupon_get_items_to_validate', array( __CLASS__, 'coupon_get_items_to_validate' ), 9999 );

		// Cart totals.
		add_action( 'wc_bogof_after_set_cart_item_discount', array( __CLASS__, 'calculate_items_subtotal' ) );

		// Deprecated.
		if ( version_compare( WC_VERSION, '3.7', '<' ) ) {
			add_action( 'woocommerce_before_cart_item_quantity_zero', array( __CLASS__, 'before_cart_item_quantity_zero' ) );
		}
	}

	/**
	 * Validate the free items.
	 */
	public static function validate_free_items() {
		$cart_contents = WC()->cart->get_cart_contents();
		foreach ( $cart_contents as $key => $cart_item ) {
			if ( self::is_free_item( $cart_item ) && ! self::is_valid_free_item( $cart_item ) ) {
				unset( WC()->cart->cart_contents[ $key ] );

			} elseif ( self::is_valid_discount( $cart_item ) ) {
				// Check the rules.
				foreach ( $cart_item['data']->_bogof_discount->get_rules() as $cart_rule_id => $quantity ) {
					if ( ! self::$cart_rules->exists( $cart_rule_id ) ) {
						WC()->cart->cart_contents[ $key ]['data']->_bogof_discount->remove_free_quantity( $cart_rule_id );
					}
				}
				if ( ! WC()->cart->cart_contents[ $key ]['data']->_bogof_discount->has_discount() ) {
					unset( WC()->cart->cart_contents[ $key ]['data']->_bogof_discount );
					unset( WC()->cart->cart_contents[ $key ]['_bogof_discount'] );
				}
			}
		}
	}

	/**
	 * Set the free item. Using this filter for WooCommerce generates a new cart item key.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @param int   $product_id The product ID.
	 * @param int   $variation_id The variation ID.
	 * @return array
	 */
	public static function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
		if ( self::is_free_item( $cart_item_data ) || ! wc_bogof_is_choose_your_gift_request() ) {
			return $cart_item_data;
		}

		$product_id = $variation_id ? $variation_id : $product_id;

		// Cart rules refresh.
		foreach ( self::$cart_rules as $cart_rule ) {
			if ( $cart_rule->is_shop_avilable_free_product( $product_id ) ) {
				$cart_item_data = self::set_cart_item_free( $cart_item_data, $cart_rule->get_id() );
				break;
			}
		}

		return $cart_item_data;
	}

	/**
	 * Update the price of the free items.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @return array
	 */
	public static function add_cart_item( $cart_item_data ) {
		$cart_rule_id = self::is_valid_free_item( $cart_item_data );

		if ( $cart_rule_id ) {
			$cart_item_data = self::set_free_price( $cart_item_data, $cart_rule_id );
			$cart_rule      = self::$cart_rules->get( $cart_rule_id );
			// Add the max qty.
			$cart_item_data['_bogof_max_qty'] = $cart_rule->get_shop_free_quantity();
		}
		return $cart_item_data;
	}

	/**
	 * Add to cart action.
	 *
	 * @param string $cart_item_key Cart item key.
	 */
	public static function add_to_cart( $cart_item_key ) {
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );

		if ( empty( $cart_item ) ) {
			return;
		}

		if ( self::is_valid_free_item( $cart_item ) && ! empty( $cart_item['_bogof_max_qty'] ) ) {

			$quantity     = isset( $cart_item['quantity'] ) ? absint( $cart_item['quantity'] ) : 0;
			$max_free_qty = absint( $cart_item['_bogof_max_qty'] );

			if ( $quantity > $max_free_qty ) {
				// Add to cart the extra qty.
				self::add_to_cart_from_item( $cart_item, $quantity - $max_free_qty );
			}

			do_action( 'wc_bogof_free_item_added_to_cart', $cart_item );

		} elseif ( ! empty( $cart_item['data'] ) && is_callable( array( $cart_item['data'], 'get_id' ) ) ) {

			self::$cart_rules->add( $cart_item );

			// Refresh cart rules.
			self::cart_update();
		}
	}

	/**
	 * Update free items qty on item removed.
	 *
	 * @param string $cart_item_key Cart item key.
	 */
	public static function cart_item_removed( $cart_item_key ) {
		$cart_contents = WC()->cart->get_removed_cart_contents();
		$cart_item     = $cart_contents[ $cart_item_key ];
		$cart_rule_id  = self::is_valid_free_item( $cart_item );

		if ( $cart_rule_id ) {
			// Add the rule to the removed rules array.
			self::$cart_rules->remove_by_user( $cart_rule_id );
		} else {
			// Restore the cart rule.
			self::$cart_rules->restore_by_user( $cart_item_key );

			// Refresh cart rules.
			self::cart_update();
		}
	}

	/**
	 * Update free items qty on item qty updated.
	 *
	 * @param string $cart_item_key Cart item key.
	 */
	public static function cart_item_set_quantity( $cart_item_key ) {
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );

		if ( empty( $cart_item ) || self::is_free_item( $cart_item ) ) {
			return;
		}

		// Restore the cart rule.
		self::$cart_rules->restore_by_user( $cart_item_key );

		// Refresh cart rules.
		self::cart_update();
	}

	/**
	 * Unset free item flag after item restored
	 *
	 * @param string $cart_item_key Cart item key.
	 */
	public static function cart_item_restored( $cart_item_key ) {
		$cart_contents = WC()->cart->get_cart_contents();
		$cart_item     = isset( $cart_contents[ $cart_item_key ] ) ? $cart_contents[ $cart_item_key ] : false;

		if ( $cart_item && self::is_free_item( $cart_item ) ) {
			unset( WC()->cart->cart_contents[ $cart_item_key ]['_bogof_free_item'] );
		}
	}

	/**
	 * Check the usage limit of each rule in the cart.
	 *
	 * @param array $posted Post data.
	 */
	public static function checkout_validation( $posted ) {
		$rules_count = array();

		foreach ( self::$cart_rules as $cart_rule_id => $cart_rule ) {

			$rule    = $cart_rule->get_rule();
			$rule_id = $rule->get_id();

			if ( $rule->get_usage_limit_per_user() > 0 && self::cart_rule_in_use( $cart_rule_id ) ) {

				$rules_count[ $rule_id ] = isset( $rules_count[ $rule_id ] ) ? $rules_count[ $rule_id ] + 1 : 1;

				$customer_ids   = wc_bogof_user_ids();
				$customer_ids[] = empty( $posted['billing_email'] ) ? false : strtolower( sanitize_email( $posted['billing_email'] ) );

				$total_uses = $rule->get_used_by_count( $customer_ids ) + $rules_count[ $rule_id ];

				if ( $total_uses > $rule->get_usage_limit_per_user() ) {
					self::remove_free_items( $cart_rule_id );
					self::remove_discount( $cart_rule_id );

					WC()->session->set( 'refresh_totals', true );

					wc_add_notice( 'You reached the usage limit of the offer.', 'error' );
				}
			}
		}
	}

	/**
	 * Add the rule Id as order metadata.
	 *
	 * @param WC_Order $order Order object.
	 */
	public static function checkout_create_order( $order ) {
		foreach ( self::$cart_rules as $cart_rule_id => $cart_rule ) {
			if ( self::cart_rule_in_use( $cart_rule_id ) ) {
				$cart_rule->get_rule()->increase_usage_count( $order );
			}
		}
	}

	/**
	 * Add the rule ID as item meta.
	 *
	 * @param  WC_Order_Item_Product $item          Order item data.
	 * @param  string                $cart_item_key Cart item key.
	 * @param  array                 $values        Order item values.
	 */
	public static function checkout_create_order_line_item( $item, $cart_item_key, $values ) {
		$rule_ids  = array();
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );

		if ( self::is_valid_free_item( $cart_item ) ) {
			$cart_rule = self::$cart_rules->get( $cart_item['_bogof_free_item'] );
			if ( $cart_rule ) {
				$rule_ids[] = $cart_rule->get_rule_id();
			}
		} elseif ( self::is_valid_discount( $cart_item ) ) {
			foreach ( array_keys( $cart_item['data']->_bogof_discount->get_rules() ) as $cart_rule_id ) {
				$cart_rule = self::$cart_rules->get( $cart_rule_id );
				if ( $cart_rule ) {
					$rule_ids[] = $cart_rule->get_rule_id();
				}
			}
		}

		foreach ( array_unique( $rule_ids ) as $rule_id ) {
			$item->add_meta_data( '_wc_bogof_rule_id', $rule_id );
		}
	}

	/**
	 * Refresh cart rules quantities and discounts.
	 */
	public static function cart_update() {
		// Remove actions that also run cart_update.
		remove_action( 'woocommerce_add_to_cart', array( __CLASS__, 'add_to_cart' ), 5 );
		remove_action( 'woocommerce_cart_item_removed', array( __CLASS__, 'cart_item_removed' ), 5 );
		remove_action( 'woocommerce_after_cart_item_quantity_update', array( __CLASS__, 'cart_item_set_quantity' ), 5 );
		// Do not calcule totals after add or remove free items.
		remove_action( 'woocommerce_cart_item_removed', array( WC()->cart, 'calculate_totals' ), 20, 0 );
		remove_action( 'woocommerce_add_to_cart', array( WC()->cart, 'calculate_totals' ), 20, 0 );

		// Remove all discounts.
		self::remove_discount( wc_list_pluck( self::$cart_rules->get_all(), 'get_id' ) );

		self::$cart_rules->update_cart();

		// Add the actions.
		add_action( 'woocommerce_add_to_cart', array( __CLASS__, 'add_to_cart' ), 5 );
		add_action( 'woocommerce_cart_item_removed', array( __CLASS__, 'cart_item_removed' ), 5 );
		add_action( 'woocommerce_after_cart_item_quantity_update', array( __CLASS__, 'cart_item_set_quantity' ), 5 );
		add_action( 'woocommerce_cart_item_removed', array( WC()->cart, 'calculate_totals' ), 20, 0 );
		add_action( 'woocommerce_add_to_cart', array( WC()->cart, 'calculate_totals' ), 20, 0 );

		// Cart rules updated.
		do_action( 'wc_bogof_after_cart_rules_update' );
	}

	/**
	 * Return the zero price for free product in the cart.
	 *
	 * @param mixed      $price Product price.
	 * @param WC_Product $product Product instance.
	 */
	public static function get_free_product_price( $price, $product ) {
		if ( ! empty( $product->_bogof_free_item ) && self::$cart_rules->exists( $product->_bogof_free_item ) ) {
			$price = 0;
		} elseif ( self::is_valid_discount( $product ) && $product->_bogof_discount->has_discount() ) {
			$price = $product->_bogof_discount->get_sale_price();
		}
		return $price;
	}

	/**
	 * Update the cart rules if required.
	 */
	public static function check_cart_items() {
		if ( self::$cart_rules->is_update_required() ) {
			self::cart_update();
			WC()->cart->calculate_totals();
		}
	}

	/**
	 * Add the free item added messages.
	 *
	 * @param mixed $value Value to return. Filter call function.
	 */
	public static function add_to_cart_messages( $value ) {
		foreach ( self::$cart_rules as $cart_rule ) {
			$cart_rule->add_messages();
		}
		return $value;
	}

	/**
	 * Disable the usage of coupons is there is a free item in the cart.
	 *
	 * @param bool      $is_valid Is valid?.
	 * @param WC_Coupon $coupon Coupon object.
	 * @return bool
	 */
	public static function coupon_is_valid( $is_valid, $coupon ) {
		if ( 'yes' !== get_option( 'wc_bogof_disable_coupons', 'no' ) || ! $is_valid ) {
			return $is_valid;
		}

		// Checks the coupon is in a cart rule.
		foreach ( self::$cart_rules as $cart_rule ) {
			if ( in_array( $coupon->get_id(), $cart_rule->get_rule()->get_coupon_ids() ) ) { // phpcs:ignore WordPress.PHP.StrictInArray
				return true;
			}
		}

		// If there is a free item coupon is invalid.
		foreach ( WC()->cart->get_cart_contents() as $cart_item ) {
			if ( self::is_valid_free_item( $cart_item ) || self::is_valid_discount( $cart_item ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Remove the free items from the coupon validations.
	 *
	 * @param array $items Items to validate.
	 * @return array
	 */
	public static function coupon_get_items_to_validate( $items ) {
		foreach ( $items as $key => $item ) {
			$cart_rule_id = self::is_valid_free_item( $item->object );
			$cart_rule    = $cart_rule_id ? self::$cart_rules->get( $cart_rule_id ) : false;
			if ( $cart_rule && $cart_rule->get_rule()->get_exclude_coupon_validation() ) {
				unset( $items[ $key ] );
			}
		}
		return $items;
	}

	/**
	 * Before cart item quantity zero.
	 *
	 * @deprecated Since WC3.7
	 * @param string $cart_item_key Cart item key.
	 */
	public static function before_cart_item_quantity_zero( $cart_item_key ) {
		$cart_item = isset( WC()->cart->cart_contents[ $cart_item_key ] ) ? WC()->cart->cart_contents[ $cart_item_key ] : false;

		if ( $cart_item && ! self::is_free_item( $cart_item ) ) {

			// Set the qty to zero before the cart rules update.
			WC()->cart->cart_contents[ $cart_item_key ]['quantity'] = 0;

			self::cart_update();
		}
	}

	/**
	 * Calculate items subtotal after adding a discount.
	 */
	public static function calculate_items_subtotal() {
		if ( ! empty( self::$cart_totals ) ) {
			self::$cart_totals->calculate_items_subtotal();
		}
	}

	/**
	 * ---------------------------------------------
	 * Helper functions.
	 * ---------------------------------------------
	 */

	/**
	 * Return the cart subtotal.
	 *
	 * @since 2.1.0
	 * @return float
	 */
	public static function cart_subtotal() {
		if ( empty( self::$cart_totals ) ) {
			self::$cart_totals = new WC_BOGOF_Cart_Totals();
		}
		return self::$cart_totals->get_subtotal();
	}

	/**
	 * Add to cart a product from a cart item data array.
	 *
	 * @since 2.1.0
	 * @param array $cart_item_data Cart item data.
	 * @param int   $qty Contains the quantity of the item to add.
	 * @param array $extra_data Data to add to the item that is not present in the original item.
	 * @return string
	 */
	public static function add_to_cart_from_item( $cart_item_data, $qty, $extra_data = array() ) {
		// Ignore cart_item_data when the function is trigger from the "add to cart" form. Prevent issues with Product add-ons.
		$item_data = isset( $_REQUEST['add-to-cart'] ) && is_numeric( wp_unslash( $_REQUEST['add-to-cart'] ) ) ? array() : $cart_item_data; // phpcs:ignore WordPress.Security.NonceVerification

		$product_id   = isset( $cart_item_data['product_id'] ) ? $cart_item_data['product_id'] : 0;
		$variation_id = isset( $cart_item_data['variation_id'] ) ? $cart_item_data['variation_id'] : 0;
		$variation    = isset( $cart_item_data['variation'] ) ? $cart_item_data['variation'] : array();

		unset( $item_data['key'] );
		unset( $item_data['product_id'] );
		unset( $item_data['variation_id'] );
		unset( $item_data['variation'] );
		unset( $item_data['quantity'] );
		unset( $item_data['data'] );
		unset( $item_data['data_hash'] );
		unset( $item_data['_bogof_free_item'] );
		unset( $item_data['_bogof_discount'] );
		unset( $item_data['_bogof_max_qty'] );

		$item_data = array_merge( $item_data, $extra_data );

		return WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation, $item_data );
	}

	/**
	 * Set a free cart item.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @param mixed $cart_rule_id Rule ID.
	 * @return array
	 */
	public static function set_cart_item_free( $cart_item_data, $cart_rule_id ) {
		$cart_item_data['_bogof_free_item'] = $cart_rule_id;

		$cart_item_data = self::set_free_price( $cart_item_data, $cart_rule_id );

		return $cart_item_data;
	}

	/**
	 * Set product price to zero.
	 *
	 * @param array $cart_item Cart item data.
	 * @param mixed $cart_rule_id Rule ID.
	 * @return array
	 */
	private static function set_free_price( &$cart_item, $cart_rule_id ) {
		if ( isset( $cart_item['data'] ) && is_a( $cart_item['data'], 'WC_Product' ) ) {
			$cart_item['data']->set_price( 0 );
			$cart_item['data']->set_sale_price( 0 );
			$cart_item['data']->_bogof_free_item = $cart_rule_id;
		}
		return $cart_item;
	}

	/**
	 * Set an discount price.
	 *
	 * @param string $cart_item_key Cart item key.
	 * @param mixed  $cart_rule_id Rule ID.
	 * @param int    $free_qty Free quantity.
	 * @return array
	 */
	public static function set_cart_item_discount( $cart_item_key, $cart_rule_id, $free_qty ) {
		$cart_item_data = WC()->cart->cart_contents[ $cart_item_key ];

		if ( ! isset( $cart_item_data['data'] ) || ! is_object( $cart_item_data['data'] ) ) {
			return;
		}

		$_product = &$cart_item_data['data'];

		if ( ! isset( $_product->_bogof_discount ) || ! is_a( $_product->_bogof_discount, 'WC_BOGOF_Cart_Item_Discount' ) ) {
			$_product->_bogof_discount = new WC_BOGOF_Cart_Item_Discount( $cart_item_data );
		}

		$_product->_bogof_discount->set_free_quantity( $cart_rule_id, $free_qty );

		WC()->cart->cart_contents[ $cart_item_key ]['_bogof_discount'] = $_product->_bogof_discount->get_rules(); // Do not store objects in the session.

		do_action( 'wc_bogof_after_set_cart_item_discount', $cart_item_data, $_product->_bogof_discount );
	}

	/**
	 * Is a free cart item?.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @return bool
	 */
	public static function is_free_item( $cart_item_data ) {
		return isset( $cart_item_data['_bogof_free_item'] );
	}

	/**
	 * Is a valid free cart item?.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @return string Cart rule ID or FALSE if it does not valid.
	 */
	public static function is_valid_free_item( $cart_item_data ) {
		return isset( $cart_item_data['_bogof_free_item'] ) && self::$cart_rules->exists( $cart_item_data['_bogof_free_item'] ) ? $cart_item_data['_bogof_free_item'] : false;
	}

	/**
	 * Is a valid offer?.
	 *
	 * @param array|object $data Data to check.
	 * @return bool
	 */
	public static function is_valid_discount( $data ) {
		$discount = false;
		if ( is_array( $data ) && isset( $data['data'] ) && is_object( $data['data'] ) && isset( $data['data']->_bogof_discount ) ) {
			$discount = $data['data']->_bogof_discount;
		} elseif ( is_object( $data ) && isset( $data->_bogof_discount ) ) {
			$discount = $data->_bogof_discount;
		}
		return $discount && is_a( $discount, 'WC_BOGOF_Cart_Item_Discount' ) && $discount->has_cart_rule( self::$cart_rules->ids() );
	}

	/**
	 * Returns the free items in the cart of a rule.
	 *
	 * @param mixed $cart_rule_id Rule ID.
	 * @return array
	 */
	public static function get_free_items( $cart_rule_id ) {
		$items         = array();
		$cart_contents = WC()->cart->get_cart_contents();
		foreach ( $cart_contents as $key => $cart_item ) {
			if ( self::is_valid_free_item( $cart_item ) === $cart_rule_id ) {
				$items[ $key ] = $cart_item;
			}
		}
		return $items;
	}

	/**
	 * Returns the number of free items in the cart of a rule.
	 *
	 * @param mixed $cart_rule_id Cart rule ID.
	 * @return int
	 */
	public static function get_free_quantity( $cart_rule_id ) {
		$qty           = 0;
		$cart_contents = self::get_free_items( $cart_rule_id );
		foreach ( $cart_contents as $cart_item ) {
			$qty += $cart_item['quantity'];
		}
		return $qty;
	}

	/**
	 * Removes the free items of a cart rule.
	 *
	 * @param mixed $cart_rule_id Cart rule ID.
	 */
	public static function remove_free_items( $cart_rule_id ) {
		$free_items = self::get_free_items( $cart_rule_id );
		foreach ( array_keys( $free_items ) as $cart_item_key ) {
			unset( WC()->cart->cart_contents[ $cart_item_key ] );
		}
	}

	/**
	 * Removes the discount of a cart rule.
	 *
	 * @param string|array $cart_rule_id Cart rule ID or array of IDs.
	 */
	public static function remove_discount( $cart_rule_id ) {
		$cart_contents = WC()->cart->get_cart_contents();
		$cart_rule_ids = is_array( $cart_rule_id ) ? $cart_rule_id : array( $cart_rule_id );

		foreach ( $cart_rule_ids as $id ) {
			foreach ( self::$cart_rules->get_cart_item_keys( $id ) as $item_key ) {
				if ( isset( $cart_contents[ $item_key ] ) && self::is_valid_discount( $cart_contents[ $item_key ] ) ) {
					WC()->cart->cart_contents[ $item_key ]['data']->_bogof_discount->remove_free_quantity( $id );
					WC()->cart->cart_contents[ $item_key ]['_bogof_discount'] = WC()->cart->cart_contents[ $item_key ]['data']->_bogof_discount->get_rules();
				}
			}
		}
	}

	/**
	 * Returns the number of items available for free in the shop.
	 *
	 * @return int
	 */
	public static function get_shop_free_quantity() {
		$qty = 0;
		foreach ( self::$cart_rules as $rule ) {
			$qty += $rule->get_shop_free_quantity();
		}
		return $qty;
	}

	/**
	 * Returns the free available qty for a product.
	 *
	 * @param WC_Product $product Product instance.
	 * @return int
	 */
	public static function get_product_shop_free_quantity( $product ) {
		$qty = 0;
		foreach ( self::$cart_rules as $cart_rule ) {
			if ( $cart_rule->is_shop_avilable_free_product( $product ) ) {
				$qty += $cart_rule->get_shop_free_quantity();
			}
		}
		return $qty;
	}

	/**
	 * Returns the hash based on cart contents and bogo rules.
	 *
	 * @return string hash for cart content
	 */
	public static function get_hash() {
		if ( ! isset( WC()->cart ) ) {
			return false;
		}
		$pieces = array(
			WC_Cache_Helper::get_transient_version( 'bogof_rules' ),
		);
		foreach ( self::$cart_rules as $rule_id => $rule ) {
			$pieces[ $rule_id ] = $rule->get_free_products_in();
		}
		return md5( wp_json_encode( $pieces ) );
	}

	/**
	 * Is there a free item in the cart that that belongs to the "cart rule"?
	 *
	 * @param string $cart_rule_id Cart rule ID.
	 * @return boolean
	 */
	public static function cart_rule_has_free_item( $cart_rule_id ) {
		return count( self::get_free_items( $cart_rule_id ) ) > 0;
	}

	/**
	 * Is there a discount in the cart that that belongs to the "cart rule"?
	 *
	 * @param string $cart_rule_id Cart rule ID.
	 * @return boolean
	 */
	public static function cart_rule_has_discount( $cart_rule_id ) {
		$has_discount  = false;
		$cart_contents = WC()->cart->get_cart_contents();
		foreach ( $cart_contents as $cart_item ) {
			if ( self::is_valid_discount( $cart_item ) && $cart_item['data']->_bogof_discount->has_cart_rule( $cart_rule_id ) ) {
				$has_discount = true;
				break;
			}
		}
		return $has_discount;
	}

	/**
	 * Returns is the cart rule is in use.
	 *
	 * @param string $cart_rule_id Cart rule ID.
	 * @return boolean
	 */
	public static function cart_rule_in_use( $cart_rule_id ) {
		return self::cart_rule_has_free_item( $cart_rule_id ) || self::cart_rule_has_discount( $cart_rule_id );
	}
}
