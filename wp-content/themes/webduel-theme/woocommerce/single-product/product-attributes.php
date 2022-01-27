<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! $product_attributes ) {
	return;
}
?>
<table class="woocommerce-product-attributes shop_attributes">
	
	<?php 
	function array_reorder_keys(&$array, $keynames){
		if(empty($array) || !is_array($array) || empty($keynames)) return;
		if(!is_array($keynames)) $keynames = explode(',',$keynames);
		if(!empty($keynames)) $keynames = array_reverse($keynames);
		foreach($keynames as $n){
			if(array_key_exists($n, $array)){
				$newarray = array($n=>$array[$n]); //copy the node before unsetting
				unset($array[$n]); //remove the node
				$array = $newarray + array_filter($array); //combine copy with filtered array
			}
		}
	}
	
	
	
	array_reorder_keys($product_attributes, 'attribute_pa_brands,attribute_pa_collection,attribute_pa_design-name,attribute_pa_color,attribute_pa_design-style,attribute_pa_pattern,attribute_pa_composition,attribute_pa_viscose,attribute_pa_match,attribute_pa_vertical-pattern-repeat,attribute_pa_horizontal-pattern-repeat,attribute_pa_width,attribute_pa_usage');

		//print_r($product_attributes ); 
		
		//echo $product_attributes; 
	foreach ( $product_attributes as $product_attribute_key => $product_attribute ) : 
		
		?>

		<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr( $product_attribute_key ); ?>">			
				<?php 
					 
				?>
					<th class="woocommerce-product-attributes-item__label"><?php echo wp_kses_post( $product_attribute['label'] ); ?></th>
					<td class="woocommerce-product-attributes-item__value"><?php echo wp_kses_post( $product_attribute['value'] ); ?></td>
			
		</tr>
	<?php endforeach; ?>
</table>
