<?php
/**
 * Buy One Get One rule advanced data panel.
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="advanced_bogof_rule_data" class="panel woocommerce_options_panel">
	<div class="options_group">
		<?php
		woocommerce_wp_checkbox(
			array(
				'id'          => '_exclude_other_rules',
				'label'       => __( 'Ignore other rules', 'wc-buy-one-get-one-free' ),
				'description' => __( 'Check this box to ignore the other rules when this rule matches.', 'wc-buy-one-get-one-free' ),
				'value'       => wc_bool_to_string( $rule->get_exclude_other_rules() ),
			)
		);
		?>
	</div>
	<div class="options_group">
		<?php
		woocommerce_wp_checkbox(
			array(
				'id'          => '_exclude_coupon_validation',
				'label'       => __( 'No coupon validations', 'wc-buy-one-get-one-free' ),
				'description' => __( 'Check this box to do not apply the coupon restrictions to the free items (recommended).', 'wc-buy-one-get-one-free' ),
				'value'       => wc_bool_to_string( $rule->get_exclude_coupon_validation() ),
			)
		);
		?>
	</div>
</div>
