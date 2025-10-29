<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function gplaces_open_now_shortcode() {
    ob_start();

    try {
        $cache_file = 'openingstijden_cache.json';
        $data = gplaces_get_cache($cache_file);

        if (!$data || !is_array($data)) {
            $url = "https://places.googleapis.com/v1/places/" . GPLACE_PLACE_ID . 
                   "?fields=id,displayName,currentOpeningHours&languageCode=nl&key=" . GPLACE_API_KEY;

            $api_data = gplaces_fetch_api($url);

            if ($api_data && isset($api_data['currentOpeningHours']['periods']) && is_array($api_data['currentOpeningHours']['periods'])) {
                $data = $api_data['currentOpeningHours']['periods'];
                gplaces_set_cache($cache_file, $data);
            } else {
                throw new Exception('Fout bij laden openingstijden.');
            }
        }

        $now = new DateTime('now', new DateTimeZone('Europe/Amsterdam'));
        $current_day = (int)$now->format('N'); // 1=maandag .. 7=zondag
        $current_time = $now->format('Hi');

        $is_open = false;
        $next_open_time = null;

        // Helper om Google dagen (0=zo) naar PHP N (1=ma) te zetten
        $googleDayToPHP = function($d) {
            return $d == 0 ? 7 : $d;
        };

        foreach ($data as $periode) {
            if (!isset($periode['open']['day'], $periode['open']['time'])) continue;

            $open_day = $googleDayToPHP($periode['open']['day']);
            $open_time = $periode['open']['time'];
            $close_day = isset($periode['close']['day']) ? $googleDayToPHP($periode['close']['day']) : $open_day;
            $close_time = isset($periode['close']['time']) ? $periode['close']['time'] : '2359';

            // Controleer of nu open
            if (
                ($current_day > $open_day || ($current_day == $open_day && $current_time >= $open_time)) &&
                ($current_day < $close_day || ($current_day == $close_day && $current_time < $close_time))
            ) {
                $is_open = true;
                break;
            }

            // Bereken de openingstijd voor deze periode
            $days_ahead = ($open_day - $current_day + 7) % 7;
            $open_dt = (clone $now)->modify("+$days_ahead day");
            $open_dt->setTime(
                (int)substr($open_time, 0, 2), 
                (int)substr($open_time, 2, 2)
            );

            // Als deze opening na nu ligt en nog geen volgende is gekozen, sla deze op
            if ($open_dt > $now && (!$next_open_time || $open_dt < $next_open_time)) {
                $next_open_time = $open_dt;
            }
        }

        if ($is_open) {
            echo '<span class="open-status open">We zijn nu open!</span>';
        } else {
            $formatted_time = $next_open_time ? strftime('%A %H:%M', $next_open_time->getTimestamp()) : 'onbekend';
            echo '<span class="open-status closed">We zijn momenteel gesloten. We gaan weer open op ' . esc_html($formatted_time) . '</span>';
        }

    } catch (Exception $e) {
        echo '<span class="open-status error">Openingstijden zijn momenteel niet beschikbaar.</span>';
        error_log('gplaces_open_now_shortcode fout: ' . $e->getMessage());
    }

    return ob_get_clean();
}
add_shortcode('open_now', 'gplaces_open_now_shortcode');
