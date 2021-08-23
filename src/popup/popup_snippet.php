<?php

if (!defined('ABSPATH')) {
    exit;
}

class InstaloginPopupSnippet
{
    public function __construct()
    {
        add_shortcode('insta-popup', function ($attributes = [], $content = null) {
            // API disabled via settings?
            $api_enabled = get_option('instalogin-api-enabled');
            if ($api_enabled != 1) {
                return '';
            }

            $registration_enabled = get_option('instalogin-api-registration', 0) == 1;

            // SETTINGS
            // $attributes = shortcode_atts([
            //     'size' => '100px',
            //     'show_when_logged_in' => "false",
            //     'border' => "false",
            // ], $attributes, 'instalogin-login-code');


            // if (!$showWhenLoggedIn && is_user_logged_in()) {
            //     return '';
            // }

            // SCRIPTS
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $api_key = get_option('instalogin-api-key', false);
            $display_type = get_option('instalogin-api-type', 'qr');
            wp_enqueue_script('instalogin-qr-widget', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api']);
            wp_localize_script('instalogin-qr-widget', 'api_key', $api_key);
            wp_localize_script('instalogin-qr-widget', 'display_type', $display_type);

            wp_enqueue_script('instalogin-popup', plugin_dir_url(__FILE__) . 'popup.js?v=1', ['instalogin-qr-widget']);

            // TOOD: if key unset, display error message

            // RENDER
            ob_start(); ?>
            <style>
                #instalogin {
                    background: white;
                    border-radius: 20px;
                    box-shadow: #00000029 0px 3px 6px;
                }

                .insta-popup-container #instalogin .instalogin-container {
                    border: none !important;
                    width: 250px;
                    height: 250px;
                    padding: 2rem;
                    /* background-color: white; */

                    display: flex;
                    flex-flow: column;
                    justify-content: center;
                    align-items: center;

                    /* scale: .5; */

                }

                .insta-popup-container #instalogin .instalogin-image-container {
                    display: flex;
                    justify-content: center;
                }

                .insta-popup-container #instalogin .instalogin-image {
                    width: 80%;
                    margin: auto;
                    /* height: 80%; */

                }

                .insta-background {
                    width: 100vw;
                    height: 100vh;
                    position: fixed;
                    inset: 0;
                    z-index: 999;
                    visibility: hidden;
                }

                .insta-popup-container {
                    position: relative;
                }

                .insta-popup-container .insta-popup {

                    z-index: 1000;

                    padding: 1.5rem;
                    box-shadow: #00000029 0px 3px 6px;

                    border-radius: 40px;

                    position: absolute;
                    top: 0;

                    display: flex;
                    gap: 2rem;

                    background-color: #E9E9E9;

                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(-90px);

                    transition: all .15s ease-out;
                }

                .insta-popup-container .popup-active {
                    visibility: visible;
                    opacity: 1;
                    transform: translateY(0px);

                }

                .insta-popup-container .popup-body {
                    max-width: 15rem;
                    display: flex;
                    flex-flow: column;
                    justify-content: space-between;
                    padding-top: 1rem;

                }

                .insta-popup-container .insta-title {
                    font-size: 32px;
                    font-family: "TT Norms";
                    color: #3E84AD;
                    font-weight: bold;
                }

                .insta-popup-container .insta-sub {
                    font-size: 18px;
                    font-weight: bold;
                    color: #808080;
                    line-height: 1.15;
                }

                .insta-popup-container .insta-text {
                    font-size: .8rem;
                    margin-top: .5rem;
                    color: #808080;
                    font-size: 14px;
                    line-height: 1.3;

                }

                .insta-popup-container .insta-button {
                    font-size: 14px !important;
                    color: white !important;
                    background: #3E84AD !important;
                    border-radius: 100px;
                    float: right;
                    margin-top: 1rem;

                    padding: 8px 24px;

                    align-self: end;
                    text-decoration: none;
                    transition: transform .15s ease-out;
                    box-shadow: none;

                }

                .insta-popup-container .insta-button:hover {
                    transform: scale(1.05);
                    box-shadow: none;
                }
            </style>



            <div class="insta-popup-container">
                <button class="insta-opener">
                    <?= _e("Sign In", "instalogin") ?>
                </button>

                <div class="insta-background"></div>
                <div class="insta-popup">
                    <div id="instalogin"></div>
                    <div class="popup-body">
                        <!-- TODO: English -->
                        <div>
                            <span class="insta-title"><?= __('Adminlogin', 'instalogin') ?></span>
                            <div class="insta-sub"><?= __('Passwortfreies<br> Login der nächsten Generation') ?></div>
                            <p class="insta-text"><?= __('Den InstaCode mit der Instalogin-App scannen und passwortlos sicher einloggen.') ?></p>
                        </div>

                        <?php if (get_option('users_can_register') || $registration_enabled) { ?>
                            <a href="<?= wp_registration_url() ?>" class="insta-button"><?= __('Registrieren', 'instalogin') ?></a>
                        <?php } ?>
                    </div>

                </div>
            </div>



<?php

            return ob_get_clean();
        });
    }
}
