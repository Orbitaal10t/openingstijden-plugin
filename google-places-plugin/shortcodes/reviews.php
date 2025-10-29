<?php
/**
 * Shortcode: [gplaces_reviews]
 * Toont Google Places reviews met caching.
 */

function gplaces_reviews_shortcode() {
    ob_start();

    $upload_dir   = wp_upload_dir();
    $cache_file   = trailingslashit($upload_dir['basedir']) . 'gplaces_reviews_cache.json';
    $cache_time   = 3600; // 1 uur
    $placeId      = defined('GPLACE_PLACE_ID') ? GPLACE_PLACE_ID : '';
    $apiKey       = defined('GPLACE_API_KEY') ? GPLACE_API_KEY : '';

    if (empty($placeId) || empty($apiKey)) {
        return '<p>Google Place ID of API Key niet ingesteld.</p>';
    }

    // Check cache
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
        $reviews = json_decode(file_get_contents($cache_file), true);
    } else {
        $url      = "https://places.googleapis.com/v1/places/$placeId?fields=reviews&key=$apiKey";
        $response = wp_remote_get($url);

        if (!is_wp_error($response)) {
            $data    = json_decode(wp_remote_retrieve_body($response), true);
            $reviews = $data['reviews'] ?? [];

            // Opslaan in cache
            file_put_contents($cache_file, json_encode($reviews));
        } else {
            $reviews = [];
        }
    }

    if (empty($reviews)) {
        echo '<p>Geen reviews gevonden.</p>';
    } else {
        echo '<ul class="gplaces-reviews">';
        foreach ($reviews as $review) {
            $author  = $review['authorName'] ?? 'Onbekend';
            $text    = $review['text'] ?? '';
            $rating  = $review['starRating'] ?? '';
            $time    = isset($review['createTime']) ? date_i18n(get_option('date_format'), strtotime($review['createTime'])) : '';

            // Als text per ongeluk een array is, omzetten naar string
            if (is_array($text)) {
                $text = json_encode($text);
            }

            echo '<li>';
            echo '<strong>' . esc_html($author) . '</strong>';
            if ($rating) echo ' – ' . esc_html($rating) . '/5';
            if ($time) echo ' (' . esc_html($time) . ')';
            echo '<br>' . esc_html($text);
            echo '</li>';
        }
        echo '</ul>';
    }

    return ob_get_clean();
}
add_shortcode('gplaces_reviews', 'gplaces_reviews_shortcode');