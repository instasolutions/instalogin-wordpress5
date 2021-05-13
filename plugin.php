<?php

/**
 * Plugin Name: Official Instalog.in Integration
 * Plugin URI: https://instalog.in/
 * Author: Christian Schemoschek
 * Author URI: https://allbut.social
 * Version: 0.1.0
 * Licence: TODO
 * Licence URI: TODO
 * Text Domain: instalog-in
 */

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class InstalogIn
{
    private $client = false;

    public function __construct()
    {
        $this->init_client();

        $this->settings_page();
        $this->login_controller();
        $this->login_page();
        $this->account_page();

        // Settings link in plugin overview on plugins page
        add_filter('plugin_row_meta', function ($links) {
            return array_merge($links, ['settings' => "<a href='/wp-admin/admin.php?page=instalog-in'>Settings</a>"]);
        });
    }

    // Initialize Instalog.in SDK client
    private function init_client()
    {
        $api_key = get_option('instalog-in-api-key');
        $api_secret = get_option('instalog-in-api-secret');
        if ($api_key == false || $api_secret == false) {
            add_action('admin_notices', function () {
                echo "<div class='error'><b>Instalog.in</b> API key or secret missing.";
                echo "<br>Go to <a href='/wp-admin/admin.php?page=instalog-in'>settings</a>.</div>";
            });
            return;
        }

        // TODO: does not throw on error
        try {
            $this->client = new \Instalogin\Client($api_key, $api_secret);
        } catch (\Throwable $th) {
            add_action('admin_notices', function () {
                echo "<div class='error'><b>Instalog.in</b> API key or secret invalid.</div>";
                echo "<br>Go to <a href='/wp-admin/admin.php?page=instalog-in'>settings</a>.</div>";
            });
        }
    }

    // Add settings page to admin panel.
    private function settings_page()
    {
        add_action('admin_menu', function () {
            add_menu_page('Instalog.in Settings', 'Instalog.In', 'manage_options', 'instalog-in', function () {
                if (! current_user_can('manage_options')) {
                    return;
                }

                // show settings saved info
                if (isset($_GET['settings-updated'])) {
                    add_settings_error('instalog-in_messages', 'instalog-in_message', __('Settings Saved', 'instalog-in'), 'updated');
                }
                // show messages/errors
                settings_errors('instalog-in_messages');
                
                // Render Settings?>
                    <div class="wrap">
                        <form action="options.php" method="post">
                            <?= settings_fields('instalog-in'); ?>
                            <?= do_settings_sections('instalog-in'); ?>
                            <p>Info Texts</p>
                            <?= submit_button('Save Settings'); ?>
                        </form>
                    </div>
                <?php
            });
        });

        add_action('admin_init', function () {
            $page = 'instalog-in';
            $api_section = 'instalog-in-api';
            
            // Add to wp
            add_settings_section($api_section, 'Instalog.in API Settings', function () {
                // Settings Section Title
            }, $page);
            
            // API Enabled
            $setting_name = 'instalog-in-api-enabled';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", 'Enable Login via Instalog.in', function () {
                $setting_name = 'instalog-in-api-enabled';
                $setting = get_option($setting_name); ?>
                    <input type="checkbox" name="<?=$setting_name?>" value="1" <?= $setting == 1 ? 'checked' : '' ?>/>
                <?php
            }, $page, $api_section);

            // API Secret
            $setting_name = 'instalog-in-api-key';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", 'API Key', function () {
                $setting_name = 'instalog-in-api-key';
                $setting = get_option($setting_name); ?>
                    <input type="text" name="<?=$setting_name?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>"/>
                <?php
            }, $page, $api_section);

            // API Secret
            $setting_name = 'instalog-in-api-secret';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", 'API Secret', function () {
                $setting_name = 'instalog-in-api-secret';
                $setting = get_option($setting_name); ?>
                    <input type="password" name="<?=$setting_name?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>"/>
                <?php
            }, $page, $api_section);
        });
    }

    // Login REST API endpoint for the instalog.in js SDK
    private function login_controller()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/login-controller', [
                'methods' => 'GET',
                'callback' => function ($request) {
                    // API Has been disabled in backend
                    $api_enabled = get_option('instalog-in-api-enabled');
                    if ($api_enabled != 1) {
                        return new WP_Error('disabled', __('Login via Instalog.in has been disabled by an administrator.', 'instalog-in'));
                    }

                    // TODO: redirect query param

                    $auth_header = $request->get_headers()['authorization'];
                    if ($auth_header == [""]) {
                        return new WP_REST_Response(__('Authorization header missing.', 'instalog-in'), 403);
                    }
                    $auth_header = $auth_header[0];
                    $jwt = [mb_substr($auth_header, 7)][0];
                    $token = $this->client->decodeJwt($jwt);

                    $email = $token->getIdentifier();
                    $user = get_user_by('email', $email);

                    if ($user == false) {
                        return new WP_REST_Response(__('Could not find user presented by token.', 'instalog-in'), 403);
                    }

                    if ($this->client->verifyToken($token)) {
                        wp_set_auth_cookie($user->id, true, is_ssl());
                        return ['location' => '/wp-admin'];
                    }

                    return new WP_REST_Response(__('Could not verify token.', 'instalog-in'), 403);
                }
            ]);
        });
    }

    // Allow users to enable instalog.in authentication in their profile page.
    private function account_page()
    {
        add_action('personal_options', function () {
            $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                <div>
                    <h3>Instalog.in</h3>
                    Activate instant login via Instalog.in:    
                    <a href="/wp-content/plugins/instalog-in/send_mail.php?redirect=<?=$url?>">Send activation Mail</a>
                </div>
            <?php
        });
    }

    // Display login graphic on wp login page.
    private function login_page()
    {
        $api_enabled = get_option('instalog-in-api-enabled');
        if ($api_enabled != 1) {
            return false;
        }

        add_action('login_head', function () {
            wp_enqueue_style('instalog-in-login', plugin_dir_url(__FILE__) . 'style/login.css?v=1');
        });

        add_action('login_footer', function () {
            ?> <script async id="instalogin-js" src="https://cdn.instalog.in/js/instalogin-0.7.1.js"></script> <?php
            wp_enqueue_script('instalog-in-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=1');
        });
    }
}

$instalog_in = new InstalogIn();
