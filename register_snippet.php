<?php

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/../../../wp-admin/includes/user.php';


class InstaloginRegisterSnippet
{
    public function __construct()
    {
        $this->api();
        $this->shortcode();
    }

    private function api()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/register', [
                'methods' => 'POST',
                'callback' => function ($request) {
                    // API Has been disabled in backend
                    $api_enabled = get_option('instalog-in-api-enabled') == 1;
                    if (!$api_enabled) {
                        return new WP_Error('disabled', __('Registration via Instalog.in has been disabled by an administrator.', 'instalog-in'));
                    }

                    // Check if API set up correctly
                    $client = null;
                    $api_key = get_option('instalog-in-api-key');
                    $api_secret = get_option('instalog-in-api-secret');
                    try {
                        $client = new \Instalogin\Client($api_key, $api_secret);
                    } catch (\Throwable $th) {
                        return new WP_REST_Response(__('Sorry, the instalogin API data is incorrect!', 'instalog-in'), 500);
                    }

                    // body params
                    $username = $request['username'];
                    $email = $request['email'];

                    if ($email == null || !is_email($email)) {
                        return new WP_REST_Response(__('Sorry, email missing or invalid!', 'instalog-in'), 400);
                    }

                    if ($username == null) {
                        $username = $email;
                    }

                    // Create Wordpress user if not exists
                    $result = wp_create_user($username, wp_generate_password(64), $email);
                    if (is_wp_error($result)) {
                        return new WP_Error($result->get_error_code(), $result->get_error_message());
                    }
                    
                    try {
                        $client->provisionIdentity($email, [
                            'sendEmail' => true // Let Instalogin handle the mail sending
                        ]);
                    } catch (\Throwable $th) {
                        // Delete user if we could not deliver the activation email.
                        wp_delete_user($result);

                        return new WP_REST_Response(__('Sorry, could not connect to instalogin servers! Account could not be created.', 'instalog-in'), 500);
                    }

                    return;
                }
            ]);
        });
    }

    private function shortcode()
    {
        add_shortcode('instalogin-register', function ($attributes = [], $content = null) {
            $api_enabled = get_option('instalog-in-api-enabled');
            if ($api_enabled != 1) {
                return false;
            }

            // SETTINGS
            $attributes = shortcode_atts([
                        'require_username' => "true",
                        'show_button' => "true",
                        'button_text' => "Submit",
                        'show_when_logged_in' => "false",
                    ], $attributes, 'instalogin-register');
        
            $show_when_logged_in = $attributes['show_when_logged_in'] == 'true';
            $require_username = $attributes['require_username'] == 'true';
            $show_button = $attributes['show_button'] == 'true';
            $button_text = $attributes['button_text'];
            
            if (!$show_when_logged_in && is_user_logged_in()) {
                return '';
            }

            // SCRIPTS
            wp_enqueue_style('instalog-in-login', plugin_dir_url(__FILE__) . 'style/form.css?v=3');
            wp_enqueue_script('instalog-in-register', plugin_dir_url(__FILE__) . 'scripts/register.js?v=1', ['wp-i18n']);


            // RENDER
            ob_start(); ?>
                <form class="instalog-in-register">

                    <?php if ($require_username) { ?>
                        <label>
                            <span class="instalog-in-label">User</span>
                            <input type="text" required class="instalog-in-username" class="instalog-in-input">
                        </label>
                    <?php } ?>

                    <label>
                        <span class="instalog-in-label">Email</span>
                        <input type="email" required class="instalog-in-email" class="instalog-in-input">
                    </label>

                    <?php if ($show_button) { ?>
                        <input class="instalog-in-submit" type="submit" value="<?= $button_text ?>">
                    <?php } ?>

                    <p class="instalog-in-error"></p>
                    <p class="instalog-in-info"></p>
                </form>
            <?php
            return ob_get_clean();
        });
    }
}
