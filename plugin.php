<?php

/**
 * Plugin Name: Official Instalog.in Integration
 * Plugin URI: https://instalog.in/
 * Author: Christian Schemoschek
 * Author URI: https://allbut.social
 * Version: 0.1.7
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
        add_filter('plugin_row_meta', function ($links, $file_name) {
            if ($file_name == 'instalog-in/plugin.php') {
                return array_merge($links, ['settings' => "<a href='/wp-admin/admin.php?page=instalog-in'>Settings</a>"]);
            }
            return $links;
        }, 10, 2);
    }

    // Initialize Instalog.in SDK client
    private function init_client()
    {
        $api_key = get_option('instalog-in-api-key');
        $api_secret = get_option('instalog-in-api-secret');
        if ($api_key == false || $api_secret == false) {
            add_action('admin_notices', function () {
                echo "<div class='error'><b>Instalog.in</b> " . __('API key or secret missing.', 'instalog-in');
                echo "<br>Go to <a href='/wp-admin/admin.php?page=instalog-in'>settings</a>.</div>";
            });
            return;
        }

        // TODO: does not throw on error
        try {
            $this->client = new \Instalogin\Client($api_key, $api_secret);
        } catch (\Throwable $th) {
            add_action('admin_notices', function () {
                echo "<div class='error'><b>Instalog.in</b> " . __('API key or secret invalid.', 'instalog-in') . "</div>";
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
                            <?= submit_button(__('Save Settings', 'instalog-in')); ?>
                        </form>
                    </div>
                <?php
            }, 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjIiIGJhc2VQcm9maWxlPSJ0aW55LXBzIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIj4KCTx0aXRsZT5OZXcgUHJvamVjdDwvdGl0bGU+Cgk8ZGVmcz4KCQk8aW1hZ2UgIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMiIgaWQ9ImltZzEiIGhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQjRBQUFBZ0JBTUFBQUQzYnRWTUFBQUFBWE5TUjBJQjJja3Nmd0FBQUJoUVRGUkZBQUFBK2ZyNy8vLy80K2ZuNCtqdDd2RHg4L1gxL1AzOXZraStTQUFBQUFoMFVrNVRBTXIvTm1PRXF1KyszVFMxQUFBQXFFbEVRVlI0bkYyUlN4S0RNQXhEQlduTHRzTUpnakpselhBQ3p0SWo5UDZMMmdxWmZMekk4Q0pIVGdTZ21uaWdxUWZKbGczNXFmaDFqcUhnSkZ5NFoxeWRJN1JZelRST3N2Q05KNDMzMjVNdUcrYytOVGhmS0J2aU11Y2NHR3ZQQVdldmIraDFuOS9xZzE5Z2J1ajBCYjArUnd6bjArZ1h1MzU3eFR1L3AraFUyQXVsdjV3M0hZYXVjdWRnUzhoT1A5YzljcVF5eHFOby81Q3cza1BaN2hVMUJHM052UExISHowTkdxTFdTN2x3QUFBQUFFbEZUa1N1UW1DQyIvPgoJPC9kZWZzPgoJPHN0eWxlPgoJCXRzcGFuIHsgd2hpdGUtc3BhY2U6cHJlIH0KCTwvc3R5bGU+Cgk8dXNlIGlkPSJCYWNrZ3JvdW5kIiBocmVmPSIjaW1nMSIgeD0iMSIgeT0iMCIgLz4KPC9zdmc+');
        });

        add_action('admin_init', function () {
            $page = 'instalog-in';
            $api_section = 'instalog-in-api';
            
            // Add to wp
            add_settings_section($api_section, __('Instalog.in API Settings', 'instalog-in'), function () {
                // Settings Section Title
            }, $page);
            
            // API Enabled
            $setting_name = 'instalog-in-api-enabled';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Enable Login via Instalog.in', 'instalog-in'), function () {
                $setting_name = 'instalog-in-api-enabled';
                $setting = get_option($setting_name); ?>
                    <input type="checkbox" name="<?=$setting_name?>" value="1" <?= $setting == 1 ? 'checked' : '' ?>/>
                <?php
            }, $page, $api_section);

            // Use QR Code or Smart Image for login
            $setting_name = 'instalog-in-api-type';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", 'Display Type', function () {
                $setting_name = 'instalog-in-api-type';
                $setting = get_option($setting_name); ?>
                    <select name="instalog-in-api-type">
                        <option value="qr" <?php selected($setting, 'qr') ?>>QR Code</option>
                        <option value="si" <?php selected($setting, 'si') ?>>Smart Image</option>
                    </select>
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

                    $is_desktop = false;
                    $x_requested_with = $request->get_headers()['x_requested_with'];
                    if ($x_requested_with != null && $x_requested_with[0] == 'XMLHttpRequest') {
                        $is_desktop = true;
                    }

                    // TODO: redirect query param

                    $auth_header = $request->get_headers()['x_instalogin_auth'];
                    if ($auth_header == [""] || $auth_header == null) {
                        return new WP_REST_Response(__('Authorization header missing.', 'instalog-in'), 403);
                    }
                    $auth_header = $auth_header[0];
                    // ? this is the default header, sometimes stripped by apache
                    // $jwt = [mb_substr($auth_header, 7)][0];
                    $jwt = $auth_header;
                    $token = $this->client->decodeJwt($jwt);

                    $email = $token->getIdentifier();
                    $user = get_user_by('email', $email);

                    if ($user == false) {
                        return new WP_REST_Response(__('Could not find user presented by token.', 'instalog-in'), 403);
                    }

                    if ($this->client->verifyToken($token)) {
                        wp_set_auth_cookie($user->id, true, is_ssl());

                        if ($is_desktop) {
                            return ['location' => '/wp-admin'];
                        }

                        wp_redirect('/wp-admin', 307);
                        exit;
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
            // current user being edited
            global $user_id;
            $user = get_user_by('id', $user_id);
            $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $query_params = parse_url($url, PHP_URL_QUERY);

            $sent = strpos($query_params, 'sent') !== false; ?>
                <div>
                    <h3><a href="https://instalog.in" target="_black" rel="noreferrer">Instalog.in</a></h3>
                    <?php if ($sent) {?>
                    </p></div>
                        <div class="notice notice-info is-dismissible inline">
                            <p>
                                <?= $sent ? __('Email has been sent to ' . $user->user_email . ' !', 'instalog-in') : '' ?>
                            </p>
                        </div>
                    <?php } ?>
                    <p><?=__('Ready to join the passwordless revolution?', 'instalog-in')?></p>
                    <a class="button" href="/wp-content/plugins/instalog-in/send_mail.php?user_id=<?=$user_id?>&redirect=<?=$url?>"><?=__('Send activation Mail', 'instalog-in')?></a>
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
            wp_enqueue_style('instalog-in-login', plugin_dir_url(__FILE__) . 'style/login.css?v=2');
        });
        
        add_action('login_footer', function () {
            wp_enqueue_script('instalog-in-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $api_key = get_option('instalog-in-api-key');
            $display_type = get_option('instalog-in-api-type', 'qr');
            wp_enqueue_script('instalog-in-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=3', ['instalog-in-api']);
            wp_localize_script('instalog-in-qr-widget', 'api_key', $api_key);
            wp_localize_script('instalog-in-qr-widget', 'display_type', $display_type);
        });
    }
}

$instalog_in = new InstalogIn();
