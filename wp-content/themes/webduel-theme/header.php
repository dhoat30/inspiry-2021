<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
            <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PS7XFHN');</script>
        <!-- End Google Tag Manager -->

    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="profile" href="https://gmpg.org/xfn/11"/>
    <?php wp_head(); ?>
    
    <!-- windcave --> 
    <script src="https://dev.windcave.com/js/windcavepayments-seamless-v1.js"></script>
        <!-- splide -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

        <!-- bing tag -->
        <meta name="msvalidate.01" content="8BB2BD3056EE954D25649333FBFC2D75" />

        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '828264374302518');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=828264374302518&ev=PageView&noscript=1"
        /></noscript>
<!-- End Facebook Pixel Code -->
    <?php 
    // get user email address 
    $pinterestUserEmail; 
    if(is_user_logged_in()){ 
        $current_user = wp_get_current_user(); 
        $pinterestUserEmail = $current_user->user_email; 
        $hashedPinterestEmail = wp_hash($pinterestUserEmail); 
    }
   
    ?>
<!-- pinterest Tag -->
        <!-- Pinterest Tag -->
        <script>
        !function(e){if(!window.pintrk){window.pintrk = function () {
        window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
        n=window.pintrk;n.queue=[],n.version="3.0";var
        t=document.createElement("script");t.async=!0,t.src=e;var
        r=document.getElementsByTagName("script")[0];
        r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
        pintrk('load', '2612506427665', {em: '<?php echo $pinterestUserEmail; ?>'});
        pintrk('page');
        </script>
        <noscript>
        <img height="1" width="1" style="display:none;" alt=""
        src='https://ct.pinterest.com/v3/?event=init&tid=2612506427665&pd[em]=<<?php echo $hashedPinterestEmail?>>&noscript=1'/>
        </noscript>
        <!-- end Pinterest Tag -->
        
</head>
<?php 
    global $template;
    //check the template 
    if(is_post_type_archive()) {
        $archive = 'product-archive'; 
    }

?>
<body id="header"<?php body_class( );?> data-archive='<?php echo $archive ?>'>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PS7XFHN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  
    <section class="header" >
        <div class="top-banner">
            <div class="track-order">
                <a href="<?php echo get_home_url().'/home/track-order' ?>" target="_blank"  class="text-decoration-none dark-grey">
                <i class="fas fa-shipping-timed"></i>
                        <span>Track Your Order</span>
                </a> 
            </div>
            <div class="free-shipping">
                <a href="<?php echo get_home_url().'/products' ?>"   class="text-decoration-none dark-grey">
                <i class="fas fa-shipping-fast"></i>
                        <span>Free Shipping</span>
                </a> 
              
            </div>
              
           <!-- wishlist -->
            <div class="wishlist">
               <a href="<?php echo get_home_url().'/wishlist' ?>"   class="text-decoration-none dark-grey">
                    <i class="fas fa-bookmark"></i>
                    <span>Saved Items</span>
               </a> 
              
            </div>
              
            <!-- login area -->
            <div class="login-area playfair-fonts paragraph-font-size profile-trigger ">
               
                    <?php 
                        if(is_user_logged_in()){
                            global $current_user; wp_get_current_user();  
                            ?> <a href="" class="profile-name-value text-decoration-none dark-grey">
                                <i class="fas fa-user"></i> 
                                <span>  
                                     <?php echo  $current_user->display_name;?>
                                    <i class="fas fa-chevron-down regular arrow-icon"></i>
                                </span>
                               
                                <nav>
                                <?php
                                    wp_nav_menu( array( 
                                        'theme_location' => 'my-account-nav-top', 
                                        'container_class' => "my-account-nav"
                                    )); 
                                ?>
                                </nav>  
                                </a>       
                            <?php
                        }
                        else{
                            $currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                            ?><a id="show_login" href="<?php echo get_site_url(); ?>/sign-in?redirect-link=<?php echo $currentLink?>" class="text-decoration-none dark-grey regular" data-root-url='<?php echo get_home_url()?>/account-profile'>
                                <i class="fas fa-user"></i>
                                <span>Login/Register</span> 
                        </a>
                            <?php
                        }
                    ?>
            </div>
            
            <!-- shopping cart -->
            <div class="shopping-cart playfair-fonts paragraph-font-size desktop-visible">
                <a href="#" class="text-decoration-none dark-grey regular cart-items-header">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-item-count">Cart (<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
                </a>
            </div>
            
            <div class="search-code playfair-fonts paragraph-font-size dark-grey">
              <div class="search-bar">
                  <input autocomplete="off" type="text" class="search-input" placeholder="Search..." id="search-term" />
                  <i class="fad fa-spinner fa-spin" aria-hidden="true"></i>
                  <i class="far fa-search" aria-hidden="true"></i>
              </div>
              <div class="result-div"></div>
            </div>
        </div>

        <div class="banner-container dark-grey-bc">
            <div class="banner-card owl-carousel">
                 <?php 

                $argsBanner = array(
                    'post_type' => 'banners',
                    'posts_per_page'=> -1,
                );
                $banner = new WP_Query( $argsBanner );

                while($banner->have_posts()){ 
                    $banner->the_post(); 
                    if(get_field('banner_link')){
                        ?>
                                        <a href="<?php echo get_field('banner_link');?>" class="white poppins-font center-align paragraph-font-size thin"> <?php echo get_the_title(); ?> LEARN <i class="fal fa-chevron-right white"></i></a>

                        <?php
                    }
                    else{
                        ?>
                        <a href="<?php echo get_field('banner_link');?>" class="white poppins-font center-align paragraph-font-size thin"> <?php echo get_the_title(); ?></a>
                        <?php
                    }
                } 
                wp_reset_postdata();
                ?>
            </div>
        </div>

        <div class="main-navbar-container">

            <!--logo -->
            <div class="logo-container">
                <?php 
                  $logoImage = 0; 
                   if(get_site_url() === "http://localhost/inspirynew"){
                    $logoImage = get_field("logo", 452530);
                   }
                   else{ 
                    $logoImage = get_field("logo", 8772);
                   }
                   
                ?>
                <a href="<?php echo get_site_url(); ?>">
                    <img class="logo" src='<?php echo $logoImage["url"]; ?>' alt="Inspiry Logo">
                </a>
            </div>
             <!-- shopping cart -->
             <div class="shopping-cart playfair-fonts paragraph-font-size desktop-visible">
                <a href="#" class="text-decoration-none dark-grey regular cart-items-header">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
            <!-- wishlist -->
            <div class="wishlist">
               <a href="<?php echo get_home_url().'/wishlist' ?>"   class="text-decoration-none dark-grey">
                    <i class="fas fa-bookmark"></i>
               </a> 
            </div>
            <!--top navbar --> 
            <nav class="navbar top-navbar">
                <?php
                wp_nav_menu(
                        array(
                            'theme_location' => 'top-navbar', 
                            'container_id' => 'top-navbar'
                        ));
                ?>     
            </nav>
             
            <!--Shop  navbar--> 
            <nav class="navbar">
                <?php
                wp_nav_menu(
                        array(
                            'theme_location' => 'inspiry_main_menu', 
                            'container_id' => 'cssmenu'
                        ));
                ?>
            </nav>
        </div>
                 
    
        <!-- login form  -->
    

    </section>






    <!-- gtag manager data -->
<script type="text/javascript">
    jQuery('.checkout-btn-header').on("click", function(event) {
        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 1},
                    'products': [
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
                            {
                                'name': '<?php echo $product -> get_name()?>',                  
                                'id': '<?php echo $product -> get_id()?>',
                                'price': '<?php echo $product -> get_price()?>',
                                'brand': '<?php echo  $product->get_attribute('pa_brands')?>	',
                                            'category': '<?php $terms = get_the_terms( $product_id, 'product_cat' );
                                            foreach ($terms as $term) {
                                                $product_cat_id = $term->term_id;
                                                
                                                echo get_the_category_by_ID($product_cat_id).",";
                                            
                                                break;
                                            } ?>',
                                'variant': 'none',
                                'quantity': '<?php echo $quantity; ?>'  
                            },


                            <?php
                        }    
                           
                            ?>
                    ]
                }
            }
        })
    });
       
</script>	