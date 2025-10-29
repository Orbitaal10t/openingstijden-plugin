<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function gplaces_get_cache($filename, $duration = 3600) {
    $file = WP_CONTENT_DIR . '/' . $filename;

    if (file_exists($file) && (time() - filemtime($file)) < $duration) {
        return json_decode(file_get_contents($file), true);
    }
    return false;
}

function gplaces_set_cache($filename, $data) {
    $file = WP_CONTENT_DIR . '/' . $filename;
    file_put_contents($file, json_encode($data));
}

function gplaces_fetch_api($url) {
    // Stuur referrer header mee voor API key validatie
    $args = array(
        'timeout' => 15,
        'headers' => array(
            'Referer' => home_url()
        )
    );

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
        error_log('Google Places API WP Error: ' . $response->get_error_message());
        return false;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        error_log('Google Places API HTTP Error: ' . $response_code);
    }

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}