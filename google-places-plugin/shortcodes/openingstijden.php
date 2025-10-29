<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

function gplaces_openingstijden_shortcode() { 
    ob_start(); 

    $cache_file = 'openingstijden_cache.json'; 
    $openingstijden = gplaces_get_cache($cache_file); 
    if (!$openingstijden) { $url = "https://places.googleapis.com/v1/places/" . GPLACE_PLACE_ID . "?fields=id,displayName,currentOpeningHours&languageCode=nl&key=" . GPLACE_API_KEY; 
    $data = gplaces_fetch_api($url); 
    if ($data && isset($data['currentOpeningHours']['weekdayDescriptions'])) { 
        $openingstijden = $data['currentOpeningHours']['weekdayDescriptions']; gplaces_set_cache($cache_file, $openingstijden); 
    } 
    else { 
        $openingstijden = ['Fout bij laden openingstijden.']; 
    } 
}
// Titel toevoegen boven de lijst
echo '<p><b>Openingstijden</b></p>';

foreach ($openingstijden as $dag) {
    echo '<p>' . esc_html($dag) . '</p>';
}
 

return ob_get_clean(); 
} 
add_shortcode('openingstijden', 'gplaces_openingstijden_shortcode');