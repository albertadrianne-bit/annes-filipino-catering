<?php
if ( ! defined('ABSPATH') ) exit;

add_shortcode('annesfs_guest_estimator', function($atts){
  $json = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}');
  $tooltip = get_option('annesfs_tooltip_text','');
  $show_tip = intval(get_option('annesfs_tooltip_enabled',1));
  ob_start(); ?>
  <div class="annesfs-ge" data-servings='<?php echo esc_attr($json); ?>' data-modes='{"light":1.0,"standard":1.2,"hearty":1.4}' data-buffer="0.10" data-riceadj="<?php echo esc_attr(get_option('annesfs_est_rice_adj',0.12)); ?>">
    <div class="annesfs-ge__row"><label>Guests</label><input type="number" min="1" step="1" class="annesfs-ge__input" id="ge-guests" placeholder="e.g. 40"></div>
    <div class="annesfs-ge__row"><label>Meal style</label><select id="ge-mode" class="annesfs-ge__select"><option value="hearty">Hearty (Filipino)</option><option value="standard">Standard</option><option value="light">Light</option></select></div>
    <div class="annesfs-ge__row"><label><input type="checkbox" id="ge-rice"> Include rice/noodles</label></div>
    <button type="button" class="annesfs-ge__btn" id="ge-calc">Get Recommendations</button>
    <div class="annesfs-ge__result" id="ge-result" style="display:none;">
      <div class="annesfs-ge__summary"></div>
      <div class="annesfs-ge__bundle"></div>
      <?php if($show_tip): ?><div class="annesfs-tip" data-annesfs-tip="<?php echo esc_attr($tooltip); ?>">ℹ️</div><?php endif; ?>
    </div>
  </div><?php
  return ob_get_clean();
});
