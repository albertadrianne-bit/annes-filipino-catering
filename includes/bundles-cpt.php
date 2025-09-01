<?php
if ( ! defined('ABSPATH') ) exit;

add_action('init', function(){
  register_post_type('annesfs_bundle',[
    'label' => 'Bundles',
    'public' => true,
    'show_in_menu' => 'annesfs-settings',
    'supports' => ['title','editor','thumbnail'],
    'menu_position' => 57,
  ]);
});

add_action('add_meta_boxes', function(){
  add_meta_box('annesfs_bundle_rules','Bundle Rules','annesfs_bundle_rules_box','annesfs_bundle','normal','high');
});

function annesfs_bundle_rules_box($post){
  $count = get_post_meta($post->ID,'annesfs_tray_count', true) ?: 5;
  $size  = get_post_meta($post->ID,'annesfs_tray_size', true) ?: 'medium';
  $cap   = get_post_meta($post->ID,'annesfs_premium_cap', true) ?: 2;
  $disc  = get_post_meta($post->ID,'annesfs_discount_percent', true) ?: 10;
  $cat   = get_post_meta($post->ID,'annesfs_category_filter', true) ?: '';
  ?>
  <p>Tray count: <input type="number" name="annesfs_tray_count" value="<?php echo esc_attr($count); ?>" style="width:100px"></p>
  <p>Tray size:
    <select name="annesfs_tray_size">
      <option value="small" <?php selected($size,'small'); ?>>Small</option>
      <option value="medium" <?php selected($size,'medium'); ?>>Medium</option>
      <option value="large" <?php selected($size,'large'); ?>>Large</option>
    </select>
  </p>
  <p>Premium cap: <input type="number" name="annesfs_premium_cap" value="<?php echo esc_attr($cap); ?>" style="width:100px"></p>
  <p>Discount %: <input type="number" name="annesfs_discount_percent" value="<?php echo esc_attr($disc); ?>" style="width:100px"></p>
  <p>Category filter (slug, optional; e.g. desserts): <input type="text" name="annesfs_category_filter" value="<?php echo esc_attr($cat); ?>" style="width:240px"></p>
  <?php
}

add_action('save_post_annesfs_bundle', function($post_id){
  foreach(['tray_count','tray_size','premium_cap','discount_percent','category_filter'] as $k){
    if(isset($_POST['annesfs_'.$k])) update_post_meta($post_id, 'annesfs_'.$k, sanitize_text_field($_POST['annesfs_'.$k]));
  }
});
