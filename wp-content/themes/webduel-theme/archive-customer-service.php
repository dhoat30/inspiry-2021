<?php
get_header(); 
?>
<div>
<?php
                wp_nav_menu(
                        array(
                            'theme_location' => 'customer-service-sidebar', 
                            'container_id' => 'customer-service-sidebar'
                        ));
                ?>   
</div>

<?php get_footer(); ?>