<?php 

// add deal information on single product page
add_action('woocommerce_simple_add_to_cart', 'webduelModal', 10); 

function webduelModal(){ 
    
    global $product; 
    // get current deal for the single product 
    $currentDeal = $product->get_attribute( 'pa_current-deal' );

    // get modal Categories
    $modalCategories = get_terms( array( 
        'taxonomy' => 'modal-categories',
        'parent'   => 0
    ) );
 
     // get category slug for custom post type
     $categorySlug = ''; 
     foreach ($modalCategories as $item ) {
         // process element here
         if($item->name === $currentDeal){
             $categorySlug = $item->slug; 
         }
     }
     
     singleProductQuery($categorySlug); 
}   

// modal query 

function singleProductQuery($categorySlug){ 
       //  query modal with category slug
       $the_query = new WP_Query( array(
        'post_type' => 'modal',
        'tax_query' => array(
            array (
                'taxonomy' => 'modal-categories',
                'field' => 'slug',
                'terms' => $categorySlug
            )
        ),
    ) );
    while($the_query->have_posts()){ 
        $the_query->the_post(); 
        echo '<div class="deal-section">
               <div class="content" >
                    <div class="column-font-size title">'.get_the_title().'</div>
                    <div class="poppins-font regular-font-size regular subtitle">'.get_the_content().'</div>
                </div>
            </div>
        '; 
    }
    wp_reset_postdata();
}