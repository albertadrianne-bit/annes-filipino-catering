<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin Menu for Anne’s Catering
 */
add_action('admin_menu', function () {
  // Top-level menu
  add_menu_page(
    "Anne's Catering",
    "Anne's Catering",
    'manage_options',
    'annesfs-settings',
    'annesfs_settings_page',
    'dashicons-food',
    56
  );

  // Settings (first submenu so it doesn’t duplicate Bundles)
  add_submenu_page(
    'annesfs-settings',
    "Anne's Catering — Settings",
    'Settings',
    'manage_options',
    'annesfs-settings',
    'annesfs_settings_page'
  );

  // Bundles CPT
  add_submenu_page(
    'annesfs-settings',
    'Bundles',
    'Bundles',
    'manage_options',
    'edit.php?post_type=annesfs_bundle'
  );

  // Optional: backup entry under WooCommerce
  add_submenu_page(
    'woocommerce',
    "Anne's Catering — Settings",
    "Anne's Catering",
    'manage_options',
    'annesfs-settings',
    'annesfs_settings_page'
  );
});


/**
 * Register settings
 */
add_action('admin_init', function () {
  register_setting('annesfs_settings_group', 'annesfs_badge_mapping');
  register_setting('annesfs_settings_group', 'annesfs_estimator_portions');
  register_setting('annesfs_settings_group', 'annesfs_discount_tiers');
});


/**
 * Settings Page
 */
function annesfs_settings_page() {
  if ( ! current_user_can('manage_options') ) return;

  // Load existing values
  $badges   = get_option('annesfs_badge_mapping', [
    'classic' => 'classic',
    'premium' => 'premium',
    'elite'   => 'elite',
  ]);

  $portions = get_option('annesfs_estimator_portions', [
    'small'  => [2,4],
    'medium' => [8,10],
    'large'  => [12,15],
  ]);

  $tiers = get_option('annesfs_discount_tiers', [
    ['qty' => 5, 'discount' => 5],
    ['qty' => 7, 'discount' => 8],
    ['qty' => 10, 'discount' => 10],
  ]);
  ?>
  <div class="wrap">
    <h1>Anne’s Catering — Settings</h1>
    <form method="post" action="options.php">
      <?php settings_fields('annesfs_settings_group'); ?>

      <h2>Badge Mapping (Category Slugs)</h2>
      <table class="form-table">
        <tr>
          <th>Classic slug</th>
          <td><input type="text" name="annesfs_badge_mapping[classic]" value="<?php echo esc_attr($badges['classic']); ?>" /></td>
        </tr>
        <tr>
          <th>Premium slug</th>
          <td><input type="text" name="annesfs_badge_mapping[premium]" value="<?php echo esc_attr($badges['premium']); ?>" /></td>
        </tr>
        <tr>
          <th>Elite slug</th>
          <td><input type="text" name="annesfs_badge_mapping[elite]" value="<?php echo esc_attr($badges['elite']); ?>" /></td>
        </tr>
      </table>

      <h2>Estimator Portions (JSON)</h2>
      <textarea name="annesfs_estimator_portions" rows="4" cols="60"><?php echo esc_textarea(json_encode($portions)); ?></textarea>
      <p class="description">Format: {"small":[2,4],"medium":[8,10],"large":[12,15]}</p>

      <h2>Discount Tiers (by total quantity)</h2>
      <table class="form-table">
        <?php foreach ($tiers as $i => $tier): ?>
        <tr>
          <th>Tier <?php echo $i+1; ?></th>
          <td>
            Qty ≥ <input type="number" name="annesfs_discount_tiers[<?php echo $i; ?>][qty]" value="<?php echo esc_attr($tier['qty']); ?>" style="width:80px;" />
            → Discount % <input type="number" name="annesfs_discount_tiers[<?php echo $i; ?>][discount]" value="<?php echo esc_attr($tier['discount']); ?>" style="width:80px;" />
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

      <?php submit_button('Save settings'); ?>
    </form>
  </div>
  <?php
}
