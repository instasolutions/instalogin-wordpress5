<?php

class InstaloginLoginPage
{
    // Display login code on wp login page.
    public function __construct()
    {
        $api_enabled = get_option('instalogin-api-enabled', 0);
        if ($api_enabled != 1) {
            return false;
        }

        add_action('login_head', function () {
            wp_enqueue_style('instalogin-login', plugin_dir_url(__FILE__) . '../../style/login.css?v=3');
        });

        add_action('login_footer', function () {
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $api_key = get_option('instalogin-api-key');
            $display_type = get_option('instalogin-api-type', 'qr');
            wp_enqueue_script('instalogin-qr-widget', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api']);
            wp_localize_script('instalogin-qr-widget', 'api_key', $api_key);
            wp_localize_script('instalogin-qr-widget', 'display_type', $display_type);
        });
    }
}
