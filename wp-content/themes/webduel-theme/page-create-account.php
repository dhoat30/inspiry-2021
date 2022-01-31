<?php get_header(); ?> 

<div class="create-account-page">
    <div class="background-image" style='background: url("<?php echo get_the_post_thumbnail_url(null,"large"); ?>")'>
        <div class="content">
            <div class="create-account-container">
                <h1 class="title" >Create an Account</h1>
                <form id="create-account" action="create-account" method="post">
                        <p class="status"></p>

                        <div class="flex name-container">
                            <div class="input-item">
                                <label for="first-name">First Name*</label>
                                <input id="first-name" type="text" name="first-name" required>
                            </div>
                            <div class="input-item">
                                <label for="last-name">Last Name*</label>
                                <input id="last-name" type="text" name="last-name" required>
                            </div>
                        </div>

                        <label for="email">Email Address*</label>
                        <input id="email" type="text" name="email" required>
                        <label for="username">Username*</label>
                        <input id="username" type="text" name="username" required>
                        <label for="password">Password*</label>
                        <input id="password" type="password" name="password" required>
                        <div class="checkbox-container" >
                            <input type="checkbox" id="newsletter" name="newsletter" >
                            <label for="newsletter"> Receive the fortnightly newsletter from Inspiry.</label>
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

<script type="text/javascript">
    let $ = jQuery
    $('.create-account-page #create-account').on('submit',function(e){
        e.preventDefault();
        $('.create-account-page p.status').show().text("Sending request, please wait...");
        let formData = {
            action: "register_user_front_end",
            username: $('#create-account #username').val(),
            email: $('#create-account #email').val(),
            password: $('#create-account #password').val(),
            firstName: $('#create-account #first-name').val(),
            lastName: $('#create-account #last-name').val(),
            subscribeNewsletter: $('#create-account #newsletter').is(":checked")
        }
      
        jQuery.ajax({
          type:"POST",
          url:"<?php echo admin_url('admin-ajax.php'); ?>",
          data: formData,
          success: function(results){
            console.log(results);
            $('.create-account-page p.status').show().text(results);
          },
          error: function(results) {
          }
        });
      });
</script>
<?php get_footer(); ?> 