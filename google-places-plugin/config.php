<?php
// Bescherm tegen directe toegang
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Haal API keys op uit WordPress opties (ingesteld via admin pagina)
 * BELANGRIJK: Stel deze in via WordPress Admin > Instellingen > Google Places
 */
define('GPLACE_PLACE_ID', get_option('gplaces_place_id', ''));
define('GPLACE_API_KEY', get_option('gplaces_api_key', ''));
define('GFOOD_API_KEY', get_option('gfood_api_key', ''));