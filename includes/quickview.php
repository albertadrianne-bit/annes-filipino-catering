<?php
if ( ! defined('ABSPATH') ) exit;

add_action('woocommerce_after_shop_loop_item', function(){
  global $product; if(! $product) return;
  $pid = $product->get_id();
  $label = $product->is_type('variable') ? __('Choose Options','annesfs') : __('Quick Add','annesfs');
  echo '<a href="#" class="button annesfs-qv-btn" data-pid="'.esc_attr($pid).'">'.$label.'</a>';
}, 15);

add_action('wp_footer', function(){ ?>
  <div id="annesfs-qv" class="annesfs-qv" style="display:none">
    <div class="annesfs-qv__backdrop"></div>
    <div class="annesfs-qv__panel" role="dialog" aria-modal="true">
      <button type="button" class="annesfs-qv__close" aria-label="Close">×</button>
      <div class="annesfs-qv__content"><div class="annesfs-qv__loading">Loading…</div></div>
    </div>
  </div><?php
});

add_action('wp_ajax_annesfs_qv','annesfs_qv'); 
add_action('wp_ajax_nopriv_annesfs_qv','annesfs_qv');

function annesfs_qv(){
  $pid = isset($_GET['pid'])?absint($_GET['pid']):0; if(!$pid) wp_send_json_error(['html'=>'Bad request']);
  $product = wc_get_product($pid); if(!$product) wp_send_json_error(['html'=>'Not found']);
  ob_start(); ?>
  <div class="qv-head"><div class="qv-title"><?php echo esc_html($product->get_name()); ?></div>
  <div class="qv-price"><?php echo wp_kses_post($product->get_price_html()); ?></div></div>
  <div class="qv-body">
    <div class="qv-media"><?php echo $product->get_image('woocommerce_single',["style"=>'border-radius:10px;max-width:100%;height:auto;object-fit:cover']); ?></div>
    <div class="qv-form">
    <?php if($product->is_type('variable')){
      $attributes=$product->get_variation_attributes(); $available=$product->get_available_variations();
      wc_get_template('single-product/add-to-cart/variable.php',[ 'available_variations'=>$available,'attributes'=>$attributes,'selected_attributes'=>$product->get_default_attributes() ]);
    } else { wc_get_template('single-product/add-to-cart/simple.php',['product'=>$product]); } ?>
    </div>
  </div><?php
  $html = ob_get_clean(); wp_send_json_success(['html'=>$html]);
}
