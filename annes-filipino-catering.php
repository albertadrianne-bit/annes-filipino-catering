<?php
/*
Plugin Name: Anne's Filipino Catering
Description: Custom WooCommerce extensions for catering bundles, quote flow, badges, deposit logic, and more.
Version: 2.1.5
Author: AV AP
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define('AFC_PATH', plugin_dir_path(__FILE__));
define('AFC_URL', plugin_dir_url(__FILE__));

// Includes
require_once AFC_PATH . 'includes/settings.php';
require_once AFC_PATH . 'includes/badges.php';
require_once AFC_PATH . 'includes/bundles.php';
require_once AFC_PATH . 'includes/checkout-modal.php';
require_once AFC_PATH . 'includes/guest-estimator.php';
?>
