<?php
if ( ! defined('ABSPATH') ) exit;
add_action('wp_footer', function(){
  if ( ! intval(get_option('annesfs_est_show_floating',1)) ) return;
  echo do_shortcode('[annesfs_guest_estimator]');
});
