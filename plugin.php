<?php

/**
 * Plugin Name: Instalog.in Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class InstalogIn
{
    public function __construct()
    {
        // wp_enqueue_style('instalog-in-global', plugin_dir_url(__FILE__) . 'style/style.css?v=1');
        // TODO: style on admin pages

        $this->settings_page();
        $this->login_controller();
        $this->login_page();
        $this->account_page();
    }

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

    public function render_settings_api_section()
    {
        echo "<p>API Settings</p>";
    }

    private function login_controller()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/login-controller', [
                'methods' => 'GET',
                'callback' => function ($request) {
                    $client = new \Instalogin\Client('VluObzy1BoNiFcgm5OXSQun42pF9pFNx', '7e2672af946831902319d3a17573bac3ed3897be1eb3c962b074bd62e75293cb');
                    // TODO: redirect query param
                    // TODO: error handling

                    $auth_header = $request->get_headers()['authorization'][0];
                    $jwt = [mb_substr($auth_header, 7)][0];
                    $token = $client->decodeJwt($jwt);
                    $email = $token->getIdentifier();
                    $user = get_user_by('email', $email);

                    if ($user == false) {
                        return false;
                    }

                    if ($client->verifyToken($token)) {
                        wp_set_auth_cookie($user->id, true, is_ssl());
                        // return ['location' => $user];
                        return ['location' => '/wp-admin'];
                    }

                    return false;
                }
            ]);
        });
    }

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

    private function login_page()
    {
        add_action('login_head', function () {
            wp_enqueue_style('instalog-in-login', plugin_dir_url(__FILE__) . 'style/login.css?v=1');
        });

        // TODO login_footer fuer js

        add_action('login_footer', function () {
            ?> <script async id="instalogin-js" src="https://cdn.instalog.in/js/instalogin-0.7.1.js"></script> <?php
            wp_enqueue_script('instalog-in-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=1');
        });
    }
}

$instalog_in = new InstalogIn();
