<?php
if ( ! defined('ABSPATH') ) exit;

add_action('admin_menu', function(){
  add_menu_page("Anne's Catering","Anne's Catering",'manage_options','annesfs-settings','annesfs_settings_page','dashicons-food',56);
});

function annesfs_settings_page(){
  if (!current_user_can('manage_options')) return;

  if (isset($_POST['annesfs_save'])){
    check_admin_referer('annesfs_save_settings');

    // Existing settings
    update_option('annesfs_cat_classic', sanitize_text_field($_POST['annesfs_cat_classic'] ?? 'classic'));
    update_option('annesfs_cat_premium', sanitize_text_field($_POST['annesfs_cat_premium'] ?? 'premium'));
    update_option('annesfs_cat_elite',   sanitize_text_field($_POST['annesfs_cat_elite']   ?? 'elite'));
    update_option('annesfs_est_portions', wp_kses_post($_POST['annesfs_est_portions'] ?? '{"small":[2,4],"medium":[8,10],"large":[12,15]}'));

    // NEW: Discount tiers (by total quantity)
    update_option('annesfs_tier_5_qty',   intval(  $_POST['annesfs_tier_5_qty']  ?? 5));
    update_option('annesfs_tier_5_disc',  floatval($_POST['annesfs_tier_5_disc'] ?? 5));
    update_option('annesfs_tier_7_qty',   intval(  $_POST['annesfs_tier_7_qty']  ?? 7));
    update_option('annesfs_tier_7_disc',  floatval($_POST['annesfs_tier_7_disc'] ?? 8));
    update_option('annesfs_tier_10_qty',  intval(  $_POST['annesfs_tier_10_qty'] ?? 10));
    update_option('annesfs_tier_10_disc', floatval($_POST['annesfs_tier_10_disc']?? 10));

    echo '<div class="updated"><p>Saved.</p></div>';
  }

  $classic  = get_option('annesfs_cat_classic','classic');
  $premium  = get_option('annesfs_cat_premium','premium');
  $elite    = get_option('annesfs_cat_elite','elite');
  $portions = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}');

  // NEW: discount tier values
  $t5  = get_option('annesfs_tier_5_qty',  5);
  $d5  = get_option('annesfs_tier_5_disc', 5);
  $t7  = get_option('annesfs_tier_7_qty',  7);
  $d7  = get_option('annesfs_tier_7_disc', 8);
  $t10 = get_option('annesfs_tier_10_qty', 10);
  $d10 = get_option('annesfs_tier_10_disc',10);
  ?>
  <div class="wrap">
    <h1>Anne's Catering — Settings</h1>
    <form method="post">
      <?php wp_nonce_field('annesfs_save_settings'); ?>

      <h2 class="title">Badge Mapping (Category Slugs)</h2>
      <table class="form-table">
        <tr><th>Classic slug</th><td><input type="text" name="annesfs_cat_classic" value="<?php echo esc_attr($classic); ?>" class="regular-text"></td></tr>
        <tr><th>Premium slug</th><td><input type="text" name="annesfs_cat_premium" value="<?php echo esc_attr($premium); ?>" class="regular-text"></td></tr>
        <tr><th>Elite slug</th><td><input type="text" name="annesfs_cat_elite" value="<?php echo esc_attr($elite); ?>" class="regular-text"></td></tr>
      </table>

      <h2 class="title">Estimator Portions (JSON)</h2>
      <textarea name="annesfs_est_portions" rows="5" class="large-text code"><?php echo esc_textarea($portions); ?></textarea>

      <h2 class="title">Discount Tiers (by total quantity)</h2>
      <table class="form-table">
        <tr>
          <th>Tier 1</th>
          <td>
            Qty ≥ <input type="number" name="annesfs_tier_5_qty" value="<?php echo esc_attr($t5); ?>" style="width:80px;">
            &nbsp;→&nbsp; Discount % <input type="number" name="annesfs_tier_5_disc" value="<?php echo esc_attr($d5); ?>" style="width:80px;">
          </td>
        </tr>
        <tr>
          <th>Tier 2</th>
          <td>
            Qty ≥ <input type="number" name="annesfs_tier_7_qty" value="<?php echo esc_attr($t7); ?>" style="width:80px;">
            &nbsp;→&nbsp; Discount % <input type="number" name="annesfs_tier_7_disc" value="<?php echo esc_attr($d7); ?>" style="width:80px;">
          </td>
        </tr>
        <tr>
          <th>Tier 3</th>
          <td>
            Qty ≥ <input type="number" name="annesfs_tier_10_qty" value="<?php echo esc_attr($t10); ?>" style="width:80px;">
            &nbsp;→&nbsp; Discount % <input type="number" name="annesfs_tier_10_disc" value="<?php echo esc_attr($d10); ?>" style="width:80px;">
          </td>
        </tr>
      </table>

      <p><button class="button button-primary" name="annesfs_save" value="1">Save settings</button></p>
    </form>
  </div>
  <?php
}
