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
            <h2 class="title">Checkout & Payments</h2>
      <?php $deposit = get_option('annesfs_deposit_percent', 50); ?>
      <table class="form-table">
        <tr>
          <th>Deposit percent</th>
          <td>
            <input type="number" min="0" max="100" step="1" name="annesfs_deposit_percent" value="<?php echo esc_attr($deposit); ?>" style="width:90px;"> %
            <p class="description">Shown in checkout summary and used in deposit math.</p>
          </td>
        </tr>
      </table>

      <h2 class="title">Order Minimums & Delivery</h2>
      <?php
        $min_pickup   = get_option('annesfs_min_pickup', 50);
        $min_delivery = get_option('annesfs_min_delivery', 150);
        $del_fee      = get_option('annesfs_delivery_fee', 25);
        $free_over    = get_option('annesfs_free_delivery_over', 600);
        $kitchen_zip  = get_option('annesfs_kitchen_zip', '85392');
        $deliver_radius = get_option('annesfs_delivery_radius_miles', 10);
        $zip_allow    = get_option('annesfs_zip_allowlist', ''); // comma-separated
      ?>
      <table class="form-table">
        <tr>
          <th>Pickup minimum ($)</th>
          <td><input type="number" step="1" name="annesfs_min_pickup" value="<?php echo esc_attr($min_pickup); ?>" style="width:120px;"></td>
        </tr>
        <tr>
          <th>Delivery minimum ($)</th>
          <td><input type="number" step="1" name="annesfs_min_delivery" value="<?php echo esc_attr($min_delivery); ?>" style="width:120px;"></td>
        </tr>
        <tr>
          <th>Delivery fee (flat, $)</th>
          <td><input type="number" step="1" name="annesfs_delivery_fee" value="<?php echo esc_attr($del_fee); ?>" style="width:120px;"></td>
        </tr>
        <tr>
          <th>Free delivery over ($)</th>
          <td><input type="number" step="1" name="annesfs_free_delivery_over" value="<?php echo esc_attr($free_over); ?>" style="width:120px;"></td>
        </tr>
        <tr>
          <th>Kitchen ZIP (origin)</th>
          <td><input type="text" name="annesfs_kitchen_zip" value="<?php echo esc_attr($kitchen_zip); ?>" style="width:140px;"></td>
        </tr>
        <tr>
          <th>Delivery radius (miles)</th>
          <td><input type="number" step="1" name="annesfs_delivery_radius_miles" value="<?php echo esc_attr($deliver_radius); ?>" style="width:120px;">
            <p class="description">Used if allow‑list below is empty.</p>
          </td>
        </tr>
        <tr>
          <th>ZIP allow‑list (optional)</th>
          <td>
            <textarea name="annesfs_zip_allowlist" rows="3" class="large-text code" placeholder="85392, 85323, 85037"><?php echo esc_textarea($zip_allow); ?></textarea>
            <p class="description">If provided, only these ZIPs are eligible for delivery.</p>
          </td>
        </tr>
      </table>

      <h2 class="title">Quotes & UI</h2>
      <?php
        $quote_emails   = get_option('annesfs_quote_recipients', get_option('admin_email'));
        $quote_log      = get_option('annesfs_quote_log_admin', 1);
        $ui_quote_btn   = get_option('annesfs_ui_show_quote', 1);
        $ui_offcanvas   = get_option('annesfs_ui_offcanvas_cart', 1);
        $thumb_size     = get_option('annesfs_minicart_thumb', 56);
      ?>
      <table class="form-table">
        <tr>
          <th>Quote notification emails</th>
          <td>
            <input type="text" name="annesfs_quote_recipients" value="<?php echo esc_attr($quote_emails); ?>" class="regular-text">
            <p class="description">Comma‑separated list. Default is your admin email.</p>
          </td>
        </tr>
        <tr>
          <th>Log quotes in WP Admin</th>
          <td><label><input type="checkbox" name="annesfs_quote_log_admin" value="1" <?php checked($quote_log,1); ?>> Enable</label></td>
        </tr>
        <tr>
          <th>Show “Request a Quote” button</th>
          <td><label><input type="checkbox" name="annesfs_ui_show_quote" value="1" <?php checked($ui_quote_btn,1); ?>> Enable</label></td>
        </tr>
        <tr>
          <th>Use off‑canvas cart</th>
          <td><label><input type="checkbox" name="annesfs_ui_offcanvas_cart" value="1" <?php checked($ui_offcanvas,1); ?>> Enable</label></td>
        </tr>
        <tr>
          <th>Mini‑cart image size (px)</th>
          <td><input type="number" step="1" name="annesfs_minicart_thumb" value="<?php echo esc_attr($thumb_size); ?>" style="width:120px;"></td>
        </tr>
      </table>
    </form>
  </div>
  <?php
}
