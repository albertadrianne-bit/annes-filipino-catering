<?php
if ( ! defined('ABSPATH') ) exit;

add_action('woocommerce_widget_shopping_cart_before_buttons', 'annesfs_estimator_mini_cart');
add_action('woocommerce_cart_totals_after_order_total', 'annesfs_estimator_cart_totals');

function annesfs_estimator_mini_cart(){ echo annesfs_render_cart_estimate('mini'); }
function annesfs_estimator_cart_totals(){ echo annesfs_render_cart_estimate('cart'); }

function annesfs_render_cart_estimate($context='cart'){
  if ( is_admin() && ! defined('DOING_AJAX') ) return '';
  $json = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}'); $parts=json_decode($json,true);
  if ( ! is_array($parts) ) return '';
  $small=intval($parts['small'][0]??2); $medium=intval($parts['medium'][0]??8); $large=intval($parts['large'][0]??12);

  $qtySmall=$qtyMed=$qtyLarge=0; if ( ! WC()->cart ) return '';
  foreach ( WC()->cart->get_cart() as $item ){
    $qty=intval($item['quantity']); $name=strtolower($item['data']->get_name());
    if (strpos($name,'large')!==false) $qtyLarge+=$qty;
    elseif(strpos($name,'medium')!==false) $qtyMed+=$qty;
    elseif(strpos($name,'small')!==false) $qtySmall+=$qty;
    else $qtySmall+=$qty;
  }
  $guests = $qtyLarge*$large + $qtyMed*$medium + $qtySmall*$small;
  if ( $guests <= 0 ) return '';
  $rng = sprintf('~%d guests',$guests);

  ob_start(); ?>
  <div class="annesfs-estimate <?php echo esc_attr($context); ?>" style="margin:10px 0;padding:10px;border:1px dashed #e4e0d7;border-radius:10px;background:#fff9f4;">
    <strong>👥 Guest estimate:</strong>
    <div><?php echo esc_html($rng); ?></div>
  </div><?php
  return ob_get_clean();
}
