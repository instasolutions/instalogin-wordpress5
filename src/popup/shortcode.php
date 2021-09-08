<?php

if (!defined('ABSPATH')) {
    exit;
}

class InstaloginPopupShortcode
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

            // if (!$showWhenLoggedIn && is_user_logged_in()) {
            //     return '';
            // }

            $setting_name = 'instalogin-popup-style';
            require_once(dirname(__FILE__) . '/default_settings.php');
            $setting = get_option($setting_name, $default_popup_settings);

            // SCRIPTS
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $container_id = wp_generate_password(5, false);;
            $api_key = get_option('instalogin-api-key', false);
            $display_type = get_option('instalogin-api-type', 'qr');

            wp_enqueue_script('instalogin-login', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api'], null, true);
            wp_add_inline_script('instalogin-login', "insta = init_insta('$container_id', '$api_key', '$display_type');", 'after');

            wp_enqueue_script('instalogin-popup', plugin_dir_url(__FILE__) . 'popup.js?v=1', [], null, true);
            wp_localize_script('instalogin-popup', 'trigger', $setting['trigger']);

            // TOOD: if key unset, display error message



            // RENDER
            ob_start(); ?>
            <style>
                .insta-popup-container .instalogin-popup {
                    background: <?= $setting['qr-bg'] ?>;
                    border-radius: 20px;
                    /* border:; */
                    box-shadow: <?= $setting['qr-shadow'] == 'on' ? '#00000029 0px 3px 6px' : '' ?>;
                }

                .insta-popup-container .instalogin-popup .instalogin-container {
                    border: none !important;
                    width: 250px;
                    height: 250px;
                    padding: 2rem;

                    display: flex;
                    flex-flow: column;
                    justify-content: center;
                    align-items: center;

                    /* scale: .5; */

                }

                .insta-popup-container .instalogin-popup .instalogin-image-container {
                    display: flex;
                    justify-content: center;
                    background: <?= $setting['qr-bg'] ?>;
                }

                .insta-popup-container .instalogin-popup .instalogin-image {
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
                    box-shadow: <?= $setting['box-shadow'] == 'on' ? '#00000029 0px 3px 6px' : '' ?>;

                    border-radius: <?= $setting['box-border-radius'] ?>;

                    position: absolute;

                    <?= $setting['position'] ?>: <?= $setting['vertical'] ?>;
                    left: <?= $setting['horizontal'] ?>;

                    display: flex;
                    gap: 2rem;

                    background-color: <?= $setting['box-bg'] ?>;

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
                    font-size: <?= $setting['title-size'] ?>;
                    font-family: <?= $setting['font'] ?>;
                    color: <?= $setting['title-color'] ?>;
                    font-weight: <?= $setting['title-weight'] ?>;
                }

                .insta-popup-container .insta-sub {
                    font-size: <?= $setting['sub-title-size'] ?>;
                    font-weight: <?= $setting['sub-title-weight'] ?>;
                    font-family: <?= $setting['font'] ?>;
                    color: <?= $setting['sub-title-color'] ?>;
                    line-height: 1.15;
                }

                .insta-popup-container .insta-text {
                    margin-top: .5rem;
                    line-height: 1.3;

                    font-size: <?= $setting['text-size'] ?>;
                    font-weight: <?= $setting['text-weight'] ?>;
                    font-family: <?= $setting['font'] ?>;
                    color: <?= $setting['text-color'] ?>;
                }

                .insta-popup-container .insta-button {
                    font-size: <?= $setting['button-size'] ?> !important;
                    color: <?= $setting['button-color'] ?> !important;
                    background: <?= $setting['button-bg'] ?> !important;
                    border-radius: <?= $setting['button-radius'] ?>;
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

            <?php
            if (is_user_logged_in()) {
            ?>
                <div>
                    <a href="<?= wp_logout_url() ?>">
                        <button>
                            <?= _e("Logout", "instalogin") ?>
                        </button>
                    </a>
                </div>
            <?php
            } else {
            ?>

                <div class="insta-popup-container">
                    <button class="insta-opener">
                        <?= __("Sign In", "instalogin") ?>
                    </button>

                    <div class="insta-background"></div>
                    <div class="insta-popup">
                        <div class="instalogin-popup" id="<?= $container_id ?>"></div>
                        <div class="popup-body">
                            <!-- TODO: English -->
                            <div>
                                <span class="insta-title"><?= $setting['title-text'] ?></span>
                                <div class="insta-sub"><?= $setting['sub-title-text'] ?></div>
                                <p class="insta-text"><?= $setting['text-text'] ?></p>
                            </div>

                            <?php if (get_option('users_can_register')) { ?>
                                <a href="<?= wp_registration_url() ?>" class="insta-button"><?= __('Register', 'instalogin') ?></a>
                            <?php } ?>
                        </div>

                    </div>
                </div>



<?php
            }

            return ob_get_clean();
        });
    }
}
