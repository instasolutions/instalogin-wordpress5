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
                    transform: translateY(-20px);

                    transition: all .15s ease-out;
                }

                .insta-popup-container .popup-active {
                    visibility: visible;
                    opacity: 1;
                    transform: translateY(-5px);

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

                .insta-popup-button {
                    font-size: <?= $setting['login-font-size'] ?> !important;
                    font-weight: <?= $setting['login-weight'] ?> !important;
                    color: <?= $setting['login-color'] ?> !important;
                    background: <?= $setting['login-bg'] ?> !important;
                    border-radius: <?= $setting['login-radius'] ?> !important;
                    padding: <?= $setting['login-padding'] ?> !important;
                }

                .insta-popup-icon,
                .insta-popup-icon:hover {
                    background: none !important;
                    color: <?= $setting['login-color'] ?> !important;
                    border: none !important;

                    padding: 0;
                    width: <?= $setting['login-size'] ?>;
                    height: <?= $setting['login-size'] ?>;
                }

                .insta-popup-icon svg {
                    fill: #1d4264;
                    /* TODO: Setting and var? */
                }

                .insta-popup-logout:hover svg {
                    opacity: .7;
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
                    <a class="insta-popup-logout" href="<?= wp_logout_url() ?>">
                        <?php if ($setting['login-type'] == 'text') { ?>
                            <button class="insta-popup-button">
                                <?= _e("Logout", "instalogin") ?>
                            </button>
                        <?php } else { ?>
                            <button class="insta-popup-icon">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sign-out-alt" class="svg-inline--fa fa-sign-out-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path>
                                </svg>
                            </button>
                        <?php } ?>
                    </a>
                </div>
            <?php
            } else {
            ?>

                <div class="insta-popup-container">
                    <?php if ($setting['login-type'] == 'text') { ?>
                        <button class="insta-opener insta-popup-button">
                            <?= __("Sign In", "instalogin") ?>
                        </button>
                    <?php } else { ?>
                        <button class="insta-opener insta-popup-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                <g id="Gruppe_2693" data-name="Gruppe 2693" transform="translate(-675 -1425)">
                                    <path id="Icon_ionic-ios-person" data-name="Icon ionic-ios-person" d="M50.758,49.614c-.868-3.833-5.822-5.7-7.533-6.3-1.88-.663-4.556-.82-6.28-1.205a6.38,6.38,0,0,1-2.9-1.338c-.482-.579-.193-5.942-.193-5.942a17.877,17.877,0,0,0,1.374-2.615,33.736,33.736,0,0,0,1.012-4.568s.988,0,1.338-1.736c.374-1.892.964-2.628.892-4.038-.072-1.386-.832-1.35-.832-1.35a21.132,21.132,0,0,0,.82-6.183C38.561,9.381,34.68,4.5,27.653,4.5c-7.123,0-10.92,4.881-10.811,9.835a22.042,22.042,0,0,0,.808,6.183s-.759-.036-.832,1.35c-.072,1.41.518,2.145.892,4.038.337,1.736,1.338,1.736,1.338,1.736a33.735,33.735,0,0,0,1.012,4.568,17.877,17.877,0,0,0,1.374,2.615s.289,5.363-.193,5.942a6.38,6.38,0,0,1-2.9,1.338c-1.724.386-4.4.542-6.28,1.205-1.711.6-6.665,2.471-7.533,6.3a.964.964,0,0,0,.952,1.169H49.818A.961.961,0,0,0,50.758,49.614Z" transform="translate(697.499 1447.5)" />
                                    <path id="Ellipse_1" data-name="Ellipse 1" d="M50,6A44,44,0,1,0,94,50,44.05,44.05,0,0,0,50,6m0-6A50,50,0,1,1,0,50,50,50,0,0,1,50,0Z" transform="translate(675 1425)" />
                                </g>
                            </svg>
                        </button>
                    <?php } ?>
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
