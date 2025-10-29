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
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return false;
    return json_decode(wp_remote_retrieve_body($response), true);
}