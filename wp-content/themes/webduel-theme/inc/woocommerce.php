<?php

//add meta info on archive page
add_action( 'woocommerce_after_shop_loop_item', 'add_meta_add_to_cart', 10); 

function add_meta_add_to_cart(){
  global $product; 
  $productPrice = $product->get_price(); 
  $productBrand= $product->get_attribute('pa_brands'); 
  $term_list = get_the_terms( $product->get_id(), 'product_cat' );
  $term = $term_list[0];
  echo "<div class='product-meta' data-name='$product->name' data-id='$product->id' data-sku='$product->sku' data-price='$productPrice' data-brand='$productBrand' data-category='$term->name' data-quantity='1'> </div>";
 
}
//gallery 
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );
function mytheme_add_woocommerce_support(){
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 500,
        'gallery_thumbnail_image_width' => 100,
        'single_image_width' => 900
        ) );
}

//adding container on product archive page
add_action('add_filters', 'add_container', 5); 
function add_container($class){
echo '<section class="product-loop-page-section">';
echo '<div class="container flex-row flex-space-between product-loop-page-container '.$class.'">';

}

//add closing div
add_action('woocommerce_after_main_content', 'add_container_closing_div'); 

function add_container_closing_div(){
echo '</div>';
echo '</section>';
}

function add_double_container_closing_div(){
    echo '</div></div>';
    }

add_action('add_filters', 'add_facetwp_filters', 10); 

function add_facetwp_filters(){
    echo '<div class="facet-wp-container">' ;
    echo do_shortcode('[add_filter_button]');
      echo '<div class="desktop">';   
         
          echo do_shortcode('[facetwp facet="categories"]');  
          echo do_shortcode('[facetwp facet="brand"]');  
          echo do_shortcode('[facetwp facet="collection"]');  
          echo do_shortcode('[facetwp facet="design_style"]');  
          echo do_shortcode('[facetwp facet="colour_family"]');  
          echo do_shortcode('[facetwp facet="pattern"]');  
          echo do_shortcode('[facetwp facet="composition"]');  
          echo do_shortcode('[facetwp facet="availability"]');  
          echo do_shortcode('[facetwp facet="price_range"]');  
        echo '<button class="facet-reset-btn" onclick="FWP.reset()">Reset All Filter</button>'; 
      echo '</div>'; 

      echo '<div class="mobile-filter-container">';   
        echo do_shortcode('[facetwp facet="categories_m"]');  
        echo do_shortcode('[facetwp facet="brand_m"]');  
        echo do_shortcode('[facetwp facet="collection_m"]');  
        echo do_shortcode('[facetwp facet="design_style_m"]');  
        echo do_shortcode('[facetwp facet="colour_family_m"]');  
        echo do_shortcode('[facetwp facet="pattern_m"]');  
        echo do_shortcode('[facetwp facet="composition_m"]');  
        echo do_shortcode('[facetwp facet="availability_m"]');  
        echo do_shortcode('[facetwp facet="price_range_m"]');   
        echo '<button class="close-button show-results"> Show All Results </button>';
        echo '<button class="facet-reset-btn" onclick="FWP.reset()">Reset All Filter</button>'; 
        echo '<i class="fal fa-times close-icon"></i>'; 
      echo '</div>'; 
    echo '</div>';
}


// add facet label 
function fwp_add_facet_labels() {
  ?>
  <script>
  (function($) {
      $(document).on('facetwp-loaded', function() {
          $('.facetwp-facet').each(function() {
              var $facet = $(this);
              var facet_name = $facet.attr('data-name');
              var facet_label = FWP.settings.labels[facet_name];
  
              if ($facet.closest('.facet-wrap').length < 1 && $facet.closest('.facetwp-flyout').length < 1) {
                  $facet.wrap('<div class="facet-wrap"></div>');
                  $facet.before('<h3 class="facet-label">' + facet_label + '</h3>');
              }
          });
      });
  })(jQuery);
  </script>
  <?php
  }
  add_action( 'wp_head', 'fwp_add_facet_labels', 100 );

//adding filter 
//add_action('woocommerce_before_main_content', 'add_facetwp_filters', 1); 

//archive page title filter
//remove ordering 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar' );


//add div container for breadcrumbs and image on single product
//
//gallery thumbnail size
add_filter( 'woocommerce_gallery_thumbnail_size', function( $size ) {
    return 'woocommerce_thumbnail';
    } );
add_action('breadcrumb_image_container', 'add_image_breadcrumb_container'); 
function add_image_breadcrumb_container(){
    if(is_single()){
        echo '<div class="img-container">';
    }
}

add_action('double_closing_div', 'add_double_container_closing_div' );
//add_action('woocommerce_single_product_summary', 'WC_Structured_Data::generate_product_data()', 7); 

//add short description 
add_action('double_closing_div', function(){
    global $post, $wp_query;
    $postID = $wp_query->post->ID;
    $product = wc_get_product( $postID );
    echo '<section class="bc-single-product__warranty desktop-warranty">' ; 
    echo $product->get_short_description();
    echo '</section>';

},1);
add_action('add_availability_share', function(){
    global $post, $wp_query;
    $postID = $wp_query->post->ID;
    $product = wc_get_product( $postID );
    echo '<section class="bc-single-product__warranty mobile-warranty">' ; 
    echo $product->get_short_description();
    echo '</section>';

},120);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

//wrapping container for gallery and product summary 
$class= 'img-summary-container'; 


    add_action('woocommerce_breadcrumb', function() { global $class ; 
        if(is_single()){
            add_container($class);
        }   
    }, 0);




//single product summary layout
//add description 
add_action('woocommerce_single_product_summary', function(){
    echo '<h3 class="product-description">'.get_the_content().'</h3>';
}, 7);

//add details 
add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 8); 

//remove woocommerce_data tab
//remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');

function tutsplus_remove_product_long_desc( $tabs ) {
    unset( $tabs['description'] ); //remove description 
    $tabs['additional_information']['title'] = __( 'Details' ); //change name to Details
    return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'tutsplus_remove_product_long_desc', 98 );

//remove dimensions in additional information 
add_filter( 'wc_product_enable_dimensions_display', '__return_false' );

//add label infront of quantity
add_action('woocommerce_before_quantity_input_field', function(){
    echo '<h6 class="qty-text poppins-font regular">Quantity:</h6>';
});

//remove meta-data 
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);


//remove tabs
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

//add availablity 
add_action('add_availability_share', function(){

  //  echo '<p class="availability poppins-font regular-text">Availability: <span class="days">7 - 10 Days</span></p>';
    echo '<p class="share-section poppins-font regular-text">Share: '. do_shortcode( "[Sassy_Social_Share]" ).'</p>'; 
}, 100);


//closing div for product container

add_action('woocommerce_after_single_product_summary', 'add_container_closing_div');



//add wallpaper calculator 
add_action('woocommerce_single_product_summary', function(){
    global $post, $product; 
    $category = wp_strip_all_tags($product->get_categories());
    $CategoryWallpaper = "Wallpaper";
    $categoryFabric = "Fabric"; 

    if(strpos($category, $CategoryWallpaper)){
      
        echo '<div class="product-page-btn-container">
        <a class="sizing-calculator-button"><i class="far fa-calculator" aria-hidden="true"></i> Wallpaper Calculator</a>       
     </div>'; 

     //add calculator body 
     add_action('add_calculator_body', 'calculator_body');
    }
    if (strpos($category, $categoryFabric )) { 
  //     echo '<div class="product-page-btn-container">
  //     <a class="sizing-calculator-button"><i class="far fa-calculator" aria-hidden="true"></i> Fabric Calculator</a>       
  //  </div>'; 
      //add calculator body
      add_action('add_calculator_body', 'fabric_calculator_body');

    }
   
}, 40); 

//wallpaper calculator 
 

function calculator_body(){
    global $product; 
    echo '<div class="body-container">
       

    <!--sizing calculator-->
    <div class="overlay-background">
        <div class="calculator-overlay">
        <i class="fal fa-times close"></i>

            <div id="calculator-container">
                <div class="popup-modal wallpaper-calculator-modal is-open">
          
                  <h1>Wallpaper Calculator</h1>
          
          
              <form name="wallpaper_calculator" id="wallpaper-calculator">
                <section>
                  <div>
                    <label for="calc-roll-width">Roll Width<em>*</em> </label>
                    <select name="calc-roll-width" id="calc-roll-width"><option value="37.2">37.2 cm</option><option value="42">42 cm</option><option value="45">45 cm</option><option value="48.5">48.5 cm</option><option value="53">53 cm</option><option value="52">52 cm</option><option value="64">64 cm</option><option value="68">68 cm</option><option value="68.5">68.5 cm</option><option value="70">70 cm</option><option value="90">90 cm</option><option value="95">95 cm</option><option value="100">100 cm</option><option value="140">140 cm</option></select>
                    <label for="calc-roll-height">Roll Length<em>*</em> </label>
                    <select name="calc-roll-height" id="calc-roll-height"><option value="2.65">2.65 cm</option><option value="2.79">2.79 cm</option><option value="3">3 cm</option><option value="5.6">5.6 cm</option><option value="6">6 cm</option><option value="8.5">8.5 cm</option><option value="8.37">8.37 cm</option><option value="9">9 cm</option><option value="10">10 cm</option><option value="10.05">10.05 cm</option><option value="12">12 cm</option><option value="24">24 cm</option></select>
                  </div>
                  <aside>
                    <label for="last-name">Wall width<em>*</em></label>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width1" value="" id="calc-wall-width1" class="form-control" placeholder="Wall 1 width">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width2" value="" id="calc-wall-width2" class="form-control" placeholder="Wall 2 width">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width" value="" id="calc-wall-width3" class="form-control" placeholder="Wall 3 width">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width4" value="" id="calc-wall-width4" class="form-control" placeholder="Wall 4 width">
                          <span class="input-group-addon">m</span>
                      </div>
                  </aside>
                  <aside>
                    <label for="last-name">Wall height<em>*</em></label>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height1" value="" id="calc-wall-height1" class="form-control" placeholder="Wall 1 length">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height2" value="" id="calc-wall-height2" class="form-control" placeholder="Wall 3 length">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height3" value="" id="calc-wall-height3" class="form-control" placeholder="Wall 3 length">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height4" value="" id="calc-wall-height4" class="form-control" placeholder="Wall 4 length">
                          <span class="input-group-addon">m</span>
                      </div>
                  </aside>
                </section>
                <section>
                  <label for="address">Repeat<em>(optional)</em></label>
                  <div class="input-group">
                    <input type="text" name="calc-pattern-repeat" value="" id="calc-pattern-repeat" class="form-control">
                    <span class="input-group-addon">cm</span>
                  </div>
                </section>
                <section class="buttons">
                  <button id="estimate-roll" class="button btn-dk-green ">Calculate</button>
                </section>
                <section class="estimate-result margin-elements">
                      <h3>Result</h3>
                      <p>
                      
                              <span class="calc-round">0</span>&nbsp;
                              <span class="suffix-singular hidden" style="display: none;">roll</span>
                              <span class="suffix-plural">rolls</span>
                   
                      </p>
                </section>
            
                <section class="message margin-elements">
                  <p>Please check your measurements carefully. Inspiry is not responsible for overages or shortages based on this calculator.</p>
                </section>
            
              </form>
          
          
          
          
                </div>
              </div>
        </div>
      </div>
</div>';
}


//fabric calclulator body 
function fabric_calculator_body(){

  echo '<div class="body-container">
     

  <!--sizing calculator-->
  <div class="overlay-background">
      <div class="calculator-overlay">
      <i class="fal fa-times close"></i>

          <div id="calculator-container">
              <div class="popup-modal wallpaper-calculator-modal is-open">
        
                <h1>Fabric Calculator</h1>
        
        
            <form name="wallpaper_calculator" id="wallpaper-calculator">
              <section>
                <div>
                  <label for="calc-roll-width">Roll Width<em>*</em> </label>
                  <select name="calc-roll-width" id="calc-roll-width"><option value="37.2">37.2 cm</option><option value="42">42 cm</option><option value="45">45 cm</option><option value="48.5">48.5 cm</option><option value="53">53 cm</option><option value="52">52 cm</option><option value="64">64 cm</option><option value="68">68 cm</option><option value="68.5">68.5 cm</option><option value="70">70 cm</option><option value="90">90 cm</option><option value="95">95 cm</option><option value="100">100 cm</option><option value="140">140 cm</option></select>
                  <label for="calc-roll-height">Roll Length<em>*</em> </label>
                  <select name="calc-roll-height" id="calc-roll-height"><option value="2.65">2.65 cm</option><option value="2.79">2.79 cm</option><option value="3">3 cm</option><option value="5.6">5.6 cm</option><option value="6">6 cm</option><option value="8.5">8.5 cm</option><option value="8.37">8.37 cm</option><option value="9">9 cm</option><option value="10">10 cm</option><option value="10.05">10.05 cm</option><option value="12">12 cm</option><option value="24">24 cm</option></select>
                </div>
                <aside>
                  <label for="last-name">Wall width<em>*</em></label>
                  <div class="input-group">
                    <input type="text" name="calc-wall-width1" value="" id="calc-wall-width1" class="form-control" placeholder="Wall 1 width">
                        <span class="input-group-addon">m</span>
                  </div>
                  <div class="input-group">
                    <input type="text" name="calc-wall-width2" value="" id="calc-wall-width2" class="form-control" placeholder="Wall 2 width">
                        <span class="input-group-addon">m</span>
                  </div>
                  <div class="input-group">
                    <input type="text" name="calc-wall-width" value="" id="calc-wall-width3" class="form-control" placeholder="Wall 3 width">
                        <span class="input-group-addon">m</span>
                  </div>
                  <div class="input-group">
                    <input type="text" name="calc-wall-width4" value="" id="calc-wall-width4" class="form-control" placeholder="Wall 4 width">
                        <span class="input-group-addon">m</span>
                    </div>
                </aside>
                <aside>
                  <label for="last-name">Wall height<em>*</em></label>
                  <div class="input-group">
                    <input type="text" name="calc-wall-height1" value="" id="calc-wall-height1" class="form-control" placeholder="Wall 1 length">
                        <span class="input-group-addon">m</span>
                  </div>
                  <div class="input-group">
                    <input type="text" name="calc-wall-height2" value="" id="calc-wall-height2" class="form-control" placeholder="Wall 3 length">
                        <span class="input-group-addon">m</span>
                  </div>
                  <div class="input-group">
                    <input type="text" name="calc-wall-height3" value="" id="calc-wall-height3" class="form-control" placeholder="Wall 3 length">
                        <span class="input-group-addon">m</span>
                  </div>
                  <div class="input-group">
                    <input type="text" name="calc-wall-height4" value="" id="calc-wall-height4" class="form-control" placeholder="Wall 4 length">
                        <span class="input-group-addon">m</span>
                    </div>
                </aside>
              </section>
              <section>
                <label for="address">Repeat<em>(optional)</em></label>
                <div class="input-group">
                  <input type="text" name="calc-pattern-repeat" value="" id="calc-pattern-repeat" class="form-control">
                  <span class="input-group-addon">cm</span>
                </div>
              </section>
              <section class="buttons">
                <button id="estimate-roll" class="button btn-dk-green ">Calculate</button>
              </section>
              <section class="estimate-result margin-elements">
                    <h3>Result</h3>
                    <p>
                    
                            <span class="calc-round">0</span>&nbsp;
                            <span class="suffix-singular hidden" style="display: none;">roll</span>
                            <span class="suffix-plural">rolls</span>
                 
                    </p>
              </section>
          
              <section class="message margin-elements">
                <p>Please check your measurements carefully. Inspiry is not responsible for overages or shortages based on this calculator.</p>
              </section>
          
            </form>
        
        
        
        
              </div>
            </div>
      </div>
    </div>
</div>';
}

//checkout 

add_action('woocommerce_checkout_before_order_review_heading', function(){
    echo '<div class="order-review-container">';
});
add_action('woocommerce_review_order_after_payment', 'add_container_closing_div');





//add sample functonality 
add_action( 'woocommerce_single_product_summary', 'bbloomer_add_free_sample_add_cart', 35 );
  
function bbloomer_add_free_sample_add_cart() {
    global $post;
    $terms = wp_get_post_terms( $post->ID, 'product_cat' );
    foreach ( $terms as $term ) $categories[] = $term->slug;

    if ( in_array( 'fabric', $categories ) || in_array( 'wallpaper', $categories )) {
   ?>
      <form class="cart" method="post" enctype='multipart/form-data'>
      <?php 
        if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
      ?>
      <button type="submit" name="add-to-cart" value="14862" class="button btn-dk-green-border btn-full-width margin-top">ORDER FREE SAMPLE</button>
      <?php
        }

        else{
            ?>
              <button type="submit" name="add-to-cart" value="999471569" class="button btn-dk-green-border btn-full-width margin-top">ORDER FREE SAMPLE</button>

            <?php
        }
      ?>
      <input type="hidden" name="free_sample" value="<?php the_ID(); ?>">
      </form>
   <?php
    }
}
// -------------------------
// 2. Add the custom field to $cart_item
  
add_filter( 'woocommerce_add_cart_item_data', 'bbloomer_store_free_sample_id', 9999, 2 );
  
function bbloomer_store_free_sample_id( $cart_item, $product_id ) {
   if ( isset( $_POST['free_sample'] ) ) {
         $cart_item['free_sample'] = $_POST['free_sample'];
   }
   return $cart_item; 
}
  
// -------------------------
// 3. Concatenate "Free Sample" with product name (CART & CHECKOUT)
// Note: rename "Free Sample" to your free sample product name
  
add_filter( 'woocommerce_cart_item_name', 'bbloomer_alter_cart_item_name', 9999, 3 );
  
function bbloomer_alter_cart_item_name( $product_name, $cart_item, $cart_item_key ) {
   if ( $product_name == "Free Sample" ) {
      $product = wc_get_product( $cart_item["free_sample"] );
      $product_name .=  " (" . $product->get_name() . ")";
   }
   return $product_name;
}



// define the woocommerce_cart_item_thumbnail callback 
function filter_woocommerce_cart_item_thumbnail( $product_get_image, $cart_item, $cart_item_key ) { 
  // make filter magic happen here... 
  if($cart_item["free_sample"]){
    $product = wc_get_product( $cart_item["free_sample"] );
    $product_get_image = '<img';
    $product_get_image .= ' src="' .  wp_get_attachment_image_url( $product->image_id, 'woocommerce_thumbnail' ). '"';
    $product_get_image .= ' class="' . $class . '"';
    $product_get_image .= ' />';
    return $product_get_image; 
  }
  else{
    return $product_get_image; 
  }
 
}; 
       
// filter for sample images on a cart page
add_filter( 'woocommerce_cart_item_thumbnail', 'filter_woocommerce_cart_item_thumbnail', 10, 3 ); 
  



// -------------------------
// 4. Add "Free Sample" product name to order meta
// Note: this will show on thank you page, emails and orders
  
add_action( 'woocommerce_add_order_item_meta', 'bbloomer_save_posted_field_into_order', 9999, 2 );
  
function bbloomer_save_posted_field_into_order( $itemID, $values ) {
    if ( ! empty( $values['free_sample'] ) ) {
      $product = wc_get_product( $values['free_sample'] );
      $product_name = $product->get_name();
      wc_add_order_item_meta( $itemID, 'Free sample for', $product_name );
      wc_add_order_item_meta( $itemID, 'SKU', $product->get_sku() );
    }
}


//attributes links - show brand attributes in menu 
add_filter('woocommerce_attribute_show_in_nav_menus', 'wc_reg_for_menus', 1, 2);

function wc_reg_for_menus( $register, $name = '' ) {
     if ( $name == 'pa_brands' ) $register = true;
     return $register;
}

//loop title 
function start_title_wrapper(){
  echo '<div class="loop-title-container"> '; 
}
add_action('woocommerce_shop_loop_item_title', 'start_title_wrapper', 1, 1); 

function close_title_wrapper(){
  echo '</div>';
}
add_action('woocommerce_shop_loop_item_title', 'close_title_wrapper', 20, 1); 

// product price formating 

add_filter( 'woocommerce_price_trim_zeros', '__return_true' );

// show percentage discount on product loop page
add_action( 'woocommerce_after_shop_loop_item', 'webduel_show_sale_percentage_loop', 1 );
add_action("woocommerce_single_product_summary", 'webduel_show_sale_percentage_loop', 10);
function webduel_show_sale_percentage_loop() {
   global $product;
   if ( ! $product->is_on_sale() ) return;
   if ( $product->is_type( 'simple' ) ) {
      $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
   } elseif ( $product->is_type( 'variable' ) ) {
      $max_percentage = 0;
      foreach ( $product->get_children() as $child_id ) {
         $variation = wc_get_product( $child_id );
         $price = $variation->get_regular_price();
         $sale = $variation->get_sale_price();
         if ( $price != 0 && ! empty( $sale ) ) $percentage = ( $price - $sale ) / $price * 100;
         if ( $percentage > $max_percentage ) {
            $max_percentage = $percentage;
         }
      }
   }
   if ( $max_percentage > 0 ) echo "<div class='sale-perc'>(" . round($max_percentage) . "% OFF)</div>"; 
}


// change image on hove on product archive page

add_action( 'woocommerce_before_shop_loop_item_title', 'add_on_hover_shop_loop_image' ) ; 

function add_on_hover_shop_loop_image() {

    $image_id = wc_get_product()->get_gallery_image_ids()[0] ; 

    if ( $image_id ) {
        echo '<img src="' . wp_get_attachment_image_src( $image_id, 'woocommerce_thumbnail')[0] . '" class="current">';
     
    } else {  //assuming not all products have galleries set

        echo wp_get_attachment_image( wc_get_product()->get_image_id(),  'woocommerce_thumbnail'  ) ; 

    }

}

// search order with product sku 
add_filter( 'woocommerce_shop_order_search_fields', 'my_shop_order_search_fields' );

 function my_shop_order_search_fields( $search_fields ) {
    $orders = get_posts( array(
        'post_type' => 'shop_order',
        'post_status' => wc_get_order_statuses(), //get all available order statuses in an array
        'posts_per_page' => 999999, // query all orders
        'meta_query' => array(
            array(
                'key' => '_product_sku',
                'compare' => 'NOT EXISTS'
            )
        ) // only query orders without '_product_sku' postmeta
    ) );

    foreach ($orders as $order) {
        $order_id = $order->ID;
        $wc_order = new WC_Order($order_id);
        $items = $wc_order->get_items();
        foreach($items as $item) {
            $product_id = $item['product_id'];
            $search_sku = get_post_meta($product_id, '_sku', true);
            add_post_meta( $order_id, '_product_sku', $search_sku );
        }
    }

    return array_merge($search_fields, array('_product_sku')); // make '_product_sku' one of the meta keys we are going to search for.
}

// show all products on archive page

add_action( 'woocommerce_product_query', 'all_products_query' );

function all_products_query( $q ){
    $q->set( 'posts_per_page', 30 );
}


// hide coupon field on checkout page
function hide_coupon_field_on_checkout( $enabled ) {

	if ( is_checkout() ) {
		$enabled = false;
	}

	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );

// remove add to cart button on loop product page
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
    function remove_add_to_cart_buttons() {
      if( is_product_category() || is_shop() || is_archive() ) { 
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
      }
    }

// add product attributes on product loop product page
add_action("woocommerce_after_shop_loop_item_title", "add_product_attributes", 1); 

function add_product_attributes(){ 
  echo do_shortcode(('[add_deal_text]'));
  // add free shipping 
   echo do_shortcode('[add_free_shipping_tag]'); 
}

// remove sale badge
add_filter( 'woocommerce_sale_flash', '__return_null' );

// add or between social login section 
add_action('woocommerce_login_form_end', "wd_or_social_login", 5); 
add_action('woocommerce_register_form_end', "wd_or_social_login", 5); 
  function wd_or_social_login(){ 
    echo '<div class="medium-font-size poppins-font margin-ten center-align or-element">Or</div>'; 
    }

//yoast seo- add description if it doesn't exist 

add_filter( 'wpseo_metadesc', 'change_yoast_desc', 10, 2);

function change_yoast_desc ( $desc , $presentation ){
  global $product;
if(!$desc && $product && is_product() ){
  $desc = wp_trim_words($product->get_description(), 160);
}
  
	return $desc;
}
