<?php 

// print_r(WC()->cart->get_cart_contents_count()); 
add_action('woocommerce_before_cart_table', 'webduelCartModal', 10); 
function webduelCartModal(){ 
    $categorySlug =  webduelGetCartDealCategory(); 
    //   query modal with category slug
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
        echo '<div class="cart-deal-modal modal-section" data-overlay="true">
                <i class="fal fa-times"></i>
               <div class="content" >
                    <div class="section-font-size center-align title">'.get_the_title().'</div>
                    <div class="center-align poppins-font medium-font-size regular subtitle">'.get_the_content().'</div>
                    <div class="image-container" >
                    <img src="'.get_the_post_thumbnail_url(get_the_id()).'"/>
                    </div>
                    <a target="_blank" href="'.get_field('link').'" class="button btn-dk-green btn-dk-green-border">Browse Now</a>
                </div>
            </div>
        '; 
    }
    wp_reset_postdata();

}

// get the first deal from cart and return the category slug of the deal
function webduelGetCartDealCategory(){ 
       
    $cart = WC()->cart;

    // Loop over $cart items
    $productWithDealsArray = array(); 
    // for each loop for cart items 
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            // check if the product had a current deal attribute and has quantity less then 2
            if($product->get_attribute( 'pa_current-deal') &&  $cart_item['quantity'] <2 && $product->price != 0){ 
                    array_push($productWithDealsArray, array(
                        "name"=> $product->name, 
                        "dealName"=> $product->get_attribute( 'pa_current-deal' ), 
                        "id"=> $product->get_id(), 
                        "qty"=> $cart_item['quantity']
                    )); 
            }
        }
        $count = array_count_values(
            array_column($productWithDealsArray, 'dealName')
        );
        // get all categories name of modal CPT
        $modalCategories = get_terms( array( 
            'taxonomy' => 'modal-categories',
            'parent'   => 0
        ) );
        // find out which deal should be shown in the modal 
        $dealToBeInModal = ''; 
        foreach($count as  $key=> $item){ 
            // check if the dealItem has less than two count 
            if($item<2){ 
                $dealToBeInModal = $key; 
            }
        }
     
        // get category slug for custom post type
        $categorySlug = ''; 
        foreach ($modalCategories as $item ) {
            // process element here
            if($item->name === $dealToBeInModal){
                $categorySlug = $item->slug; 
            }
        }
        return $categorySlug; 
}