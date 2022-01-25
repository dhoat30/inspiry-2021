<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>


    <section class="project-page">
        <!--hero section  -->
        <section class="trade-directory-page">
        <!--hero section  -->
        <section class="hero-section trade-directory-hero-section beige-color-bc">
            <div class="row-container hero-container">
                <h3 class="column-font-size dark-grey regular">Be Inspired</h3>
               
                        <h1 class="dark-grey large-font-size typewriter-title" ></h1>

                  
            </div>
        </section>
        <!-- main content section  -->

        <section class="trade-directory row-container main-content margin-row">
            
            <div class="sidebar">
                    <div class="close-icon">
                        <i class="fal fa-times"></i>
                    </div>
                    <?php //echo do_shortcode('[gd_categories post_type="0" max_level="1" max_count="all" max_count_child="all" title_tag="h4" sort_by="count"]');?>
                    <div class="category">
                        <div class="facet-wp-code">
                            <div class="title">
                                <h2 class="regular column-font-size"> Project Categories</h2>
                                <i class="fal fa-plus"></i>
                            </div>
                            
                          
                            <?php echo do_shortcode('[facetwp facet="project_categories"]');?>
                            
                        </div>
                    </div>

                    
                    
                
                    <button onclick="FWP.reset()" class="facet-reset-btn">Reset</button>
                    
            </div>
            <div class="main-cards">
                <!-- count the number of trade proffesionals  -->

              
                <h1 class="section-font-size regular"><?php  //echo $tradeProfessionals->post_count;?> Projects </h1>
                <div class="refine-button">
                    <a class="btn button btn-dk-green-border rm-txt-dec"><i class="fal fa-filter"></i> Filters</a>
                </div>
                <div class="flex">
                    <!-- get the template from facet wp  -->
                    <?php echo do_shortcode('[facetwp template="projects"]');?>
                    
                    
                
                </div>
                <?php echo do_shortcode('[facetwp facet="pager_"]'); ?>
            </div>
        </section>
        <section class="typewriter-query-container">
            <?php 
                    $argsTypewriter = array(
                        'post_type' => 'typewriter_effect', 
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'typewriter-category',
                                'field'    => 'slug',
                                'terms'    => array( 'project-page'),
                            )
                            ),
                    );
                    $typewriterEffect = new WP_Query( $argsTypewriter );
                       
                        $titleArray = array_map('get_the_title', $typewriterEffect->posts);
                        
                        ?>
                        <div data-title='<?php  echo json_encode($titleArray);?>'></div>

                        <?php
                    while($typewriterEffect->have_posts()){
                        $typewriterEffect->the_post();
                    }
                    wp_reset_postdata();
                ?>  
        </section>
    </section>

                    
                  

    <?php
}

get_footer();
?>