<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Dynamic discount tiers based on total cart quantity (treat as "tray count").
 * Defaults: 5+ = 5%, 7+ = 8%, 10+ = 10%
 */
add_action('woocommerce_cart_calculate_fees', function( $cart ){
    if ( is_admin() && ! defined('DOING_AJAX') ) return;
    if ( ! $cart || $cart->is_empty() ) return;

    $t5  = floatval( get_option('annesfs_tier_5_qty', 5) );
    $d5  = floatval( get_option('annesfs_tier_5_disc', 5) );
    $t7  = floatval( get_option('annesfs_tier_7_qty', 7) );
    $d7  = floatval( get_option('annesfs_tier_7_disc', 8) );
    $t10 = floatval( get_option('annesfs_tier_10_qty', 10) );
    $d10 = floatval( get_option('annesfs_tier_10_disc', 10) );

    // Count total quantity
    $qty_total = 0;
    foreach ( $cart->get_cart() as $item ) {
        $qty_total += intval( $item['quantity'] );
    }

    // Best tier wins
    $rate = 0;
    if ( $qty_total >= $t10 && $d10 > $rate ) $rate = $d10;
    if ( $qty_total >= $t7  && $d7  > $rate ) $rate = $d7;
    if ( $qty_total >= $t5  && $d5  > $rate ) $rate = $d5;

    if ( $rate > 0 ) {
        $subtotal = $cart->get_subtotal();
        if ( $subtotal > 0 ) {
            $discount = - ( $subtotal * ( $rate / 100 ) );
            $label    = sprintf( __('Bundle discount (%s%% off)', 'annesfs'), $rate );
            $cart->add_fee( $label, $discount );
        }
    }
}, 20);
