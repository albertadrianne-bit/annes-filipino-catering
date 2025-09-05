<?php
if ( ! defined('ABSPATH') ) exit;

add_action('admin_menu', function(){
  add_menu_page("Anne's Catering","Anne's Catering",'manage_options','annesfs-settings','annesfs_settings_page','dashicons-food',56);
  add_submenu_page('annesfs-settings','Bundles','Bundles','manage_options','edit.php?post_type=annesfs_bundle');
});

function annesfs_bool($opt,$def=false){ return !!intval(get_option($opt, $def?1:0)); }

function annesfs_settings_page(){
  if ( isset($_POST['annesfs_save']) ){
    check_admin_referer('annesfs_save_settings');

    update_option('annesfs_tiers_enabled', isset($_POST['annesfs_tiers_enabled'])?1:0);
    foreach(['t1_qty','t1_disc','t2_qty','t2_disc','t3_qty','t3_disc'] as $k){
      update_option('annesfs_'.$k, sanitize_text_field($_POST['annesfs_'.$k] ?? ''));
    }
    update_option('annesfs_est_portions', wp_kses_post($_POST['annesfs_est_portions'] ?? '{\"small\":[2,4],\"medium\":[8,10],\"large\":[12,15]}'));
    update_option('annesfs_est_rice_adj', floatval($_POST['annesfs_est_rice_adj'] ?? 0.12));
    update_option('annesfs_est_show_floating', isset($_POST['annesfs_est_show_floating'])?1:0);

    update_option('annesfs_badge_classic', sanitize_text_field($_POST['annesfs_badge_classic'] ?? 'classic'));
    update_option('annesfs_badge_premium', sanitize_text_field($_POST['annesfs_badge_premium'] ?? 'premium'));
    update_option('annesfs_badge_elite', sanitize_text_field($_POST['annesfs_badge_elite'] ?? 'elite'));
    update_option('annesfs_tooltip_enabled', isset($_POST['annesfs_tooltip_enabled'])?1:0);
    update_option('annesfs_tooltip_text', wp_kses_post($_POST['annesfs_tooltip_text'] ?? 'Estimate based on hearty Filipino servings. Actual may vary.'));
    update_option('annesfs_show_guest_badges', isset($_POST['annesfs_show_guest_badges'])?1:0);

    update_option('annesfs_deposit_percent', floatval($_POST['annesfs_deposit_percent'] ?? 50));
    update_option('annesfs_delivery_fee', floatval($_POST['annesfs_delivery_fee'] ?? 25));
    update_option('annesfs_free_delivery_over', floatval($_POST['annesfs_free_delivery_over'] ?? 600));

    update_option('annesfs_request_quote_enabled', isset($_POST['annesfs_request_quote_enabled'])?1:0);

    echo '<div class="updated"><p>Settings saved.</p></div>';
  }

  $portions = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}');
  ?>
  <div class="wrap">
    <h1>Anne's Catering — Settings</h1>
    <form method="post"><?php wp_nonce_field('annesfs_save_settings'); ?>

      <h2>Dynamic Discount Tiers</h2>
      <label><input type="checkbox" name="annesfs_tiers_enabled" <?php checked(annesfs_bool('annesfs_tiers_enabled', true)); ?>> Enable tiers</label>
      <p>Tier 1: Qty ≥ <input type="number" name="annesfs_t1_qty" value="<?php echo esc_attr(get_option('annesfs_t1_qty',5)); ?>" style="width:80px"> → % <input type="number" name="annesfs_t1_disc" value="<?php echo esc_attr(get_option('annesfs_t1_disc',10)); ?>" style="width:80px"></p>
      <p>Tier 2: Qty ≥ <input type="number" name="annesfs_t2_qty" value="<?php echo esc_attr(get_option('annesfs_t2_qty',7)); ?>" style="width:80px"> → % <input type="number" name="annesfs_t2_disc" value="<?php echo esc_attr(get_option('annesfs_t2_disc',12)); ?>" style="width:80px"></p>
      <p>Tier 3: Qty ≥ <input type="number" name="annesfs_t3_qty" value="<?php echo esc_attr(get_option('annesfs_t3_qty',9)); ?>" style="width:80px"> → % <input type="number" name="annesfs_t3_disc" value="<?php echo esc_attr(get_option('annesfs_t3_disc',15)); ?>" style="width:80px"></p>

      <h2>Guest Estimator</h2>
      <p><strong>Portion JSON</strong></p>
      <textarea name="annesfs_est_portions" rows="4" class="large-text code"><?php echo esc_textarea($portions); ?></textarea>
      <p><label><input type="checkbox" name="annesfs_est_show_floating" <?php checked(annesfs_bool('annesfs_est_show_floating', true)); ?>> Show floating widget (bottom-left)</label></p>
      <p>Rice/noodle adjustment: <input type="number" name="annesfs_est_rice_adj" step="0.01" value="<?php echo esc_attr(get_option('annesfs_est_rice_adj',0.12)); ?>" style="width:100px"></p>

      <h2>Badges & Tooltips</h2>
      <p>Category slugs → badges: Classic <input type="text" name="annesfs_badge_classic" value="<?php echo esc_attr(get_option('annesfs_badge_classic','classic')); ?>" style="width:140px">
       Premium <input type="text" name="annesfs_badge_premium" value="<?php echo esc_attr(get_option('annesfs_badge_premium','premium')); ?>" style="width:140px">
       Elite <input type="text" name="annesfs_badge_elite" value="<?php echo esc_attr(get_option('annesfs_badge_elite','elite')); ?>" style="width:140px"></p>
      <p><label><input type="checkbox" name="annesfs_show_guest_badges" <?php checked(annesfs_bool('annesfs_show_guest_badges', true)); ?>> Show guest count badges on bundle titles</label></p>
      <p><label><input type="checkbox" name="annesfs_tooltip_enabled" <?php checked(annesfs_bool('annesfs_tooltip_enabled', true)); ?>> Enable tooltips</label></p>
      <p><textarea name="annesfs_tooltip_text" class="large-text code" rows="3"><?php echo esc_textarea(get_option('annesfs_tooltip_text')); ?></textarea></p>

      <h2>Payments & Delivery</h2>
      <p>Deposit percent: <input type="number" name="annesfs_deposit_percent" value="<?php echo esc_attr(get_option('annesfs_deposit_percent',50)); ?>" style="width:100px">%</p>
      <p>Delivery fee (flat): <input type="number" name="annesfs_delivery_fee" value="<?php echo esc_attr(get_option('annesfs_delivery_fee',25)); ?>" style="width:100px"></p>
      <p>Free delivery over: $<input type="number" name="annesfs_free_delivery_over" value="<?php echo esc_attr(get_option('annesfs_free_delivery_over',600)); ?>" style="width:120px"></p>

      <h2>Quote Flow</h2>
      <p><label><input type="checkbox" name="annesfs_request_quote_enabled" <?php checked(annesfs_bool('annesfs_request_quote_enabled', true)); ?>> Enable “Request a Quote” alongside checkout</label></p>

      <p><button class="button button-primary" name="annesfs_save">Save settings</button></p>
    </form>
  </div>
<?php } ?>
