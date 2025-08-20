<?php
if ( ! defined('ABSPATH') ) exit;
add_action('admin_menu', function(){
  add_menu_page("Anne's Catering","Anne's Catering",'manage_options','annesfs-settings','annesfs_settings_page','dashicons-food',56);
});
function annesfs_settings_page(){
  if (!current_user_can('manage_options')) return;
  if (isset($_POST['annesfs_save'])){
    check_admin_referer('annesfs_save_settings');
    update_option('annesfs_cat_classic', sanitize_text_field($_POST['annesfs_cat_classic'] ?? 'classic'));
    update_option('annesfs_cat_premium', sanitize_text_field($_POST['annesfs_cat_premium'] ?? 'premium'));
    update_option('annesfs_cat_elite', sanitize_text_field($_POST['annesfs_cat_elite'] ?? 'elite'));
    update_option('annesfs_est_portions', wp_kses_post($_POST['annesfs_est_portions'] ?? '{"small":[2,4],"medium":[8,10],"large":[12,15]}'));
    echo '<div class="updated"><p>Saved.</p></div>';
  }
  $classic = get_option('annesfs_cat_classic','classic');
  $premium = get_option('annesfs_cat_premium','premium');
  $elite   = get_option('annesfs_cat_elite','elite');
  $portions = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}');
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
      <p><button class="button button-primary" name="annesfs_save" value="1">Save settings</button></p>
    </form>
  </div>
  <?php
}
