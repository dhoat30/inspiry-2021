<?php 
// remove sale badge
add_filter('woocommerce_sale_flash', 'webduel_hide_sale_flash');
function webduel_hide_sale_flash(){
return false;
}


add_filter ( 'woocommerce_product_thumbnails_columns', 'webduel_change_gallery_columns' );
 
function webduel_change_gallery_columns() {
     return 1; 
}

