<?php
// Display category-based badges
add_action('woocommerce_before_shop_loop_item_title', function() {
    global $product;
    $terms = wp_get_post_terms($product->get_id(), 'product_cat');
    foreach($terms as $term){
        if(in_array(strtolower($term->slug), ['classic','premium','elite'])) {
            echo '<span class="afc-badge afc-' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</span>';
        }
    }
});
?>
