<?php

class InstaloginLoginSnippet
{
    public function __construct()
    {
        add_shortcode('instalogin-login-code', function ($attributes = [], $content = null) {
            // API disabled via settings?
            $api_enabled = get_option('instalog-in-api-enabled');
            if ($api_enabled != 1) {
                return '';
            }

            // SETTINGS
            $attributes = shortcode_atts([
                'size' => '100px',
                'showWhenLoggedIn' => "false",
                'border' => "false",
            ], $attributes, 'instalogin-login-code');

            $size = $attributes['size'];
            $showWhenLoggedIn = $attributes['showWhenLoggedIn'] == 'true';
            $border = $attributes['border'] == 'true';

            if (!$showWhenLoggedIn && is_user_logged_in()) {
                return '';
            }

            // SCRIPTS
            wp_enqueue_script('instalog-in-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $api_key = get_option('instalog-in-api-key');
            $display_type = get_option('instalog-in-api-type', 'qr');
            wp_enqueue_script('instalog-in-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=3', ['instalog-in-api']);
            wp_localize_script('instalog-in-qr-widget', 'api_key', $api_key);
            wp_localize_script('instalog-in-qr-widget', 'display_type', $display_type);

            // RENDER
            ob_start(); ?>
                <style>
                    #instalogin .instalogin-container {
                        <?= $border ? 'border: 1px solid rgb(200, 200, 200);' : 'border: none !important;' ?>;
                        width: <?=$size?>;
                    }
                    #instalogin .instalogin-image {
                        width: <?=$size?>;
                    }
                </style>

                <div id="instalogin"></div>
            <?php

            return ob_get_clean();
        });
    }
}
