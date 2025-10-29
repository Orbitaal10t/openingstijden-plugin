<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function gplaces_openingstijden_shortcode() {
    ob_start();

    // Check if API credentials are configured
    if (empty(GPLACE_PLACE_ID) || empty(GPLACE_API_KEY)) {
        echo '<p><b>Openingstijden</b></p>';
        echo '<p style="color: red;">Configuratiefout: Google Places API instellingen zijn niet ingesteld. Ga naar WordPress Admin > Instellingen > Google Places om deze in te stellen.</p>';
        return ob_get_clean();
    }

    $cache_file = 'openingstijden_cache.json';
    $openingstijden = gplaces_get_cache($cache_file);

    if (!$openingstijden) {
        $url = "https://places.googleapis.com/v1/places/" . GPLACE_PLACE_ID . "?fields=id,displayName,currentOpeningHours&languageCode=nl&key=" . GPLACE_API_KEY;
        $data = gplaces_fetch_api($url);

        // Log the response for debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Google Places API Response: ' . print_r($data, true));
        }

        if ($data && isset($data['currentOpeningHours']['weekdayDescriptions'])) {
            $openingstijden = $data['currentOpeningHours']['weekdayDescriptions'];
            gplaces_set_cache($cache_file, $openingstijden);
        }
        else {
            // Better error handling
            $error_msg = 'Fout bij laden openingstijden.';
            if (isset($data['error'])) {
                $error_msg .= ' API Fout: ' . $data['error']['message'];
                error_log('Google Places API Error: ' . print_r($data['error'], true));
            } elseif (!$data) {
                $error_msg .= ' Geen verbinding met Google Places API.';
            } else {
                $error_msg .= ' Onverwachte API response structuur.';
                error_log('Google Places API unexpected structure: ' . print_r($data, true));
            }
            $openingstijden = [$error_msg];
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
