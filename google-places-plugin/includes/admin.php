<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Voeg admin menu toe
 */
function gplaces_add_admin_menu() {
    add_options_page(
        'Google Places & GloriaFood Instellingen',
        'Google Places',
        'manage_options',
        'gplaces-settings',
        'gplaces_settings_page'
    );
}
add_action('admin_menu', 'gplaces_add_admin_menu');

/**
 * Registreer instellingen
 */
function gplaces_register_settings() {
    register_setting('gplaces_settings_group', 'gplaces_api_key', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ]);

    register_setting('gplaces_settings_group', 'gplaces_place_id', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ]);

    register_setting('gplaces_settings_group', 'gfood_api_key', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ]);
}
add_action('admin_init', 'gplaces_register_settings');

/**
 * Admin pagina HTML
 */
function gplaces_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Toon succes bericht na opslaan
    if (isset($_GET['settings-updated'])) {
        add_settings_error(
            'gplaces_messages',
            'gplaces_message',
            'Instellingen opgeslagen',
            'updated'
        );
    }

    settings_errors('gplaces_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('gplaces_settings_group');
            ?>

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="gplaces_api_key">Google Places API Key</label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gplaces_api_key"
                                name="gplaces_api_key"
                                value="<?php echo esc_attr(get_option('gplaces_api_key', '')); ?>"
                                class="regular-text"
                            />
                            <p class="description">
                                Jouw Google Places API key.
                                <a href="https://developers.google.com/maps/documentation/places/web-service/get-api-key" target="_blank">
                                    Hoe krijg ik een API key?
                                </a>
                            </p>
                            <p class="description" style="color: #d63638;">
                                <strong>Belangrijk:</strong> In Google Cloud Console moet je bij de API key restrictions
                                jouw website domein toevoegen onder "HTTP referrers".
                                Voeg toe: <code><?php echo esc_html(parse_url(home_url(), PHP_URL_HOST)); ?>/*</code>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="gplaces_place_id">Google Place ID</label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gplaces_place_id"
                                name="gplaces_place_id"
                                value="<?php echo esc_attr(get_option('gplaces_place_id', '')); ?>"
                                class="regular-text"
                            />
                            <p class="description">
                                Jouw Google Place ID (bijv. ChIJ250pzsCJxEcRnUI36GnDTuA).
                                <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank">
                                    Hoe vind ik mijn Place ID?
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="gfood_api_key">GloriaFood API Key</label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gfood_api_key"
                                name="gfood_api_key"
                                value="<?php echo esc_attr(get_option('gfood_api_key', '')); ?>"
                                class="regular-text"
                            />
                            <p class="description">
                                Jouw GloriaFood API key voor het ophalen van menu's.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php submit_button('Instellingen opslaan'); ?>
        </form>

        <hr>

        <h2>Shortcodes</h2>
        <p>Gebruik de volgende shortcodes in jouw WordPress pagina's:</p>
        <ul style="list-style: disc; margin-left: 20px;">
            <li><code>[openingstijden]</code> - Toont de openingstijden</li>
            <li><code>[open_now]</code> - Toont of je zaak nu open is</li>
            <li><code>[gplaces_reviews]</code> - Toont Google reviews</li>
            <li><code>[gloriafood_menu]</code> - Toont het GloriaFood menu</li>
        </ul>
    </div>
    <?php
}
