<?php
get_header(); 
?>
<div class="body-container">
    <div class="row-container board-loop-page single-board box-shadow" data-poststatus=<?php echo get_post_status(); ?>>
        <div class="back-icon-container">
            <a href="<?php echo get_site_url(); ?>/boards">
                <i class="fal fa-arrow-left"></i>
            </a>
        </div>
        <h1 class="large-font-size playfair-fonts regular light-grey"> <?php echo get_the_title($parentID);?></h1>
        <div class="btn-container">
            <?php //check if the post status is publish 
                                if(get_post_status() == 'publish'){
                                    ?>
            <div class='action-btn-container'>
                <button class="share btn btn-dk-green-border paragraph-font-size"><i class="fal fa-share-alt"></i>
                    Share</button>

                <div class="share-icons box-shadow">
                    
           
                        <i class="fal fa-times"></i>
                        <h2 class="poppins-font medium-font-sizeium regular">Share this board</h2>
                        <div class="underline underline-bg margin-elements"></div>
                        <div>
                            <?php echo do_shortcode('[Sassy_Social_Share]');?>
                        </div>
                    
                </div>
            </div>
            <?php
                                }
                            ?>

            <div class="image-upload-container" data-parentid=<?php echo get_the_id();?>>
                <button class="img-upload share btn btn-dk-green-border paragraph-font-size"> <i
                        class="far fa-arrow-to-top"></i> Upload</button>

                <div>
                    <div class="project-save-form-container">
                        <div class="poppins-font regular form-title medium-font-size">Upload</div>
                        <div class="form-underline"></div>
                        <div class="form">
                            <form action="{{admin_url}}" method="POST" enctype="multipart/form-data" id="upload-image">





                                <input type="hidden" name="action" value="my_file_upload" id="action" />
                                <label for="image">Select file:</label>
                                <input type="file" id="image" name="my_file_field" accept="image/*" />



                                <div class="btn-container">
                                    <button type="button" class="cancel-btn btn"> Cancel</button>
                                    <button type="submit" class="save-btn btn btn-dk-green archive-save-btn">
                                        Upload</button>

                                    <div class="custom-loader"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="board-flex">

            <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => get_the_id(),
                'posts_per_page' => -1
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                $parentID =  wp_get_post_parent_id(get_the_id()); 
                ?>

            <div class="board-card design-board-single-card">

                <i class="fas fa-ellipsis-h option-icon"></i>
                <div class="pin-options-container box-shadow">
                    <ul class="dark-grey">
                        <?php 
                            if(get_post_status() == 'publish'){
                                ?>
                        <li class="share-btn"><i class="fas fa-share-alt"></i> Share</li>
                        <?php
                            }
                        ?>
                        <?php 
                        //check if the author is logged in 
                        global $current_user;
                        get_currentuserinfo();
                        
                        if (is_user_logged_in() && $current_user->ID == $post->post_author) {
                             
                            ?>

                        <!-- <li class="website-btn"><a class='rm-txt-dec' target="_blank" href='<?php// echo do_shortcode('[gd_post_meta key="website" id="7345" show="value-raw" no_wrap="1"]');?>'><i class="fas fa-globe"></i> Website</a></li>-->
                        <li class="delete-btn" data-pinid='<?php the_ID();?>'><i class="far fa-trash-alt"></i> Delete
                        </li>
                        <?php
                        }
        
                        ?>


                    </ul>


                </div>

                <div class="share-icon-container box-shadow">
                    <div class="poppins-font regular medium-font-size"> Share this pin </div>
                    <div class="underline"></div>
                    <div>

                        <?php echo do_shortcode('[Sassy_Social_Share url="<?php echo get_the_permalink(get_field("saved_project_id")); ?>"]');?>
                    </div>
                    <span class="close-icon">X</span>
                </div>

                
                <?php 
                        //check if the image id exists
                        if(get_field('saved_image_id')){
                            $imgSmall =  wp_get_attachment_image_src(get_field('saved_image_id'), 'large');
                            $imgFull = wp_get_attachment_image_src(get_field('saved_image_id'), 'full');
                            ?>
                        <div class="thumbnail">
                            <img src="<?php echo $imgSmall[0] ?>" alt="<?php echo get_the_title();?>" class="event-image">
                            <img src="" data-src="<?php echo $imgFull[0]?>" alt="<?php echo get_the_title();?>" class="lightbox-image">
                        </div>
                        <div class="title paragraph-font-size rm-txt-dec">
                            <?php echo get_the_title(get_field('saved_project_id')); ?>
                        </div>

                    <?php
                        }
                        else{
                            
                           ?>
                            <a href="<?php echo get_the_permalink(get_field('saved_project_id')); ?>">
                                <div class="thumbnail">
                                    <?php 
                                    echo get_the_post_thumbnail( get_field('saved_project_id'), 'post-thumbnail');
                                    ?>
                                </div>
                                <div class="title paragraph-font-size rm-txt-dec">
                                    <?php echo get_the_title(get_field('saved_project_id')); ?>
                            </div>
                            </a>
                           <?php 
                        }
                        ?>

                

            

            </div>

            <?php
            }
        ?>


        </div>
    </div>
</div>
<div>

</div>


<?php 
get_footer(); 
?>