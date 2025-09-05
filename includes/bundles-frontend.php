<?php
if ( ! defined('ABSPATH') ) exit;

add_filter('the_title', function($title, $post_id){
  if (get_post_type($post_id)!=='annesfs_bundle') return $title;
  if (! intval(get_option('annesfs_show_guest_badges',1)) ) return $title;

  $size  = get_post_meta($post_id,'annesfs_tray_size', true) ?: 'medium';
  $count = intval(get_post_meta($post_id,'annesfs_tray_count', true) ?: 5);
  $map = ['small'=>2,'medium'=>8,'large'=>12];
  $guests = $count * ($map[$size] ?? 8);
  $tip = esc_attr(get_option('annesfs_tooltip_text',''));
  $badge = ' <span class="annesfs-guest-badge" title="'.$tip.'">• Feeds ~'.$guests.' guests</span>';
  return $title.$badge;
}, 10, 2);

add_filter('the_content', function($content){
  if (get_post_type()!=='annesfs_bundle') return $content;
  return $content.'<p><a href="#" class="button annesfs-bundle-build" data-bundle="'.get_the_ID().'">Build this bundle</a></p>';
});

add_action('wp_ajax_annesfs_bundle_modal','annesfs_bundle_modal');
add_action('wp_ajax_nopriv_annesfs_bundle_modal','annesfs_bundle_modal');

function annesfs_bundle_modal(){
  $bid = absint($_GET['bid'] ?? 0); if (!$bid) wp_send_json_error(['html'=>'Bad request']);
  $title = get_the_title($bid);
  $count = intval(get_post_meta($bid,'annesfs_tray_count', true) ?: 5);
  $cap   = intval(get_post_meta($bid,'annesfs_premium_cap', true) ?: 2);
  $disc  = floatval(get_post_meta($bid,'annesfs_discount_percent', true) ?: 10);
  ob_start(); ?>
  <div class="annesfs-bundle-head">
    <h3><?php echo esc_html($title); ?></h3>
    <div class="annesfs-bundle-meta">Choose <?php echo esc_html($count); ?> dishes • Premium allowed: <?php echo esc_html($cap); ?></div>
  </div>
  <div class="annesfs-bundle-grid" data-count="<?php echo esc_attr($count); ?>" data-cap="<?php echo esc_attr($cap); ?>" data-disc="<?php echo esc_attr($disc); ?>">
    <div class="annesfs-bundle-placeholder">Product grid loads here…</div>
  </div>
  <div class="annesfs-bundle-footer">
    <div class="annesfs-bundle-totals">Subtotal: $0 • Discount: $0 • Total: $0</div>
    <button class="button button-primary annesfs-bundle-add">Add Bundle to Cart</button>
  </div>
  <?php
  $html = ob_get_clean(); wp_send_json_success(['html'=>$html]);
}
