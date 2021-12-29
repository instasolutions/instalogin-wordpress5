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
                'redirect' => '',
            ], $attributes, 'insta-login');

            $size = $attributes['size'];
            $showWhenLoggedIn = $attributes['show_when_logged_in'] == 'true';
            $border = $attributes['border'] == 'true';
            $redirect = $attributes['redirect'];

            if (!$showWhenLoggedIn && is_user_logged_in()) {
                return '';
            }

            // SCRIPTS
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $container_id = wp_generate_password(5, false);
            $api_key = get_option('instalogin-api-key');
            $display_type = get_option('instalogin-api-type', 'qr');

            wp_enqueue_script('instalogin-login', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api'], null, true);
            wp_add_inline_script('instalogin-login', "init_insta('$container_id', '$api_key', '$display_type', '$redirect');", 'after');

            // TOOD: if key unset, display error message

            // RENDER
            ob_start(); ?>
            <style>
                .instalogin-login .instalogin-container {
                    border: <?php echo esc_html($border) ? ' 1px solid rgb(200, 200, 200);' : ' none !important;' ?>;
                    width: <?php echo esc_html($size) ?>;
                }

                .instalogin-login .instalogin-image {
                    width: <?php echo esc_html($size) ?>;
                }
            </style>

            <div class="instalogin-login" id="<?php echo esc_attr($container_id) ?>"></div>
<?php

            return ob_get_clean();
        });
    }
}
