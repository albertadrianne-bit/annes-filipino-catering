<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Show category-based badges on product cards and single pages.
 * Uses slugs from Settings: classic / premium / elite.
 */

function annesfs_get_badge_html( $term_slugs ) {
  $classic = get_option('annesfs_badge_classic','classic');
  $premium = get_option('annesfs_badge_premium','premium');
  $elite   = get_option('annesfs_badge_elite','elite');

  $map = [
    $classic => ['Classic','#6b7280'],
    $premium => ['Premium','#b45309'],
    $elite   => ['Elite','#065f46'],
  ];

  foreach ($term_slugs as $slug) {
    if ( isset($map[$slug]) ) {
      [$label,$color] = $map[$slug];
      return '<span class="annesfs-badge" style="display:inline-block;margin-left:8px;padding:2px 8px;border-radius:999px;background:'.$color.';color:#fff;font-size:12px;line-height:1;">'.$label.'</span>';
    }
  }
  return '';
}

/** On catalog cards (loop). */
add_action('woocommerce_after_shop_loop_item_title', function(){
  global $product; if(! $product) return;
  $terms = get_the_terms($product->get_id(),'product_cat') ?: [];
  $slugs = array_map(fn($t)=>$t->slug,$terms);
  echo annesfs_get_badge_html($slugs);
}, 15);

/** On single product title area. */
add_filter('the_title', function($title,$id){
  if ( get_post_type($id) !== 'product' ) return $title;
  $terms = get_the_terms($id,'product_cat') ?: [];
  $slugs = array_map(fn($t)=>$t->slug,$terms);
  return $title . annesfs_get_badge_html($slugs);
}, 10, 2);
