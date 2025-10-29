<?php
/**
 * Plugin Name: Google Places Plugin
 * Description: Meerdere Google Places functies met caching.
 * Version: 1.0
 * Author: Harm van 't Leven
 * Text Domain: google-places-plugin

 */

if ( ! defined( 'ABSPATH' ) ) exit;

define('GPLACES_VERSION', '1.0');
define('GPLACES_MAIN_FILE', plugin_basename(__FILE__));

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/shortcodes/openingstijden.php';
require_once __DIR__ . '/shortcodes/reviews.php';
require_once __DIR__ . '/shortcodes/open_now.php';
require_once __DIR__ . '/shortcodes/gloriafood_menu.php';