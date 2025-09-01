<?php
/**
 * Plugin Name: Anne's Filipino Catering
 * Description: Bundles, quick-view modal, guest estimator, badges, deposit & delivery, request-a-quote, dynamic discount tiers, and frontend polish.
 * Version: 2.2.2
 * Author: Anne's Filipino Sweet Spot
 * Text Domain: annesfs
 */
if ( ! defined('ABSPATH') ) exit;

define('ANNESFS_VER','2.2.2');
define('ANNESFS_DIR', plugin_dir_path(__FILE__));
define('ANNESFS_URL', plugin_dir_url(__FILE__));

add_action('wp_enqueue_scripts', function(){
  wp_enqueue_style('annesfs-core',  ANNESFS_URL.'assets/css/core.css',  [], ANNESFS_VER);
  wp_enqueue_style('annesfs-polish',ANNESFS_URL.'assets/css/polish.css',['annesfs-core'], ANNESFS_VER);

  wp_enqueue_script('annesfs-qv',       ANNESFS_URL.'assets/js/qv.js',       ['jquery'], ANNESFS_VER, true);
  wp_enqueue_script('annesfs-estimator',ANNESFS_URL.'assets/js/estimator.js',[],          ANNESFS_VER, true);
  wp_enqueue_script('annesfs-bundles',  ANNESFS_URL.'assets/js/bundles.js',  ['jquery'], ANNESFS_VER, true);

  wp_localize_script('annesfs-bundles','ANNESFS_BUNDLES', ['ajax'=>admin_url('admin-ajax.php')]);
  wp_localize_script('annesfs-qv',     'ANNESFS_QV',      ['ajax'=>admin_url('admin-ajax.php')]);
});

require_once ANNESFS_DIR.'includes/settings.php';
require_once ANNESFS_DIR.'includes/quickview.php';
require_once ANNESFS_DIR.'includes/estimator-shortcode.php';
require_once ANNESFS_DIR.'includes/estimator-cart.php';
require_once ANNESFS_DIR.'includes/discount-tiers.php';
require_once ANNESFS_DIR.'includes/bundles-cpt.php';
require_once ANNESFS_DIR.'includes/bundles-frontend.php';
require_once ANNESFS_DIR.'includes/quote-flow.php';
require_once ANNESFS_DIR.'includes/floating-widget.php';
require_once ANNESFS_DIR.'includes/github-updater.php';
