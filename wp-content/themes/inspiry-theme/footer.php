<section class="subscribe-section">
  <div class="content">
    <div class="section-font-size">
      Sign up For Inspiry Emails.
    </div>
    <div class="poppins-font paragraph-font-size">
      Plus hear about the latest and greatest from our family of brands!
    </div>
  </div>

  <div class="form">
    <?php echo do_shortcode('[mc4wp_form id="88533"]');?>
  </div>
</section>
<footer class="off-white-bc footer">

  <div class="footer-menu-row row-container light-grey">
    <div class="trade-nav">
      <h6 class="footer-menu-title">
        Trade
      </h6>
      <?php
                  wp_nav_menu( array( 
                      'theme_location' => 'footer-trade-menu'
                    )); 
            ?>
    </div>

    <div class="help-info-nav">
      <h6 class="footer-menu-title">
        Help & Info
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-help-info'
              ) )
            ?>

    </div>

    <div class="Store">
      <h6 class="footer-menu-title">
        Store
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-store'
              ) )
            ?>

    </div>

    <div class="ways-to-shop">
      <h6 class="footer-menu-title">
        Ways to Shop
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-ways-to-shop'
              ) )
            ?>

    </div>

    <div class="ideas-insipiration">
      <h6 class="footer-menu-title">
        IDEAS & INSPIRATION
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-ideas-inspiration'
              ) )
            ?>
      <div class="social-media-footer">
        <h6 class="column-font-size regular">Get social with us</h6>
        <div class="underline-dg"></div>
        <div class="social-media-container">
          <?php 
           $argsContact = array(
            'pagename' => 'contact'
          );
          $queryContact = new WP_Query( $argsContact );
          while($queryContact->have_posts()){
            $queryContact->the_post(); 
          ?>
          <a class="social-icon" href="<?php echo get_field("facebook");?>" target="_blank"><i
              class="fab fa-facebook-square"></i></a>
          <a class="social-icon" href="<?php echo get_field("instagram");?>" target="_blank"><i class="fab fa-instagram-square"></i></a>
          <a class="social-icon" href="<?php echo get_field("pintrest_");?>" target="_blank"><i class="fab fa-pinterest-square"></i></a>
          <a class="social-icon" href="<?php echo get_field("youtube");?>" target="_blank"><i class="fab fa-youtube"></i></a>
          <?php 
          
          }
          wp_reset_postdata(  );
          ?>
        </div>
      </div>

    </div>

  </div>

  <div class="footer-img">
    <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/Inspiry_Slogan-transparent.png" alt="Slogan">
  </div>
  <div class="copyright-container row-container light-grey">
    <div>© Copyright 2019 Inspiry NZ. All rights reserved. <a href="https://webduel.co.nz" rel="nofollow"
        target="_blank" class="dark-green rm-txt-dec"> Built By WebDuel</a></div>
  </div>

</footer>


<!--design board container-->
<div class="board-overlay overlay">
  <div class="choose-board-container" data-post-id="value" data-post-title="value">
    <div class="choose-board">Choose Board</div>
    <div class="close-icon">X</div>
    <ul class="board-list">
      <?php 
                                        
                                        //wp query to get parent title of boards 
                                        
                                        $boardLoop = new WP_Query(array(
                                            'post_type' => 'boards', 
                                            'post_parent' => 0,
                                            'author' => get_current_user_id()
                                        ));
                                        
                                        while($boardLoop->have_posts()){
                                            $boardLoop->the_post(); 
                                            
                                        }
                                    
                                            while($boardLoop->have_posts()){ 
                                                $boardLoop->the_post(); 
                                                global $product;
                                                // $productImage = wp_get_attachment_url( $product->get_image_id() );
                                                ?>
      <li class="board-list-item" data-boardID='<?php echo get_the_id(); ?>' 
      data-boardID='<?php echo $productImage; ?>'
        data-postStatus='<?php echo get_post_status();?>'>

        <?php 
                                                            
                                                        the_title();?>
        <div class="custom-loader"></div>

      </li>

      <?php
                                                wp_reset_postdata(  );
                                            }
                                        ?>
    </ul>
    <div class="create-new-board"><i class="fal fa-plus"></i> Create New Board</div>
  </div>

  <div class="project-save-form-section">

    <div class="project-save-form-container">
      <div class="poppins-font regular form-title medium-font-size">Create Board</div>
      <div class="form-underline"></div>
      <div class="form">
        <form id="new-board-form">
          <label for="name">Give your board a title*</label>
          <input type="text" name="board-name" id="board-name" required>
          <label for="description">Description</label>
          <textarea name="board-description" id="board-description" cols="30" rows="10"></textarea>
          <div class="toggle-btn-container">
            <label class="tgl tgl-gray" style="font-size:30px">
              <input type="checkbox" checked />
              <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
            </label>
            <div class="toggle-status poppins-font thin"><i class="fal fa-lock"></i> Private
            </div>

          </div>
          <div class="toggle-status-info poppins-font paragraph-font-size regular">
            Private boards cannot be shared with the general public.
          </div>

          <div class="btn-container">
            <button type="button" class="cancel-btn btn"> Cancel</button>
            <button type="submit" class="save-btn btn btn-dk-green archive-save-btn"> Save</button>

            <div class="custom-loader"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="go-to-header hide" id='go-to-header'>
  <a href="#header"><i class="fal fa-angle-up"></i></a>
</div>

<?php wp_footer();?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnify/2.3.3/js/jquery.magnify.min.js"
  integrity="sha512-YKxHqn7D0M5knQJO2xKHZpCfZ+/Ta7qpEHgADN+AkY2U2Y4JJtlCEHzKWV5ZE87vZR3ipdzNJ4U/sfjIaoHMfw=="
  crossorigin="anonymous" defer></script>

<!-- Optional mobile plugin (uncomment the line below to enable): -->
<!-- <script src="/js/jquery.magnify-mobile.js"></script> -->
<script>
  
  jQuery(document).ready(function ($) {
    $('.zoom').magnify({
      magnifiedWidth: 1500
    });
  });
</script>

</body>

</html>