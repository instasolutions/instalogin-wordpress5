<?php

if (!defined('ABSPATH')) {
    exit;
}


class InstaloginLoginShortcode
{
    public function __construct()
    {
        add_shortcode('insta-login', function ($attributes = [], $content = null) {
            // API disabled via settings?
            $api_enabled = get_option('instalogin-api-enabled');
            if ($api_enabled != 1) {
                return '';
            }

            // SETTINGS
            $attributes = shortcode_atts([
                'size' => '100px',
                'show_when_logged_in' => "false",
                'border' => "false",
            ], $attributes, 'instalogin-login-code');

            $size = $attributes['size'];
            $showWhenLoggedIn = $attributes['show_when_logged_in'] == 'true';
            $border = $attributes['border'] == 'true';

            if (!$showWhenLoggedIn && is_user_logged_in()) {
                return '';
            }

            // SCRIPTS
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $api_key = get_option('instalogin-api-key');
            $display_type = get_option('instalogin-api-type', 'qr');
            wp_enqueue_script('instalogin-qr-widget', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api']);
            wp_localize_script('instalogin-qr-widget', 'api_key', $api_key);
            wp_localize_script('instalogin-qr-widget', 'display_type', $display_type);

            // TOOD: if key unset, display error message


            // RENDER
            ob_start(); ?>
            <style>
                #instalogin .instalogin-container {
                    border: <?= $border ? ' 1px solid rgb(200, 200, 200);' : ' none !important;' ?>;
                    width: <?= $size ?>;
                }

                #instalogin .instalogin-image {
                    width: <?= $size ?>;
                }
            </style>

            <div id="instalogin"></div>
<?php

            return ob_get_clean();
        });
    }
}
