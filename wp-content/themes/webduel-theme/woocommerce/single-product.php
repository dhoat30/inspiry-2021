<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
	
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
		// Loop over $cart items
		?>
		
		<?php
		
	?>

<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
?>


<script>

<?php global $product; 
	$term_list = get_the_terms( $product->get_id(), 'product_cat' );
	$term = $term_list[0];
	$variation_id = "No Variation";
	?>
dataLayer = window.dataLayer || [];
dataLayer.push({ ecommerce: null }); 
dataLayer.push({
  'event': 'productDetails',
  'ecommerce': {
  	'detail': {
  	'actionField': {'list': localStorage.getItem('<?php echo $product -> get_id()?>')},          
    'products': [
    	<?php 
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
				
					$stringTitle = str_replace("'", '-', $product->get_name());
					$productTitleSanitized =  preg_replace('/[^A-Za-z0-9\-]/', '', $stringTitle);
			?>
			{
			'name': '<?php echo $productTitleSanitized?>',       // Name or ID is required.
			'id': '<?php echo $product -> get_id()?>',
			'price': '<?php echo $product -> get_price()?>',
			'brand': '<?php echo  $product->get_attribute('pa_brands')?>',
			'category': '<?php echo $term -> name ?>',
			'variant': '<?php echo $variation_id ?>',
			}
    ]
  }
}
});

</script>
<!-- add to cart button google ecommerce -->
<script type="text/javascript">

	<?php
					global $product; 
    				$term_list = get_the_terms( $product->get_id(), 'product_cat' );
    				$term = $term_list[0];
    				$variation_id = "No Variation";

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
					$stringName = str_replace("'", '-', $product->get_name());
					$productNameSanitized =  preg_replace('/[^A-Za-z0-9\-]/', '', $stringName);
	?>

	var thisProduct = {
							'name': '<?php echo $productNameSanitized?>',   
       						'id': '<?php echo $product -> get_id()?>',
       						'price': '<?php echo $product -> get_price()?>',
     			       		'brand': '<?php echo  $product->get_attribute('pa_brands')?>',
                        	'category': '<?php echo $term -> name ?>',
       						'variant': '<?php echo $variation_id ?>',
       						'quantity': '<?php echo $qty; ?>'
	}

	var addToCartBtn = document.getElementsByName("add-to-cart")[0];

		addToCartBtn.addEventListener("click", function(event) {
			// clear the previous data layer
			dataLayer.push({ ecommerce: null }); 
			  dataLayer.push({
					    'event': 'addToCart',
					    'actionField': {'list': localStorage.getItem('<?php echo $product -> get_id()?>')},  
					    'ecommerce': {
					      'currencyCode': 'NZD',
					      'add': {
					        'products': [{
					          'name': thisProduct.name,                  
					          'id': thisProduct.id,
					          'price': thisProduct.price,
					          'brand': thisProduct.brand,
					          'category': thisProduct.category,
					          'variant': thisProduct.variant,
					          'quantity': Number(document.getElementsByClassName("qty")[0].value)   
					         }]
					       }
			   		  	}
			 		 });

					});
				
</script>


<!-- facebook add to cart button -->
<script type="text/javascript">

<?php 
	global $product; 
?>
	let productObject = {
		currency: "NZD", 
		value: '<?php echo $product->get_price()?>',
		content_ids: '<?php echo $product->get_id()?>', 
		content_name: '<?php echo $product->get_name()?>', 
		content_type: "Product", 

	}
	addToCartBtn.addEventListener('click', function(){
		fbq('track', 'AddToCart', productObject);
	})
</script>


<script>
	// single product page visit record 
	pintrk('track', 'pagevisit', {
	currency: 'NZD',
	line_items: [
	{
	product_name: '<?php echo $product->get_name() ?>',
	product_id: '<?php echo $product->get_id()?>',
	product_category: '<?php echo $term->name ?>',
	product_price: '<?php echo $product->get_price()?>',
	product_brand: '<?php echo  $product->get_attribute('pa_brands')?>'
	}
	]
	});

	// add to cart button Pinterest Event
	addToCartBtn.addEventListener('click', function(){
		pintrk('track', 'addtocart', {
			product_price: '<?php echo $product->get_price()?>',
			order_quantity:  Number(document.getElementsByClassName("qty")[0].value),
			currency: 'NZD',
			product_id: '<?php echo $product->get_id()?>', 
			product_category:'<?php echo $term -> name ?>'
			});
	})
 
</script>