<?php 
/**
 * Inspiry functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package inspiry

 */

//  routes
require get_theme_file_path('/inc/api-routes/auth-route.php');
require get_theme_file_path('/inc/api-routes/boards-route.php');
require get_theme_file_path('/inc/api-routes/cart-routes.php');
require get_theme_file_path('/inc/api-routes/email-routes.php');
require get_theme_file_path('/inc/api-routes/mailchimp-route.php');
require get_theme_file_path('/inc/api-routes/pins-routes.php');
require get_theme_file_path('/inc/api-routes/search-routes.php');
require get_theme_file_path('/inc/api-routes/social-login-routes.php');
require get_theme_file_path('/inc/api-routes/trade-routes.php');
require get_theme_file_path('/inc/api-routes/windcave-routes.php');
require get_theme_file_path('/inc/api-routes/woocommerce-route.php');
require get_theme_file_path('/inc/api-routes/order-routes.php');

// auth
require get_theme_file_path('/inc/auth/ajax-login.php');
require get_theme_file_path('/inc/auth/create-user.php');

// customer service
require get_theme_file_path('/inc/customer-service/contact-form.php'); 
require get_theme_file_path('/inc/customer-service/feedback-form.php');


// general
require get_theme_file_path('/inc/cart-modal.php');
require get_theme_file_path('/inc/custom-post-type.php');
require get_theme_file_path('/inc/enquiry-modal.php');
require get_theme_file_path('/inc/nav-registeration.php');
require get_theme_file_path('/inc/rest-acf.php');
require get_theme_file_path('/inc/users.php');
require get_theme_file_path('/inc/webduel-short-code.php');
// windcave 
require get_theme_file_path('/inc/windcave-payment.php');
require get_theme_file_path('/inc/windcave-session.php');

// woocommerce 
require get_theme_file_path('/inc/woocommerce/image-size.php');
require get_theme_file_path('/inc/woocommerce/single-product.php');



 //enqueue scripts
 function inspiry_scripts(){ 

  
   wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
   wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
   wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
   wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

   wp_enqueue_script("jQuery");
   wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/f3cb7ab01f.js', NULL, '1.0', false);
   wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/f3cb7ab01f.js', NULL, '1.0', false);

   
    
    // wp_localize_script("main", "inspiryData", array(
    //   "root_url" => get_site_url(),
    //   "nonce" => wp_create_nonce("wp_rest")
    // ));
}
add_action( "wp_enqueue_scripts", "inspiry_scripts" ); 
