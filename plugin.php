<?php

/**
 * Plugin Name: Official Instalog.in Integration
 * Plugin URI: https://instalog.in/
 * Author: Christian Schemoschek
 * Author URI: https://allbut.social
 * Requires at least: 5.0
 * Version: 0.4.0
 * Licence: TODO
 * Licence URI: TODO
 * Text Domain: instalogin
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class InstalogIn
{
    private $client = false;

    private $sc_login_code;
    private $sc_register;

    public function __construct()
    {
        $this->init_client();

        // Shortcodes
        require_once('login_snippet.php');
        require_once('src/popup/popup_snippet.php');
        require_once('register_snippet.php');
        require_once('device_snippet.php');


        $this->sc_login_code = new InstaloginLoginSnippet();
        $this->sc_register = new InstaloginRegisterSnippet();
        new InstaloginDeviceSnippet();
        new InstaloginPopupSnippet();

        // Backend
        require_once('manage_devices.php');
        new InstaloginDeviceManager();

        // wizard

        require_once('wizard/settings_rest.php');
        new InstalogWizardSettingsAPI();

        $this->settings_page();
        $this->login_controller();
        $this->login_page();
        $this->account_page();

        // Settings link in plugin overview on plugins page
        add_filter('plugin_row_meta', function ($links, $file_name) {
            if ($file_name == 'instalogin/plugin.php') {
                return array_merge($links, ['settings' => "<a href='/wp-admin/admin.php?page=instalogin'>Settings</a>"]);
            }
            return $links;
        }, 10, 2);
    }

    // Initialize Instalog.in SDK client
    private function init_client()
    {

        $api_key = get_option('instalogin-api-key');
        $api_secret = get_option('instalogin-api-secret');
        if ($api_key == false || $api_secret == false) {
            add_action('admin_notices', function () {
?>
                <div class="notice notice-info">
                    <h3>Instalogin Setup</h3>
                    <p>
                        <?= __('You are almost ready to use Instalogin!', 'instalogin') ?><br>
                        <?= __('Plase finish the setup: ', 'instalogin') ?><br>
                    </p>
                    <p>
                        <a class="button" href="<?= plugin_dir_url(__FILE__) ?>/wizard">Run Installation Wizard</a>
                    </p>
                </div>
            <?php
            });
            return;
        }

        // TODO: does not throw on error
        try {
            $this->client = new \Instalogin\Client($api_key, $api_secret);
        } catch (\Throwable $th) {
            add_action('admin_notices', function () {
                echo "<div class='error'><b>Instalog.in</b> " . __('API key or secret invalid.', 'instalogin') . "</div>";
                echo "<br>Go to <a href='/wp-admin/admin.php?page=instalogin'>settings</a>.</div>";
            });
        }
    }

    // Add settings page to admin panel.
    private function settings_page()
    {
        add_action('admin_menu', function () {

            // TODO: ADD TOOLTIPS


            __('Tooltip enable instalogin TODO', 'instalogin');
            __('Tooltip enable registration TODO', 'instalogin');
            __('Tooltip redirect to after login TODO (default /wp-admin)', 'instalogin');
            __('Tooltip Display type TODO', 'instalogin');
            __('Tooltip API Key TODO', 'instalogin');
            __('Tooltip API Secret TODO', 'instalogin');

            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1

            add_menu_page('Instalog.in Settings', 'Instalog.In', 'manage_options', 'instalogin', function () {
                if (!current_user_can('manage_options')) {
                    return;
                }

                // show settings saved info
                if (isset($_GET['settings-updated'])) {
                    add_settings_error('instalogin_messages', 'instalogin_message', __('Settings Saved', 'instalogin'), 'updated');
                }
                // show messages/errors
                settings_errors('instalogin_messages');

                // Render Settings
            ?>
                <div class="wrap">
                    <form action="options.php" method="post">
                        <?= settings_fields('instalogin'); ?>
                        <?= do_settings_sections('instalogin'); ?>
                        <p>Info Texts</p>
                        <?= submit_button(__('Save Settings', 'instalogin')); ?>
                    </form>
                </div>
            <?php
            }, 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjIiIGJhc2VQcm9maWxlPSJ0aW55LXBzIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIj4KCTx0aXRsZT5OZXcgUHJvamVjdDwvdGl0bGU+Cgk8ZGVmcz4KCQk8aW1hZ2UgIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMiIgaWQ9ImltZzEiIGhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQjRBQUFBZ0JBTUFBQUQzYnRWTUFBQUFBWE5TUjBJQjJja3Nmd0FBQUJoUVRGUkZBQUFBK2ZyNy8vLy80K2ZuNCtqdDd2RHg4L1gxL1AzOXZraStTQUFBQUFoMFVrNVRBTXIvTm1PRXF1KyszVFMxQUFBQXFFbEVRVlI0bkYyUlN4S0RNQXhEQlduTHRzTUpnakpselhBQ3p0SWo5UDZMMmdxWmZMekk4Q0pIVGdTZ21uaWdxUWZKbGczNXFmaDFqcUhnSkZ5NFoxeWRJN1JZelRST3N2Q05KNDMzMjVNdUcrYytOVGhmS0J2aU11Y2NHR3ZQQVdldmIraDFuOS9xZzE5Z2J1ajBCYjArUnd6bjArZ1h1MzU3eFR1L3AraFUyQXVsdjV3M0hZYXVjdWRnUzhoT1A5YzljcVF5eHFOby81Q3cza1BaN2hVMUJHM052UExISHowTkdxTFdTN2x3QUFBQUFFbEZUa1N1UW1DQyIvPgoJPC9kZWZzPgoJPHN0eWxlPgoJCXRzcGFuIHsgd2hpdGUtc3BhY2U6cHJlIH0KCTwvc3R5bGU+Cgk8dXNlIGlkPSJCYWNrZ3JvdW5kIiBocmVmPSIjaW1nMSIgeD0iMSIgeT0iMCIgLz4KPC9zdmc+');
        });

        add_action('admin_init', function () {
            $page = 'instalogin';
            $api_section = 'instalogin-api';

            // Add to wp
            add_settings_section($api_section, __('Instalog.in API Settings', 'instalogin'), function () {
                // Settings Section Title
            }, $page);

            // API Enabled
            $setting_name = 'instalogin-api-enabled';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Enable login via Instalog.in', 'instalogin'), function () {
                $setting_name = 'instalogin-api-enabled';
                $setting = get_option($setting_name); ?>
                <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <?php
            }, $page, $api_section);

            // Registration via API enabled
            $setting_name = 'instalogin-api-registration';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Enable registration via Instalog.in', 'instalogin'), function () {
                $setting_name = 'instalogin-api-registration';
                $setting = get_option($setting_name); ?>
                <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <?php
            }, $page, $api_section);

            // Redirection
            $setting_name = 'instalogin-api-redirect';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Redirect to after login', "instalogin"), function () {
                $setting_name = 'instalogin-api-redirect';
                $setting = get_option($setting_name); ?>
                <input type="text" placeholder="/wp-admin" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <?php
            }, $page, $api_section);

            // Use QR Code or Smart Image for login
            $setting_name = 'instalogin-api-type';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Display Type', "instalogin"), function () {
                $setting_name = 'instalogin-api-type';
                $setting = get_option($setting_name); ?>
                <select name="instalogin-api-type">
                    <option value="qr" <?php selected($setting, 'qr') ?>>QR Code</option>
                    <option value="si" <?php selected($setting, 'si') ?>>Smart Image</option>
                </select>
            <?php
            }, $page, $api_section);

            // API Secret
            $setting_name = 'instalogin-api-key';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('API Key', "instalogin"), function () {
                $setting_name = 'instalogin-api-key';
                $setting = get_option($setting_name); ?>
                <input type="text" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <?php
            }, $page, $api_section);

            // API Secret
            $setting_name = 'instalogin-api-secret';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('API Secret', "instalogin"), function () {
                $setting_name = 'instalogin-api-secret';
                $setting = get_option($setting_name); ?>
                <input type="password" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
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
                    $api_enabled = get_option('instalogin-api-enabled');
                    if ($api_enabled != 1) {
                        return new WP_Error('disabled', __('Login via Instalog.in has been disabled by an administrator.', 'instalogin'));
                    }

                    $is_desktop = false;
                    $x_requested_with = $request->get_headers()['x_requested_with'];
                    if ($x_requested_with != null && $x_requested_with[0] == 'XMLHttpRequest') {
                        $is_desktop = true;
                    }

                    $auth_header = $request->get_header('x_instalogin_auth');
                    if ($auth_header == null) {
                        return new WP_REST_Response(__('Authorization header missing.', 'instalogin'), 403);
                    }
                    // ? this is the default header, sometimes stripped by apache
                    // $jwt = [mb_substr($auth_header, 7)][0];
                    $jwt = $auth_header;
                    $token = $this->client->decodeJwt($jwt);

                    $email = $token->getIdentifier();
                    $user = get_user_by('email', $email);

                    if ($user == false) {
                        return new WP_REST_Response(__('Could not find user presented by token.', 'instalogin'), 403);
                    }

                    if ($this->client->verifyToken($token)) {
                        wp_set_auth_cookie($user->id, true, is_ssl());

                        // TODO: Add option redirect to current page?
                        // $redirect = $request->get_header('referer');
                        // if ($redirect == null || strpos($redirect, 'wp-login.php') !== false) {
                        //     $redirect = '/wp-admin';
                        // }

                        $redirect = get_option('instalogin-api-redirect', '/wp-admin');
                        if ($redirect == '') {
                            $redirect = '/wp-admin';
                        }

                        if ($is_desktop) {
                            return ['location' => $redirect];
                        }

                        wp_redirect($redirect, 307);
                        exit;
                    }

                    return new WP_REST_Response(__('Could not verify token.', 'instalogin'), 403);
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
            $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $query_params = parse_url($url, PHP_URL_QUERY);


            wp_enqueue_script('instalogin-devices', plugin_dir_url(__FILE__) . "scripts/devices.js", ['wp-i18n'], '1', true);
            wp_localize_script('instalogin-devices', 'wpv', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'show_activation' => $user_id != get_current_user_id(),
            ]);

            wp_enqueue_script('instalogin-send-mail', plugin_dir_url(__FILE__) . "scripts/device-send-mail.js", ['wp-i18n'], '1', true);
            wp_localize_script('instalogin-send-mail', 'wpv_mail', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'user_id' => $user_id,
            ]); ?>
            <div>
                <h3><a href="https://instalog.in" target="_black" rel="noreferrer">Instalog.in</a></h3>

                <div class="instalogin-info-area">
                    <?php
                    if (isset($_GET['reset_password']) && $_GET['reset_password'] == 'true') {
                        wp_set_password(wp_generate_password(64), get_current_user_id()); ?>
                        <script>
                            window.location = "/wp-login.php";
                        </script>
                    <?php

                        // echo "<div class='notice notice-info inline is-dismissible'><p>Random password has been set</p></div>";
                    } ?>
                </div>

                <p><?= __('Ready to join the passwordless revolution?', 'instalogin') ?></p>

                <button class="instalogin-activate button instalogin-send-mail"><?= __('Send activation mail', 'instalogin') ?></button>

                <?php if ($user_id == get_current_user_id()) { ?>

                    <style>
                        details.instalogin-devices-details {
                            cursor: pointer;
                            border-radius: 3px;
                            transition: 0.15s background linear;
                        }

                        details.instalogin-devices-details .instalogin-devices-admin {
                            cursor: auto;
                            background: #eee;
                            padding: 15px;
                            border-radius: 4px;
                        }

                        details.instalogin-devices-details .instalogin-devices-admin:before {
                            content: "";
                            height: 0;
                        }

                        details.instalogin-devices-details[open] .instalogin-devices-admin {
                            animation: animateDown 0.2s linear forwards;
                        }

                        @keyframes animateDown {
                            0% {
                                opacity: 0;
                                transform: translatey(-15px);
                            }

                            100% {
                                opacity: 1;
                                transform: translatey(0);
                            }
                        }
                    </style>

                    <details class="instalogin-devices-details">
                        <summary class="button" style="margin-bottom: .5rem; margin-top: .5rem;"><?= __('Manage Devices', 'instalogin') ?></summary>
                        <div class="instalogin-devices-admin">
                            <!-- <ul class="instalogin-device-list"></ul> -->
                        </div>
                    </details>

                    <div class="card">

                        <h2 class="title"><?= __('Randomize Password', 'instalogin') ?></h2>
                        <p><?= __('Instalogin enables effortless authentication by freeing you of the burden of having to remember a password.<br><br>
                            If you created your account with a password we suggest that you replace it with a strong, random and secret password.<br>
                            This will ensure that your password is unguessable and increase your account\'s security even further.<br><br>
                            Should you at any point decide that you do not wish to use Instalogin for authentication anymore you may set a new password by requesting a 
                            password reset email.', 'instalogin') ?></p>

                        <label>
                            <input type="checkbox" name="instalogin-accept-reset" id="instalogin-accept-reset">
                            Replace my password with a secure random password.
                        </label>
                        <br>

                        <button id="instalogin-reset-password" disabled style="margin-top: 1rem;" class="button">Save</button>
                    </div>

                    <script>
                        {

                            history.pushState(null, null, '/wp-admin/profile.php');

                            const reset_button = document.querySelector('#instalogin-reset-password');
                            const checkbox = document.querySelector('#instalogin-accept-reset');

                            if (checkbox) {
                                checkbox.addEventListener('change', () => {
                                    if (checkbox.checked) reset_button.disabled = false;
                                    else reset_button.disabled = true;
                                })
                            }

                            if (reset_button && checkbox) {
                                reset_button.addEventListener('click', (event) => {
                                    event.preventDefault();

                                    if (checkbox.checked) {
                                        window.location = '/wp-admin/profile.php?reset_password=true'
                                    }
                                })
                            }
                        }
                    </script>

                <?php } else { ?>

                    <div class="notice notice-warning is-dismissible inline">
                        <p>
                            You can not manage another user's devices.
                        </p>
                    </div>

                <?php } ?>
            </div>
<?php
        });
    }

    // Display login code on wp login page.
    private function login_page()
    {
        $api_enabled = get_option('instalogin-api-enabled');
        if ($api_enabled != 1) {
            return false;
        }

        add_action('login_head', function () {
            wp_enqueue_style('instalogin-login', plugin_dir_url(__FILE__) . 'style/login.css?v=3');
        });

        add_action('login_footer', function () {
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $api_key = get_option('instalogin-api-key');
            $display_type = get_option('instalogin-api-type', 'qr');
            wp_enqueue_script('instalogin-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=3', ['instalogin-api']);
            wp_localize_script('instalogin-qr-widget', 'api_key', $api_key);
            wp_localize_script('instalogin-qr-widget', 'display_type', $display_type);
        });
    }
}

$instalog_in = new InstalogIn();
