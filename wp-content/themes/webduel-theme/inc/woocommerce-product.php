<?php

// add product custom field
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields()
{
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => '_supplier_name',
            'placeholder' => 'Add Supplier Name',
            'label' => __('Supplier Name', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    //Custom Product Number Field
    woocommerce_wp_text_input(
        array(
            'id' => '_product_cost',
            'placeholder' => 'Add Product Cost',
            'label' => __('Product Cost', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    
    echo '</div>';
}

function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_supplier_name = $_POST['_supplier_name'];
    if (!empty($woocommerce_custom_supplier_name))
        update_post_meta($post_id, '_supplier_name', esc_attr($woocommerce_custom_supplier_name));
    // Custom Product Number Field
    $woocommerce_product_cost = $_POST['_product_cost'];
    if (!empty($woocommerce_product_cost))
        update_post_meta($post_id, '_product_cost', esc_attr($woocommerce_product_cost));
}


// related product loop 
add_action('woocommerce_after_single_product_summary', 'relatedProductLoop', 30); 

function relatedProductLoop(){ 
   echo do_shortcode('[related_product_loop_short_code]');
}


// single product page wishlist container and add brand name before title 
add_action("woocommerce_single_product_summary", "single_product_page_title_start", 1); 

function single_product_page_title_start(){ 
    global $product; 
    // find the brand name of the product
    $brand = array_shift( wc_get_product_terms( $product->id, 'pa_brands', array( 'fields' => 'names' ) ) );

    echo  '<div class="single-product-before-title-container">';
        echo '<div class="poppins-font brand-name">'; 
        echo $brand; 
        echo '</div>';
        echo '<div class="design-board-container">'. do_shortcode('[design_board_button_code]').'</div>';
    echo '</div>';
}

// single product page share container 
add_action("woocommerce_single_product_summary", "share_code_after_cart", 50); 

//add availabilty and share options
function share_code_after_cart(){ 
    echo '<div class="margin-elements">';
    do_action('add_availability_share'); 
    echo '</div>';
}

// product loop page - add design board buttons 
add_action('woocommerce_before_shop_loop_item_title', 'loop_product_design_board_buttons', 5); 

function loop_product_design_board_buttons(){
    echo do_shortcode('[design_board_button_code]');
}

// yith wishlist hooks 
add_action('yith_wcwl_table_after_product_name', 'addFreeShippingWishlist', 10);

function addFreeShippingWishlist() { 
    echo do_shortcode('[add_free_shipping_tag]'); 
}

// wishlist title 
remove_action('yith_wcwl_before_wishlist_title', "YITH_WCWL_Frontend_Premium", 10); 


// remove product loop title
add_filter('woocommerce_show_page_title', 'webduel_hide_shop_page_title');
 
function webduel_hide_shop_page_title($title) {
   if (is_shop()) $title = false;
   return $title;
}


// yoast breadcrumb
 add_action('woocommerce_before_main_content', 'webduel_filter_button', 10); 

 function webduel_filter_button(){ 
     if(is_archive() || is_shop() || is_product_category()){ 
        echo '<div class="breadcrumb-container"> 
        ';
     }
   
 }

//  add mobile filter button
 add_action('woocommerce_before_main_content', 'webduelClosingDiv', 30); 

 function webduelClosingDiv(){
     if(is_archive() || is_shop() || is_product_category()){ 
        echo '<div class="mobile">'; 
        echo do_shortcode('[add_filter_button]'); 
        echo '</div>'; 
    echo '</div>'; 
     }
}

// add fixed filter button for mobile 
add_action('woocommerce_after_main_content', function(){ 
    if(is_archive() || is_shop() || is_product_category()){ 
        echo '<div class="fixed-filter-button">'; 
        echo do_shortcode('[add_filter_button]'); 
        echo '</div>'; 
    echo '</div>'; 
    }
}, 10); 

// add category and tag banner
add_action('add_filters', 'add_category_banner_webduel', 1); 
function add_category_banner_webduel(){ 
    // add category banner
    global $wp_query;
    $cat = $wp_query->get_queried_object();
       $catID = $cat->term_id; 
       $thumbnail_id =   get_term_meta( $catID, 'thumbnail_id', true );
       $imageLarge = wp_get_attachment_image_src($thumbnail_id, 'large' );
       $imageMedium = wp_get_attachment_image_src($thumbnail_id, 'thumbnail_image_width' );
        if($thumbnail_id){ 
          echo ' <picture>
            <source media="(min-width:1366px)" srcset="'.$imageLarge[0].'">
            <source media="(min-width:600px)" srcset="'.$imageLarge[0].'">
            <img class="product-cat-banner"  loading="lazy" src="'.$imageMedium[0].'"
            alt="<?php echo get_the_title();?>" width="100%" >
            </picture>'; 
        }

        // add tag banner
        $current_tags = get_the_terms( get_the_ID(), 'product_tag' );
        $image_id = get_field('banner', $current_tags[0]);
        // check if the first tag has an image
        if(!$image_id){ 
            $image_id = get_field('banner', $current_tags[1]);
        }

        // get one image for desktop and one for mobile
        $tagImage = wp_get_attachment_image_src($image_id, 'full' );
        $tagImageMobile = wp_get_attachment_image_src($image_id, 'large' );
        if($tagImageMobile && $tagImage){ 
            echo ' <picture>
            <source media="(min-width:1366px)" srcset="'.$tagImage[0].'">
            <source media="(min-width:600px)" srcset="'.$tagImage[0].'">
            <img class="product-cat-banner"  loading="lazy" src="'.$tagImageMobile[0].'"
            alt="<?php echo get_the_title();?>" width="100%" >
            </picture>'; 
        }
}

// remove page title on woocommerce product loop 
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10, 2 ); 
function remove_cat_title_from_cat_or_shop_page($category){
    if ( is_product_category() || is_shop() ) {
        echo"<style>";
        echo".woocommerce ul.products li.product .woocommerce-loop-category__title{display:none!important;}";
        echo"</style>";
    } 
}
add_filter( 'woocommerce_before_shop_loop', 'remove_cat_title_from_cat_or_shop_page');

add_filter( 'woocommerce_show_page_title', 'not_a_shop_page' );
function not_a_shop_page() {
    return boolval(is_shop());
}