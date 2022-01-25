<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('add_filters');
do_action( 'woocommerce_before_main_content' );
?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title section-font-size"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<div class="facetwp-template">
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked 	woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			?>

			
			
			<?php
			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );
			
			wc_get_template_part( 'content', 'product' );
		
		}
	}
	?>
	
	<?php

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}
	?> 

</div>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
//do_action( 'woocommerce_sidebar' );

do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */

get_footer( 'shop' );
?>

<script>
dataLayer.push({
  'event': 'productImpressions',
  'ecommerce': {
    'currencyCode': 'NZD',                       // Local currency is optional.
    'impressions': [
    	<?php 

    		if ( wc_get_loop_prop( 'total' ) ) {
    			$i = 0;
    			while ( have_posts() ) {
    				$i++;
    				the_post();
    				global $product; 
    				$term_list = get_the_terms( $product->get_id(), 'product_cat' );
    				$term = $term_list[0];
    				$variation_id = "No Variation";
    				$list = "Unknown List";
    				$page_title = get_the_title();

    				if( $product->is_type('variable') ) {
    					foreach($product->get_available_variations() as $variation_values ){

    								foreach($variation_values['attributes'] as $key => $attribute_value ){
    									$attribute_name = str_replace( 'attribute_', '', $key );
    									$default_value = $product->get_variation_default_attribute($attribute_name);
    									if( $default_value == $attribute_value ){
    										$is_default_variation = true;
    									} else {
    										$is_default_variation = false;
                 				  		 	break; // Stop this loop to start next main lopp
					               		}
					           		 }
					            	if( $is_default_variation ){
					            		$variation_id = $variation_values['variation_id'];
					                break; // Stop the main loop
					            }
					        }
					} //end of variable product type condition
				
				
			?>
					{
					'name': '<?php echo $product -> get_name()?>',       // Name or ID is required.
					'id': '<?php echo $product -> get_id()?>',
					'price': '<?php echo $product -> get_price()?>',
					'brand': '<?php echo  $product->get_attribute('pa_brands')?>',
					'category': '<?php echo $term -> name ?>',
					'variant': '<?php echo $variation_id ?>',
					'list': '<?php woocommerce_page_title(); ?>',
					'position': '<?php echo $i ?>'
					},

					<?php }
		}
		?>

     ]
  }
});

</script>


<!-- click dynamic data for google tag manager -->

<script type="text/javascript">
var productObj = {};

<?php

if ( wc_get_loop_prop( 'total' ) ) {

				$i = 0;
    			while ( have_posts() ) {
    				$i++;
    				the_post();
    				global $product; 

    				$term_list = get_the_terms( $product->get_id(), 'product_cat' );
    				$term = $term_list[0];
    				$variation_id = "No Variation";
    				$list = "Unknown List";
    				$page_title = get_the_title();

    				if( $product->is_type('variable') ) {

    							foreach($product->get_available_variations() as $variation_values ){
    								foreach($variation_values['attributes'] as $key => $attribute_value ){
    									$attribute_name = str_replace( 'attribute_', '', $key );
    									$default_value = $product->get_variation_default_attribute($attribute_name);
    									if( $default_value == $attribute_value ){
    										$is_default_variation = true;
    									} else {
    										$is_default_variation = false;
                 				  		 	break; // Stop this loop to start next main lopp
					               		}
					           		 }
					            	if( $is_default_variation ){
					            		$variation_id = $variation_values['variation_id'];
					                break; // Stop the main loop
					            }
					        }
					} //end of variable product type condition

					?>

					var thisProduct = {
							'name': '<?php echo $product -> get_name()?>',   
       						'id': '<?php echo $product -> get_id()?>',
       						'price': '<?php echo $product -> get_price()?>',
     			       		'brand': '<?php echo  $product->get_attribute('pa_brands')?>',
                        	'category': '<?php echo $term -> name ?>',
       						'variant': '<?php echo $variation_id ?>',
       						'list': '<?php woocommerce_page_title(); ?>',
       						'position': '<?php echo $i ?>'
					}

					productObj['<?php echo get_permalink( $product->get_id()); ?>'] = thisProduct;
					<?php
			
    		}
			wp_reset_postdata();
    	}



?>
	

	var products = document.getElementsByClassName("woocommerce-loop-product__link");

	for(var i = 0; i < products.length; i++) {

		products[i].addEventListener("click", function(event) {

            var clickedProductURL = event.currentTarget.href;
			var clickedProduct = productObj[clickedProductURL];

  	dataLayer.push({
		    'event': 'productClick',
		    'ecommerce': {
		      'click': {
		        'actionField': {'list': clickedProduct.list},      // Optional list property.
		        'products': [{
		          'name': clickedProduct.name,                      // Name or ID is required.
		          'id': clickedProduct.id,
		          'price': clickedProduct.price,
		          'brand': clickedProduct.brand,
		          'category': clickedProduct.category,
		          'variant': clickedProduct.variant,
		          'position': clickedProduct.position
		         }]
		       }
   		  	}
 		 });

  		localStorage.setItem(clickedProduct.id, clickedProduct.list);

		});

	}

</script>

<!-- add to cart button click dynamic data for google tag manager -->

<script type="text/javascript">
var productObject = {};

	

	var products = document.getElementsByClassName("add_to_cart_button");

	for(var i = 0; i < products.length; i++) {

		products[i].addEventListener("click", function(event) {
			let $ = jQuery; 
			let productData = $(event.target).siblings('.product-meta');
			console.log(productData.data('name'))
  	dataLayer.push({
		    'event': 'addToCartArchive',
		    'ecommerce': {
		      'click': {
		        'actionField': {'list': productData.data('category')},      // Optional list property.
		        'products': [{
		          'name': productData.data('name'),                      // Name or ID is required.
		          'id': productData.data('id'),
		          'price': productData.data('price'),
		          'brand': productData.data('brand'),
		          'category': productData.data('category')
		         }]
		       }
   		  	}
 		 });


		});

	}

</script>




