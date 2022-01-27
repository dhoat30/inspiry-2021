<?php 
/**
 * Inspiry functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package inspiry

 */

require get_theme_file_path('/inc/woocommerce.php');

require get_theme_file_path('/inc/buddypress-design-boards.php');

require get_theme_file_path('/inc/boards-route.php');
require get_theme_file_path('/inc/custom-post-type.php');

require get_theme_file_path('/inc/nav-registeration.php');

require get_theme_file_path('/inc/woocommerce-route.php');

require get_theme_file_path('/inc/enquiry-modal.php');
require get_theme_file_path('/inc/woocommerce-product.php');

require get_theme_file_path('/inc/cart-modal.php');

require get_theme_file_path('/inc/windcave-payment.php');
require get_theme_file_path('/inc/windcave-session.php');

require get_theme_file_path('/inc/rest-acf.php');
require get_theme_file_path('/inc/users.php');
require get_theme_file_path('/inc/order-routes.php');
require get_theme_file_path('/inc/api-routes/email-routes.php');
require get_theme_file_path('/inc/api-routes/trade-routes.php');
require get_theme_file_path('/inc/api-routes/pins-routes.php');
require get_theme_file_path('/inc/ajax-login.php');
require get_theme_file_path('/inc/api-routes/search-routes.php');
require get_theme_file_path('/inc/api-routes/social-login-routes.php');
require get_theme_file_path('/inc/api-routes/windcave-routes.php');

require get_theme_file_path('/inc/webduel-short-code.php');

require get_theme_file_path('/inc/woocommerce/woocommerce-cart.php');
require get_theme_file_path('/inc/woocommerce/single-product.php');
require get_theme_file_path('/inc/api-routes/cart-routes.php');
require get_theme_file_path('/inc/api-routes/mailchimp-route.php');

 //enqueue scripts
 function inspiry_scripts(){ 
   wp_enqueue_script("jQuery");
   wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/f3cb7ab01f.js', NULL, '1.0', false);
   wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/f3cb7ab01f.js', NULL, '1.0', false);
  
   if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
      wp_enqueue_script('main', 'http://localhost:3000/bundled.js',  array( 'jquery' ), '1.0', true);
    } else {
      wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.fa583623385eedc86539.js'),  array( 'jquery' ), '1.0', true);
      wp_enqueue_script('main', get_theme_file_uri('/bundled-assets/scripts.f9db7d9c3658c3c340b1.js'), NULL, '1.0', true);
      wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.f9db7d9c3658c3c340b1.css'));      
      wp_enqueue_style('our-vendor-styles', get_theme_file_uri('/bundled-assets/styles.f9db7d9c3658c3c340b1.css'));
    }
    
    wp_localize_script("main", "inspiryData", array(
      "root_url" => get_site_url(),
      "nonce" => wp_create_nonce("wp_rest")
    ));
}
add_action( "wp_enqueue_scripts", "inspiry_scripts" ); 

//sidebar
add_action( "widgets_init", "mat_widget_areas" );
function mat_widget_areas() {
    register_sidebar( array(
        "name" => "Theme Sidebar",
        "id" => "mat-sidebar",
        "description" => "The main sidebar shown on the right in our awesome theme",
        "before_widget" => '<li id="%1$s" class="widget %2$s">',
		"after_widget"  => "</li>",
		"before_title"  => '<h3 class="widget-title">',
		"after_title"   => "</h3>",
    ));
}

//custom post register
add_theme_support("post-thumbnails");
function register_custom_type(){ 
   register_post_type("boards", array(
     'show_in_rest' => true, 
      'has_archive' => true,
      "supports" => array("title", "page-attributes", 'editor'), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Boards", 
         "add_new_item" => "Add New Board", 
         "edit_item" => "Edit Board", 
         "all_items" => "All Boards", 
         "singular_name" => "Board"
      ), 
      "menu_icon" => "dashicons-heart"
      
   )
   ); 
}

add_action("init", "register_custom_type"); 

 //make private page parent/child
 add_filter("page_attributes_dropdown_pages_args", "my_attributes_dropdown_pages_args", 1, 1);

function my_attributes_dropdown_pages_args($dropdown_args) {

    $dropdown_args["post_status"] = array("publish","draft", "private");

    return $dropdown_args;
}


// remove "Private: " from titles
function remove_private_prefix($title) {
	$title = str_replace("Private: ", "", $title);
	return $title;
}
add_filter("the_title", "remove_private_prefix");

//facet wp
function fwp_archive_per_page( $query ) {
   if ( is_tax( 'category' ) ) {
       $query->set( 'posts_per_page', 20 );
   }
}
add_filter( 'pre_get_posts', 'fwp_archive_per_page' );


function fwp_home_custom_query( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'post_type', [ 'post', 'product' ] );
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );
    }
}
add_filter( 'pre_get_posts', 'fwp_home_custom_query' );

//upload images 

function handle_my_file_upload() {
 

  // will return the attachment id of the image in the media library
  $attachment_id = media_handle_upload('my_file_field', 0);

  // test if upload succeeded
  if (is_wp_error($attachment_id)) {
      http_response_code(400);
      echo 'Failed to upload file.';
      return 'failed to upload file';
  }
  else {
      http_response_code(200);
      echo $attachment_id;
      return 'saved a file';
  }

  // done!
  die();
}

// allow uploads from users that are logged in
add_action('wp_ajax_my_file_upload', 'handle_my_file_upload');

// allow uploads from guests
//add_action('wp_ajax_nopriv_my_file_upload', 'handle_my_file_upload');



//preload css 
function add_rel_preload($html, $handle, $href, $media) {
    
  if (is_admin())
      return $html;

   $html = <<<EOT
<link rel='preload' as='style' onload="this.onload=null;this.rel='stylesheet'" id='$handle' href='$href' type='text/css' media='all' />
EOT;
  return $html;
}
add_filter( 'style_loader_tag', 'add_rel_preload', 10, 4 );

//redirect after login 
/**
* Redirect users to custom URL based on their role after login
*
* @param string $redirect
* @param object $user
* @return string
*/
function wc_custom_user_redirect( $redirect, $user ) {
  // Get the first of all the roles assigned to the user
  $role = $user->roles[0];
  $dashboard = admin_url();
  $myaccount = get_permalink( wc_get_page_id( 'shop' ) );
  if( $role == 'administrator' ) {
    //Redirect administrators to the dashboard
    $redirect = '/';
  }  elseif ( $role == 'customer' || $role == 'subscriber' ) {
    //Redirect customers and subscribers to the "My Account" page
    $redirect = '/';
  } else {
    //Redirect any other role to the previous visited page or, if not available, to the home
    $redirect = wp_get_referer() ? wp_get_referer() : home_url();
  }
  return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );

// ajax add to cart 
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {
                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
    }

        //add to cart ajax
       /**
 * Show cart contents / total Ajax
 */

add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
  global $woocommerce;
  ob_start();
  ?>
    <div class="cart-box">
                <div class="flex-card">
                        <?php

                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $product = $cart_item['data'];
                            $product_id = $cart_item['product_id'];
                            $quantity = $cart_item['quantity'];
                            $price = WC()->cart->get_product_price( $product );
                            $subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );
                            $link = $product->get_permalink( $cart_item );
                            // Anything related to $product, check $product tutorial
                            $meta = wc_get_formatted_cart_item_data( $cart_item );
                           
                            ?>
                    <!-- front end cart items cards -->
                    <div class="product-card">
                        <?php 

                                // condition to check if the product is simple
                        if($product->name == "Free Sample"){
                                    // pulling information of an original product in a form of an objec†
                        $originalProduct = wc_get_product( $cart_item["free_sample"] );
                        
                        ?>
                        <a href="<?php echo get_the_permalink($originalProduct->get_id()); ?>" class="rm-txt-dec">
                            
                            <div class="img-container">
                                <img src="<?php echo wp_get_attachment_image_url( $originalProduct->image_id, 'woocommerce_thumbnail' );?>" alt="<?php echo $originalProduct->get_name()?>">
                            </div>
                            <div class="title-container">
                                    <h5 class="paragraph-font-size regular"> <?php echo $quantity;?> X  Free Sample (<?php echo $originalProduct->get_name(); 
                                    ?> )
                                    </h5>
                            </div>
                            
                            <div class="price-container">
                            <h6 class="paragraph-font-size poppins-font bold">$<?php echo number_format($product->price * $quantity) ?></h6>
                            </div>
                            <i class="fal fa-times remove-cart-item-btn" data-productID="<?php echo $product_id;?>"></i>
                        </a>

                        <?php
                        }
                        else{
                            ?>
                            <a href="<?php echo $link?>" class="rm-txt-dec">
                                
                                <div class="img-container">
                                    <img src="<?php echo get_the_post_thumbnail_url($product_id, 'medium');?>" alt="<?php echo $product->name?>">
                                </div>
                                <div class="title-container">
                                        <h5 class="paragraph-font-size regular"> <?php echo $quantity;?> X  <?php echo $product->name
                                        ?> 
                                        </h5>
                                </div>
                                
                                <div class="price-container">
                                <h6 class="paragraph-font-size poppins-font bold">$<?php echo number_format($product->price * $quantity); ?></h6>
                                </div>
                                
                                <i class="fal fa-times remove-cart-item-btn" data-productID="<?php echo $product_id;?>"></i>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                
                    <?php
                    
                    }
                    
                    ?>
			    </div>
                <div class="pop-up-footer">
                    <div class="total-container">
                        
                        <div class="total poppins-font">
                            Total: $<?php 
                            $totalAmount = str_replace(".00", "", (string)number_format (WC()->cart->total, 2, ".", ""));
                            echo number_format($totalAmount); ?>
                        </div>
                    </div>
                    <div class="cont-shopping">
                            <a class="rm-txt-dec button btn-dk-green-border btn-full-width center-align" href="#">Continue Shopping</a>
                        </div>
                    <div class="checkout-btn">
                        <a class="rm-txt-dec button btn-dk-green btn-full-width center-align checkout-btn-header" href="<?php echo get_site_url();?>/cart">Checkout</a>
                    </div>
                </div>
            </div>
 <?php
  $fragments['.cart-box'] = ob_get_clean();
  return $fragments;
}

// add to cart ajax slider down
add_action( 'wp_footer', 'trigger_for_ajax_add_to_cart' );
function trigger_for_ajax_add_to_cart() {
    ?>
        <script type="text/javascript">
     
            (function($){
                $('body').on( 'added_to_cart', function(){
                
                    // Your code goes here
                    $('.cart-popup-container').slideDown();
                    $('.header .shopping-cart a i').toggleClass('fa-chevron-up');
                    // setTimeout(function(){  $('.cart-popup-container').slideUp('slow');}, 3000);

                   // $('.cart-popup-container .fa-times').on('click', closeCart)
                
                      
                });
            })(jQuery);

          
        </script>
    <?php
}

// show cart item number in the header on add to cart
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_item_fragment');

function woocommerce_header_add_to_cart_item_fragment( $fragments ) {
    global $woocommerce;

    ob_start();

    ?>
    <span class="cart-item-count">Cart (<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
    <?php

    $fragments['span.cart-item-count'] = ob_get_clean();

    return $fragments;

}

// remove item from cart pop up 
function remove_item_from_cart() {
    $cart = WC()->instance()->cart;
    $id = $_POST['product_id'];
    $cart_id = $cart->generate_cart_id($id);
    $cart_item_id = $cart->find_product_in_cart($cart_id);

    if($cart_item_id){
       $cart->set_quantity($cart_item_id, 0);
       return true;
    } 
    return false;
    }

    add_action('wp_ajax_remove_item_from_cart', 'remove_item_from_cart');
    add_action('wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart');

// redirect to home page after log out 
add_action('wp_logout','ps_redirect_after_logout');
function ps_redirect_after_logout(){
         wp_redirect( 'https://inspiry.co.nz' );
         exit();
}

    // disable admin bar for regular users 
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
       return $data;
    }
  
    $filetype = wp_check_filetype( $filename, $mimes );
  
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
  
  }, 10, 4 );
  
  function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );
  
  function fix_svg() {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }
  add_action( 'admin_head', 'fix_svg' );

