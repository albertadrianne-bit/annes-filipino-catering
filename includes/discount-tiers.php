<?php
if ( ! defined('ABSPATH') ) exit;

add_action('woocommerce_cart_calculate_fees', function($cart){
  if ( is_admin() && ! defined('DOING_AJAX') ) return;
  if ( ! $cart || $cart->is_empty() ) return;
  if ( ! intval(get_option('annesfs_tiers_enabled',1)) ) return;

  $tiers = [
    [ 'qty' => intval(get_option('annesfs_t1_qty', 5)), 'disc' => floatval(get_option('annesfs_t1_disc', 10)) ],
    [ 'qty' => intval(get_option('annesfs_t2_qty', 7)), 'disc' => floatval(get_option('annesfs_t2_disc', 12)) ],
    [ 'qty' => intval(get_option('annesfs_t3_qty', 9)), 'disc' => floatval(get_option('annesfs_t3_disc', 15)) ],
  ];

  $qty_total=0; foreach($cart->get_cart() as $item){ $qty_total += intval($item['quantity']); }
  $rate=0.0; foreach($tiers as $t){ if($qty_total>=max(0,$t['qty'])) $rate=max($rate,max(0.0,$t['disc'])); }

  if($rate>0){
    $subtotal=$cart->get_subtotal();
    if($subtotal>0){
      $cart->add_fee(sprintf('Bundle discount (%s%% off)', wc_format_decimal($rate,2)), -($subtotal*($rate/100)));
    }
  }
},20);
