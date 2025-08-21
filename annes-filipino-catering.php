<?php
/**
 * Plugin Name: Anne's Filipino Catering
 * Description: One-page catering flow: bundle builder hooks, badges, quote flow, quick-view variation modal, and guest count estimator.
 * Version: 2.1.6-dev
 * Author: Anne's Filipino Sweet Spot
 * Text Domain: annesfs
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define('ANNESFS_VER', '2.1.6-dev');
define('ANNESFS_DIR', plugin_dir_path(__FILE__));
define('ANNESFS_URL', plugin_dir_url(__FILE__));

add_action('wp_enqueue_scripts', function(){
  wp_register_style('annesfs-qv', ANNESFS_URL.'assets/css/qv.css', [], ANNESFS_VER);
  wp_enqueue_style('annesfs-qv');
  wp_register_script('annesfs-qv', ANNESFS_URL.'assets/js/qv.js', ['jquery'], ANNESFS_VER, true);
  wp_enqueue_script('annesfs-qv');

  wp_register_style('annesfs-estimator', ANNESFS_URL.'assets/css/estimator.css', [], ANNESFS_VER);
  wp_enqueue_style('annesfs-estimator');
  wp_register_script('annesfs-estimator', ANNESFS_URL.'assets/js/estimator.js', [], ANNESFS_VER, true);
  wp_enqueue_script('annesfs-estimator');

  wp_localize_script('annesfs-qv','ANNESFS_AJAX',['url'=>admin_url('admin-ajax.php')]);
});

require_once ANNESFS_DIR . 'includes/settings.php';
require_once ANNESFS_DIR . 'includes/quickview.php';
require_once ANNESFS_DIR . 'includes/estimator-shortcode.php';
require_once ANNESFS_DIR . 'includes/discount-tiers.php'; // NEW for 2.1.6
