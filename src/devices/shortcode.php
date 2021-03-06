<?php

if (!defined('ABSPATH')) {
    exit;
}


class InstaloginDevicesShortcode
{
    public function __construct()
    {
        add_shortcode('insta-devices', function ($attributes = [], $content = null) {
            // API disabled via settings?
            // $api_enabled = get_option('instalogin-api-enabled');
            // if ($api_enabled != 1) {
            //     return '';
            // }

            // SETTINGS
            $attributes = shortcode_atts([], $attributes, 'instalogin-devices');

            if (!is_user_logged_in()) {
                // return '<div>' . __('You must be logged in to manage devices.', 'instalogin-me') . '</div>';
                return;
            }

            // SCRIPTS

            wp_enqueue_style('instalogin-login', plugin_dir_url(__FILE__) . '../../style/form.css?v=3');

            $user = wp_get_current_user();
            $email = $user->user_email;
            $user_id = $user->ID;

            wp_enqueue_script('instalogin-devices', plugin_dir_url(__FILE__) . "../../scripts/devices.js", ['wp-i18n'], '1', true);
            wp_localize_script('instalogin-devices', 'wpv', [
                'is_frontend' => true,
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'show_activation' => false,
                'email' => $email,
            ]);

            wp_enqueue_script('instalogin-send-mail', plugin_dir_url(__FILE__) . "../../scripts/device-send-mail.js", ['wp-i18n'], '1', true);
            wp_localize_script('instalogin-send-mail', 'wpv_mail', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'user_id' => $user_id,
            ]);

            // RENDER
            ob_start(); ?>
            <div>
                <div class="instalogin-devices">

                    <span class="instalogin-devices-title"><?php _e("Connected Devices", 'instalogin-me') ?></span>
                    <hr class="instalogin-devices-hr">

                    <ul class="instalogin-device-list">

                    </ul>
                    <button class="instalogin-device-button instalogin-refresh"><?php _e("Refresh", 'instalogin-me') ?></button>
                    <button class="instalogin-device-button instalogin-add-device"><?php _e("Add Device", 'instalogin-me') ?></button>
                </div>
            </div>
<?php

            return ob_get_clean();
        });
    }
}
