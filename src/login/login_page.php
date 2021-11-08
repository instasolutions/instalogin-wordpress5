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
            // load global variables
            wp_enqueue_script('insta_global_scripts', plugin_dir_url(__FILE__) . "scripts/global.js", [], '0.0.1', false);
            wp_add_inline_script('insta_global_scripts', "const insta_api = '" . rest_url('instalogin/v1/') . "'", 'before');

            wp_enqueue_style('instalogin-login', plugin_dir_url(__FILE__) . '../../style/login.css?v=3');
        });

        add_action('login_footer', function () {

            // don't show login code when registering or requesting email etc.
            if (isset($_GET['action'])) {
                return;
            }

            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $container_id = 'instalogin';
            $api_key = get_option('instalogin-api-key');
            $display_type = get_option('instalogin-api-type', 'qr');

            wp_enqueue_script('instalogin-login', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api'], null, true);
            wp_add_inline_script('instalogin-login', "init_insta('$container_id', '$api_key', '$display_type');", 'after');
        });
    }
}
