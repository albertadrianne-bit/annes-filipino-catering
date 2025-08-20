<?php
// Checkout modal placeholder
add_action('woocommerce_after_cart', function(){
    echo '<div id="afc-checkout-modal" style="display:none;">[Checkout Modal]</div>';
});
?>
