<?php
if ( ! defined('ABSPATH') ) exit;
add_shortcode('annesfs_guest_estimator', function($atts){
  $json = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}');
  ob_start(); ?>
  <div class="annesfs-ge"
       data-servings='<?php echo esc_attr($json); ?>'
       data-modes='{"light":1.0,"standard":1.3,"hearty":1.7}'
       data-buffer="0.10">
    <div class="annesfs-ge__row">
      <label>Guests</label>
      <input type="number" min="1" step="1" class="annesfs-ge__input" id="ge-guests" placeholder="e.g. 25">
    </div>
    <div class="annesfs-ge__row">
      <label>Meal style</label>
      <select id="ge-mode" class="annesfs-ge__select">
        <option value="hearty">Hearty (Filipino Party)</option>
        <option value="standard">Standard</option>
        <option value="light">Light Bites</option>
      </select>
    </div>
    <button type="button" class="annesfs-ge__btn" id="ge-calc">Get Recommendations</button>
    <div class="annesfs-ge__result" id="ge-result" style="display:none;">
      <div class="annesfs-ge__summary"></div>
      <p class="annesfs-ge__note">Rice is not counted in tray totals. Add rice separately.</p>
      <div class="annesfs-ge__actions">
        <button type="button" class="annesfs-ge__btn-secondary" id="ge-copy">Copy plan</button>
        <a href="#bundle" class="annesfs-ge__link" id="ge-bundle">Build a Bundle</a>
        <a href="#quote" class="annesfs-ge__link" id="ge-quote">Request a Quote</a>
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
});
