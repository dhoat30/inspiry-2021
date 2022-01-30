<?php get_header(); ?> 

<div class="create-account-page">
    <div class="background-image" style='background: url("<?php echo get_the_post_thumbnail_url(null,"large"); ?>")'>
        <div class="content">
            <div class="create-account-container">
                <h1 class="title" >Sign In</h1>
                <form id="create-account" action="create-account" method="post">
                        <p class="status"></p>
                        <div class="flex name-container">
                            <div class="input-item">
                                <label for="first-name">First Name*</label>
                                <input id="first-name" type="text" name="first-name">
                            </div>
                            <div class="input-item">
                                <label for="last-name">Last Name*</label>
                                <input id="last-name" type="text" name="last-name">
                            </div>
                            
                        </div>
                      
                        <label for="username">Username*</label>
                        <input id="username" type="text" name="username">
                        <label for="password">Password*</label>
                        <input id="password" type="password" name="password">
                        <div class="checkbox-container" >
                            <input type="checkbox" id="newsletter" name="newsletter" value="yes">
                            <label for="vehicle1"> Receive the fortnightly newsletter from Inspiry.</label>
                        </div>
                        <div class="flex">
                            <input class="primary-button" type="submit" value="CREATE ACCOUNT" name="submit">
                            <div class="divider">Or</div>
                            <?php echo do_shortcode('[google-login]');
                            if(!is_front_page()){ 
                                echo do_shortcode('[facebook-login]');
                            }
                            ?>
                        </div>        
                        <div class="terms-flex">
                            <div class="terms"><?php echo get_the_content();?></div>
                        </div>    
                       
                        <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?> 