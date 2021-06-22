<?php

if (!defined('ABSPATH')) {
    exit;
}


class InstaloginDeviceSnippet
{
    public function __construct()
    {
        add_shortcode('instalogin-devices', function ($attributes = [], $content = null) {
            // API disabled via settings?
            // $api_enabled = get_option('instalogin-api-enabled');
            // if ($api_enabled != 1) {
            //     return '';
            // }

            // SETTINGS
            $attributes = shortcode_atts([], $attributes, 'instalogin-login-code');

            $size = $attributes['size'];
            $showWhenLoggedIn = $attributes['show_when_logged_in'] == 'true';
            $border = $attributes['border'] == 'true';

            if (!is_user_logged_in()) {
                return '<div>' . __('You must be logged in to manage devices.', 'instalogin') . '</div>';
            }

            // SCRIPTS
            wp_enqueue_script('instalogin-devices', plugin_dir_url(__FILE__) . "scripts/devices.js", [], '1', true);
            wp_localize_script('instalogin-devices', 'wpv', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'show_activation' => false,
                'is_frontend' => true,
            ]);

            wp_enqueue_script('instalogin-send-mail', plugin_dir_url(__FILE__) . "scripts/device-send-mail.js", [], '1', true);
            wp_localize_script('instalogin-send-mail', 'wpv_mail', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'user_id' => $user_id,
            ]);

            // RENDER
            ob_start(); ?>
                <div>
                    <div class="instalogin-devices">

                    <style>
                        .instalogin-devices {
                            background: white;
                            border-radius: .25rem;
                            padding: 1rem;

                            max-width: 20rem !important;
                        }

                        .instalogin-devices-title {
                            text-transform: uppercase;
                            font-size: .875em;
                            color: #333;
                            font-weight: 300;
                        }
                        .instalogin-devices-hr {
                            margin-bottom: .5rem;
                            border-bottom: 1px #333 solid;
                            opacity: .25;
                        }

                        ul.instalogin-device-list {
                            list-style: none; 
                            padding: 0;
                            margin-bottom: 1rem;
                            
                        }
                        ul.instalogin-device-list li {
                            display: flex; 
                            align-items: start;
                            justify-content: space-between;
                            gap: 1rem;
                            width: 100%;
                        }

                        ul.instalogin-device-list li button {
                            background-color: transparent !important;
                            padding: 0;
                            border:none;
                            color: black !important;
                        }

                        .instalogin-refresh, .instalogin-send-mail {

                            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;

                            padding: .375rem .75rem;
                            color: #6c757d !important;
                            background-color: white !important;
                            border-color: #6c757d;
                            border-radius: .25rem;
                            border: 1px solid;

                            text-transform: uppercase;

                            font-size: small;
                            font-weight: 700;
                        }

                        .instalogin-refresh:hover, .instalogin-send-mail:hover {
                            color: white !important;
                            background-color: #6c757d !important;
                            border-color: #6c757d !important;
                        }

                        ul.instalogin-device-list .instalogin-device-entry{
                            display: flex;
                            flex-flow: column;
                            margin-top: .5rem;
                        }

                        ul.instalogin-device-list .instalogin-device-entry span{
                            font-size: 1rem !important;
                            font-weight: normal;
                        }

                        ul.instalogin-device-list .instalogin-device-entry small{
                            font-size: .775em !important;
                            line-height: 1;
                            font-weight: 300;
                        }

                        ul.instalogin-device-list svg{
                            width: 8px !important;
                        }
                        </style>

                        <span class="instalogin-devices-title">Connected Devices</span>
                        <hr class="instalogin-devices-hr">

                        <ul class="instalogin-device-list">
                            
                        </ul>
                        <button class="instalogin-refresh">Refresh</button>
                        <button class="instalogin-send-mail">Add Device</button>
                    </div>
                </div>
            <?php

            return ob_get_clean();
        });
    }
}
