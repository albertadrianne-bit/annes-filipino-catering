<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Basic dynamic discount tiers by total cart quantity.
 * Defaults: 5+ = 5%, 7+ = 8%, 10+ = 10%
 */

add_action('woocommerce_cart_calculate_fees', function( $cart ){
    if ( is_admin() && ! defined('DOING_AJAX') ) return;
    if ( ! $cart || $cart->is_empty() ) return;

    // Load settings or defaults
    $t5  = floatval( get_option('annesfs_tier_5_qty', 5) );
    $d5  = floatval( get_option('annesfs_tier_5_disc', 5) );
    $t7  = floatval( get_option('annesfs_tier_7_qty', 7) );
    $d7  = floatval( get_option('annesfs_tier_7_disc', 8) );
    $t10 = floatval( get_option('annesfs_tier_10_qty', 10) );
    $d10 = floatval( get_option('annesfs_tier_10_disc', 10) );

    // Count total quantity (treating all items as “trays” for now)
    $qty_total = 0;
    foreach ( $cart->get_cart() as $item ) {
        $qty_total += intval( $item['quantity'] );
    }

    // Pick best tier
    $rate = 0;
    if ( $qty_total >= $t10 && $d10 > $rate ) $rate = $d10;
    if ( $qty_total >= $t7  && $d7  > $rate ) $rate = $d7;
    if ( $qty_total >= $t5  && $d5  > $rate ) $rate = $d5;

    if ( $rate > 0 ) {
        $subtotal = $cart->get_subtotal();
        $discount = - ( $subtotal * ( $rate / 100 ) );
        $label    = sprintf( __('Bundle discount (%s%% off)', 'annesfs'), $rate );
        $cart->add_fee( $label, $discount );
    }
}, 20);
