<?php 
/* Template Name: Places * Template Post Type: post*/ /*The template for displaying full width single posts. */
get_header(); 

?>

<section class="home-page">
    <div class="slider-container">

        <div class="slider">
           


            <?php 

                                        $args = array(
                                            'post_type' => 'sliders',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'category',
                                                    'field'    => 'slug',
                                                    'terms'    => array( 'home-page-hero-slider'),
                                                )
                                                ), 
                                                'orderby' => 'date', 
                                                'order' => 'ASC'
                                        );
                                        $query = new WP_Query( $args );

                                        while($query->have_posts()){ 
                                            $query->the_post(); 
                                            $image = get_field('mobile_image'); 
                                            $imgUrl; 
                                            if($image['sizes']['medium_large']){
                                                $imgUrl = $image['sizes']['medium_large'];
                                            }
                                            else{
                                                $imgUrl = $image['url'];
                                            }
                                          
                                            ?>
                                            <div class="slide">
                                                <a  href="<?php echo  get_field('add_link'); ?>">
                                            <picture > 
                                                    <source media="(min-width:700px)" srcset="<?php echo get_the_post_thumbnail_url(null,"full"); ?>">
                                                    <source media="(min-width:465px)" srcset="<?php echo get_the_post_thumbnail_url(null,"large"); ?>">
                                                    <img   src="<?php echo esc_url($imgUrl);?>" alt="<?php echo get_the_title();?>">
                                                </picture>   

                                                    </a>   
                                                     </div>
                                                           
                                            <?php

                                       
                                        }
                                        wp_reset_postdata();

                                        ?>



        </div>


        <div class="buttons">
            <button id="prev"><i class="fas fa-arrow-left"></i></button>
            <button id="next"><i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <div class="beige-color-bc usp">
        <div>
            <i class="fal fa-truck"></i>
            <div>
                <span class="poppins-font medium-font-size medium">Free Shipping NZ Wide</span> <br>
                <span>on all wallpaper and fabric</span>
            </div>

        </div>
        <div>
            <i class="fal fa-tag"></i>
            <div>
                <span class="poppins-font medium-font-size medium">Beautiful Designer Brands</span> <br>
                <span class="poppins-font paragraph-font-size">furniture, homeware & rugs</span>
            </div>
        </div>
        <div>
            <i class="fal fa-shipping-timed"></i>
            <div>
                <span class="poppins-font medium-font-size medium">shipped daily</span> <br>
                <span>to your door</span>
            </div>
        </div>
    </div>

    <div class="box-section">
        <div class="flex">
            <div class="photo photo-1">
                <picture>
                    <source media="(max-width: 500px)"
                        srcset="<?php echo get_site_url();?>/wp-content/uploads/2020/11/frida_image_roomshot_kitchen_item_7675-370x370.jpg">
                    <source media="(max-width: 800px)"
                        srcset="<?php echo get_site_url();?>/wp-content/uploads/2020/11/frida_image_roomshot_kitchen_item_7675-960x960.jpg">
                    <img loading="lazy" src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/frida_image_roomshot_kitchen_item_7675.jpg"
                        alt="Frida">
                </picture>

            </div>

            <div class="boxes">
                <div>
                    <p class="section-font-size white center-align"> Creative Kitchen Walls</p>
                    <a class="rm-txt-dec shop-borastapeter-button-home" href="<?php echo get_site_url();?>/brands/boras-tapeter/"> <i
                            class="fal fa-arrow-left "></i> Shop Borastapeter <i class="fal fa-arrow-up"></i></a>
                </div>
                <div>
                    <p class="section-font-size white center-align"> Good Things Are Coming</p>
                    <a class="rm-txt-dec shop-khroma-button-home" href="<?php echo get_site_url();?>/brands/khroma"> Shop Khroma <i
                            class="fal fa-arrow-right"></i> <i class="fal fa-arrow-down"></i></a>
                </div>
            </div>

            <div class="photo photo-2">
                <picture>
                    <source media="(max-width: 500px)"
                        srcset="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1-370x370.jpg">
                    <source media="(max-width: 800px)"
                        srcset="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1-960x960.jpg">
                    <img loading="lazy" src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1.jpg"
                        alt="Khroma">
                </picture>
            </div>

        </div>


    </div>
</section>




<!--Loving Section ----->

<div class="loving-section row-section margin-row">
    <div class="underline-dg"></div>

    <div class="large-font-size center-align">What We’re Loving</div>
    <div class="flex">

        <?php 

            $argsLoving = array(
                'post_type' => 'loving',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => array( 'home-page-loving-section')
                    )
                    ), 
                    'orderby' => 'date', 
                    'order' => 'ASC'
            );
            $loving = new WP_Query( $argsLoving );

            while($loving->have_posts()){ 
                $loving->the_post(); 

                ?>
        
            <div class="cards">
            
                <div>
                    <picture>
                        <source media="(max-width: 500px)"
                            srcset="<?php echo get_the_post_thumbnail_url(null,"medium"); ?>">
                       
                        <img loading="lazy" src="<?php echo get_the_post_thumbnail_url(null,"large"); ?>"
                            alt="Khroma">
                    </picture>
                    <a class="rm-txt-dec center-align"  id="we-are-loving" href="<?php echo get_field('add_link');?>">Shop Now <i
                            class="fal fa-arrow-right"></i> </a>
                </div>
            </div>
    
        <?php 

            }
            wp_reset_postdata();
            ?>




    </div>
</div>
<section class="brand-stripe">
    <div class="flex">

    <?php 

            $brandLogo = array(
                'post_type' => 'brand-logo',
                'posts_per_page'=> -1
            );
            $brandLogoQuery = new WP_Query( $brandLogo );

            while($brandLogoQuery->have_posts()){ 
                $brandLogoQuery->the_post(); 

                ?>
        
        <img loading="lazy" src="<?php echo get_the_post_thumbnail_url(null,"medium"); ?>" alt="<?php echo get_the_title();?>">
       

    <?php 
            }
            wp_reset_postdata();

    ?>

    </div>
</section>
<div class="row-container featured-project-section">
    <div class="underline-dg"></div>

    <div class="large-font-size center-align">Featured Project</div>

    <div class="flex">
        <?php 
                $argsPorject = array(
                    'post_type' => 'gd_project', 
                    'posts_per_page' => 4
                );
                $project = new WP_Query( $argsPorject );
    
                while($project->have_posts()){ 
                    $project->the_post(); 
                    ?>


        <div class="card">
            <a href="<?php the_permalink();?>" class="view-project">
                <div>
                   
                    <img loading="lazy" src="<?php echo get_the_post_thumbnail_url(null, 'large');?>" alt="">
                    <div class="hover-overlay"></div>
                    <div class="column-font-size"><?php the_title();?></div>
                </div>
            </a>
        </div>
        <?php
                    } 
                    wp_reset_postdata();
            ?>
    </div>
</div>

<div class="row-container featured-trade-section">
    <div class="flex">
        <div class="card">
            <div class="column-font-size center-align medium">TRADE PROFESSIONALS</div>
            <div class="underline-dg center-align"></div>
            <div class="paragraph center-align">Inspiry Trade <br>
                List your services with and be apart of our 'Be Inspired' page with your featured projects.
                <br>Request an information pack<br>
                trade@inspiry.co.nz
            </div>
            <a href="<?php echo get_site_url();?>/location" id="view-trade-proffesional" class="rm-txt-dec button btn-dk-green">View Trade
                Professionals</a>
        </div>

        <?php 
                $argsTrade = array(
                    'post_type' => 'gd_place', 
                    'posts_per_page' => 2, 
                    'order'=>'DESC', 
                    'orderby'=> 'title'
                );
                $trade = new WP_Query( $argsTrade );
    
                while($trade->have_posts()){ 
                    $trade->the_post(); 
                    ?>


        <div class="card">
            <img loading="lazy" class="gallery" src="<?php echo get_the_post_thumbnail_url(null, 'large');?>"
                alt="Trade Proffesionals">
            <div class="logo">

                <?php
                                $variable =  do_shortcode('[gd_post_meta key="logo" show="value-raw" no_wrap="1" alignment="left"]');
                                $variable = substr($variable, 0, strpos($variable, "|"));
                                ?>
                <img loading="lazy" src="<?php echo  $variable?>" alt="<?php the_title();  ?>">
            </div>
            <div class="medium-font-size center-align regular light-grey"><?php the_title();  ?></div>

            <table>
                <tr>
                    <th>LOCATION:</th>

                    <td><?php echo do_shortcode('[gd_post_meta key="city" show="value-raw" no_wrap="1" alignment="left"]');?>
                    </td>
                </tr>
                <tr>
                    <th>PHONE:</th>

                    <td>
                        <a class="rm-txt-dec"
                            href='tel:<?php echo do_shortcode('[gd_post_meta key="phone" show="value-raw"  no_wrap="1" alignment="left"]');?>'>

                            <?php echo do_shortcode('[gd_post_meta key="phone" show="value-raw" no_wrap="1" alignment="left"]');?>
                    </td>
                    </a>
                </tr>

                <tr>
                    <th>EMAIL:</th>

                    <td>
                        <a class="rm-txt-dec"
                            href='mailto:<?php echo do_shortcode('[gd_post_meta key="email" show="value-raw"  no_wrap="1" alignment="left"]');?>'>

                            <?php echo do_shortcode('[gd_post_meta key="email" show="value-raw" no_wrap="1" alignment="left"]');?>
                        </a>
                    </td>

                </tr>

                <tr>
                    <th>WEBSITE:</th>
                    <td>
                        <a class="rm-txt-dec"
                            href='mailto:<?php echo do_shortcode('[gd_post_meta key="email" show="value-raw"  no_wrap="1" alignment="left"]');?>'>

                            <?php echo do_shortcode('[gd_post_meta key="website" show="value-strip" no_wrap="1" alignment="left"]');?>
                        </a>
                    </td>
                </tr>
            </table>

        </div>
        <?php
                    } 
            ?>
    </div>
</div>

<script>
    const slides = document.querySelectorAll('.slide');
    const next = document.querySelector('#next');
    const prev = document.querySelector('#prev');
    const auto = true; // Auto scroll
    const intervalTime = 3000;
    let slideInterval;
    slides[0].classList.add('current');

    const nextSlide = () => {
        // Get current class
        const current = document.querySelector('.current');
        // Remove current class
        current.classList.remove('current');
        // Check for next slide
        if (current.nextElementSibling) {
            // Add current to next sibling
            current.nextElementSibling.classList.add('current');
        } else {
            // Add current to start
            slides[0].classList.add('current');
        }
        setTimeout(() => current.classList.remove('current'));
    };

    const prevSlide = () => {
        // Get current class
        const current = document.querySelector('.current');
        // Remove current class
        current.classList.remove('current');
        // Check for prev slide
        if (current.previousElementSibling) {
            // Add current to prev sibling
            current.previousElementSibling.classList.add('current');
        } else {
            // Add current to last
            slides[slides.length - 1].classList.add('current');
        }
        setTimeout(() => current.classList.remove('current'));
    };

    // Button events
    next.addEventListener('click', e => {
        console.log('clicked');
        nextSlide();
        if (auto) {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, intervalTime);
        }
    });

    prev.addEventListener('click', e => {
        prevSlide();
        if (auto) {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, intervalTime);
        }
    });

    // Auto slide
    if (auto) {
        // Run next slide at interval time
        slideInterval = setInterval(nextSlide, intervalTime);
    }
</script>
<?php 

get_footer(); 
?>