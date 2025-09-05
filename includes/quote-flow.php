<?php
if ( ! defined('ABSPATH') ) exit;
add_action('woocommerce_proceed_to_checkout', function(){
  if (! intval(get_option('annesfs_request_quote_enabled',1)) ) return;
  echo '<a href="'.esc_url(wc_get_cart_url()).'?annesfs_quote=1" class="button wc-forward annesfs-btn-quote" style="margin-left:8px;">Request a Quote</a>';
}, 25);
