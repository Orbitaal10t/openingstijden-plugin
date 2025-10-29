<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

function gloriafood_menu_shortcode() {
    ob_start();

    // Check of API key is ingesteld
    if (empty(GFOOD_API_KEY)) {
        return '<p>GloriaFood API Key niet ingesteld. Ga naar Instellingen > Google Places om deze in te stellen.</p>';
    }

    $url = 'https://pos.globalfoodsoft.com/pos/menu';
    $headers = [
        'Authorization' => GFOOD_API_KEY,
        'Accept' => 'application/json',
        'Glf-Api-Version' => '2'
    ];

    $response = wp_remote_get($url, ['headers' => $headers]);
    $menu = [];

    if (!is_wp_error($response)) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($data['categories'])) {
            $menu = $data['categories'];
        }
    }

    // Toon menu in HTML-formaat met Elementor-styling
    foreach ($menu as $category) {
        echo '<ul class="elementor-price-list">';
        foreach ($category['items'] as $item) {
            $desc = !empty($item['description']) ? esc_html($item['description']) : '';
            // Prijs kan meerdere formaten hebben, hier simpel als voorbeeld één prijs
            $price = esc_html($item['price']);

            echo '<li class="elementor-price-list-item">';
            echo '<div class="elementor-price-list-text">';
            echo '<div class="elementor-price-list-header">';
            echo '<span class="elementor-price-list-title">' . esc_html($item['name']) . '</span>';
            echo '<span class="elementor-price-list-separator"></span>';
            echo '<span class="elementor-price-list-price">€ ' . $price . '</span>';
            echo '</div>'; // header
            if ($desc) {
                echo '<span class="elementor-price-list-description">' . $desc . '</span>';
            }
            echo '</div>'; // text
            echo '</li>';
        }
        echo '</ul>';
    }

    return ob_get_clean(); 
} 
add_shortcode('gloriafood_menu', 'gloriafood_menu_shortcode');