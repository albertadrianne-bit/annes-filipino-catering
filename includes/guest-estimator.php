<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Helper functions used by estimator blocks/widgets.
 * Keeps logic independent from shortcode/layout code.
 */

function annesfs_get_portion_map(){
  $json = get_option('annesfs_est_portions','{"small":[2,4],"medium":[8,10],"large":[12,15]}');
  $data = json_decode($json,true);
  if(!is_array($data)) $data = ['small'=>[2,4],'medium'=>[8,10],'large'=>[12,15]];
  // we use the LOWER bound for conservative minimum feeds
  return [
    'small'  => intval($data['small'][0]  ?? 2),
    'medium' => intval($data['medium'][0] ?? 8),
    'large'  => intval($data['large'][0]  ?? 12),
  ];
}

/** Public helper: estimate guests given a cart line breakdown. */
function annesfs_estimate_guests_from_counts( $counts ){
  $p = annesfs_get_portion_map();
  $s = intval($counts['small']  ?? 0)  * $p['small'];
  $m = intval($counts['medium'] ?? 0)  * $p['medium'];
  $l = intval($counts['large']  ?? 0)  * $p['large'];
  return max(0, $s+$m+$l);
}
