<?php

if (!defined('ABSPATH')) {
    exit;
}

class InstaloginPopupShortcode
{
    public function __construct()
    {
        add_shortcode('insta-popup', function ($attributes = [], $content = null) {


            $attributes = shortcode_atts([
                'preview' => "false",
            ], $attributes, 'insta-popup');

            return InstaloginPopupShortcode::render_popup($attributes['preview'] == 'true');
        });
    }

    public static function render_popup($preview = false)
    {
        $api_enabled = get_option('instalogin-api-enabled');
        if ($api_enabled != 1) {
            return '';
        }

        $setting_name = 'instalogin-popup-style';
        require(dirname(__FILE__) . '/default_settings.php');
        $setting = get_option($setting_name, $default_popup_settings);

        // icon display
        $icon_setting_name = 'instalogin-popup-icon';
        $icon_setting = get_option($icon_setting_name);

        $logout_icon_setting_name = 'instalogin-popup-icon-logout';
        $logout_icon_setting = get_option($logout_icon_setting_name);

        // SCRIPTS
        wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

        $container_id = wp_generate_password(5, false);;
        $api_key = get_option('instalogin-api-key', false);
        $display_type = get_option('instalogin-api-type', 'qr');

        wp_enqueue_script('instalogin-login', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api'], null, true);
        wp_add_inline_script('instalogin-login', "insta = init_insta('$container_id', '$api_key', '$display_type');", 'after');

        wp_enqueue_script('instalogin-popup', plugin_dir_url(__FILE__) . 'popup.js?v=1', [], null, true);
        wp_localize_script('instalogin-popup', 'trigger', [$setting['trigger']]);

        // TOOD: if key unset, display error message

        // RENDER
        ob_start(); ?>
        <style>
            .insta-popup-container .instalogin-popup {
                background: <?php echo esc_attr($setting['qr-bg']) ?>;
                border-radius: 8px;
                /* TODO: setting */
                /* border:; */
                box-shadow: <?php echo esc_html($setting['qr-shadow']) == 'on' ? '#00000029 0px 3px 6px' : '' ?>;
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

                white-space: wrap !important;

                /* scale: .5; */

            }

            .insta-popup-container .instalogin-popup .instalogin-image-container {
                display: flex;
                justify-content: center;
                background: <?php echo esc_attr($setting['qr-bg']) ?>;
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
                /* hide popup on desktop when screen too small */
                display: none;
            }

            @media screen and (min-width: 800px) {
                .insta-popup-container {
                    display: inline-flex;
                }
            }

            .insta-popup-container .insta-popup {
                z-index: 1000;

                padding: 1.5rem;
                box-shadow: <?php echo esc_html($setting['box-shadow']) == 'on' ? '#00000029 0px 3px 6px' : '' ?>;

                border-radius: <?php echo esc_attr($setting['box-border-radius']) ?>;

                position: absolute;

                <?php echo esc_attr($setting['position']) ?>: <?php echo esc_attr($setting['vertical']) ?>;
                <?php
                if ($setting['horizontal'] == "left") {
                    echo "left: 0;";
                } else {
                    echo "right: 0;";
                }
                ?>display: flex;
                gap: 2rem;

                background-color: <?php echo esc_attr($setting['box-bg']) ?>;

                opacity: 0;
                visibility: hidden;
                transform: translateY(-7px);

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
                white-space: nowrap;
                font-size: <?php echo esc_attr($setting['title-size']) ?> !important;
                font-family: <?php echo esc_attr($setting['font']) ?> !important;
                color: <?php echo esc_attr($setting['title-color']) ?> !important;
                font-weight: <?php echo esc_attr($setting['title-weight']) ?> !important;
            }

            .insta-popup-container .insta-sub {
                font-size: <?php echo esc_attr($setting['sub-title-size']) ?> !important;
                font-weight: <?php echo esc_attr($setting['sub-title-weight']) ?> !important;
                font-family: <?php echo esc_attr($setting['font']) ?> !important;
                color: <?php echo esc_attr($setting['sub-title-color']) ?> !important;
                line-height: 1.15;
            }

            .insta-popup-container .insta-text {
                margin-top: .5rem !important;
                line-height: 1.3 !important;

                font-size: <?php echo esc_attr($setting['text-size']) ?> !important;
                font-weight: <?php echo esc_attr($setting['text-weight']) ?> !important;
                font-family: <?php echo esc_attr($setting['font']) ?> !important;
                color: <?php echo esc_attr($setting['text-color']) ?> !important;
                /* TODO: setting */
                text-transform: none !important;
            }

            .insta-popup-container .insta-button {
                font-size: <?php echo esc_attr($setting['button-size']) ?> !important;
                color: <?php echo esc_attr($setting['button-color']) ?> !important;
                background: <?php echo esc_attr($setting['button-bg']) ?> !important;
                border-radius: <?php echo esc_attr($setting['button-radius']) ?> !important;
                float: right;
                margin-top: 1rem;

                padding: 8px 24px !important;

                align-self: end;
                text-decoration: none;
                transition: transform .15s ease-out;
                box-shadow: none !important;
            }

            .insta-popup-button {
                cursor: pointer;
                font-size: <?php echo esc_attr($setting['login-font-size']) ?> !important;
                font-weight: <?php echo esc_attr($setting['login-weight']) ?> !important;
                color: <?php echo esc_attr($setting['login-color']) ?> !important;
                background: <?php echo esc_attr($setting['login-bg']) ?> !important;
                border-radius: <?php echo esc_attr($setting['login-radius']) ?> !important;
                padding: <?php echo esc_attr($setting['login-padding']) ?> !important;
            }

            .insta-opener {
                cursor: pointer;
            }

            .insta-popup-icon,
            .insta-popup-icon:hover {
                background: none !important;
                color: <?php echo esc_attr($setting['login-color']) ?> !important;
                border: none !important;

                padding: 0;
                width: <?php echo esc_attr($setting['login-size']) ?>;
                height: <?php echo esc_attr($setting['login-size']) ?>;
            }

            .insta-popup-icon svg {
                fill: <?php echo esc_attr($setting['login-icon-color']) ?>;
            }

            .insta-popup-icon img {
                fill: #1d4264;
                width: 100%;
                height: 100%;
                object-fit: contain;
                object-position: center;
            }

            .insta-popup-logout {
                padding: 0 !important;
                margin: 0 !important;
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
        if (is_user_logged_in() && $preview == false) {
        ?>
            <div style="height: 100%; display: flex; align-items: center;">
                <a class="insta-popup-logout" href="<?php echo esc_attr(wp_logout_url()) ?>">
                    <?php if ($setting['login-type'] == 'text') { ?>
                        <button class="insta-popup-button">
                            <?php echo esc_attr($setting['login-out-text']) ?>
                        </button>
                        <?php } else {

                            InstaloginPopupShortcode::get_logout_icon($logout_icon_setting);
                        
                    } ?>
                </a>
            </div>
        <?php
        } else {
        ?>

            <!-- Mobile -->

            <style>
                @media screen and (min-width: 800px) {
                    .insta-popup-mobile-opener {
                        display: none;
                    }
                }
            </style>

            <!-- <div class="insta-popup-mobile"> -->
            <div class="insta-popup-mobile-opener">
                <?php
                InstaloginPopupShortcode::get_login_icon($icon_setting);
                ?>
            </div>

            <script>
                {
                    const openers = document.querySelectorAll('.insta-popup-mobile-opener');
                    console.log(openers)
                    for (const opener of openers) {
                        opener.addEventListener('click', (event) => {
                            console.log('open app')
                            event.preventDefault();
                            window.open('https://app.instalog.in', '_blank');
                        })
                    }
                }
            </script>
            <!-- </div> -->

            <!-- Desktop -->
            <div class="insta-popup-container">
                <?php if ($setting['login-type'] == 'text') { ?>
                    <button class="insta-opener insta-popup-button">
                        <?php echo esc_attr($setting['login-text']) ?>
                    </button>
                <?php } else {
                    InstaloginPopupShortcode::get_login_icon($icon_setting);
                ?>

                <?php } ?>
                <div class="insta-background"></div>
                <div class="insta-popup">
                    <div class="instalogin-popup" id="<?php echo esc_attr($container_id) ?>"></div>
                    <div class="popup-body">
                        <div>
                            <span class="insta-title"><?php echo esc_html($setting['title-text']) ?></span>
                            <div class="insta-sub"><?php echo $setting['sub-title-text'] ?></div>
                            <p class="insta-text"><?php echo $setting['text-text'] ?></p>
                        </div>

                        <?php if (get_option('users_can_register')) { ?>
                            <a href="<?php echo esc_attr(wp_registration_url()) ?>" class="insta-button"><?php _e('Register', 'instalogin-me') ?></a>
                        <?php } ?>
                    </div>

                </div>
            </div>



        <?php
        }

        return ob_get_clean();
    }

    public static function get_login_icon($icon_setting)
    {
        if ($icon_setting['type'] == 'custom') {
        ?>
            <button class="insta-opener insta-popup-icon">
                <img src="<?php echo esc_attr($icon_setting['custom_src']) ?>" alt="login">
            </button>
        <?php
        } else {
        ?>
            <button class="insta-opener insta-popup-icon">

                <?php if ($icon_setting['type'] == 2) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100.001 100">
                        <path id="Differenzmenge_14" data-name="Differenzmenge 14" d="M45,98A50.013,50.013,0,0,1,25.538,1.929,50.013,50.013,0,0,1,64.462,94.071,49.687,49.687,0,0,1,45,98ZM35.143,41.018c-.078,0-.758.039-.826,1.35a5.635,5.635,0,0,0,.394,2.2,15.146,15.146,0,0,1,.5,1.838c.334,1.715,1.327,1.735,1.337,1.735a33.8,33.8,0,0,0,1.013,4.568,17.994,17.994,0,0,0,1.374,2.615c.012.222.274,5.381-.193,5.942A6.313,6.313,0,0,1,35.835,62.6c-.639.143-1.407.254-2.221.372a22.839,22.839,0,0,0-4.059.833c-5.484,1.932-7.123,4.494-7.533,6.3a.955.955,0,0,0,.188.807.974.974,0,0,0,.764.362H67.317a.962.962,0,0,0,.94-1.169c-.409-1.809-2.049-4.371-7.533-6.3a22.84,22.84,0,0,0-4.06-.834c-.813-.118-1.581-.229-2.221-.372a6.31,6.31,0,0,1-2.9-1.338c-.477-.572-.2-5.888-.193-5.942a17.988,17.988,0,0,0,1.373-2.615,33.867,33.867,0,0,0,1.013-4.568c.012,0,.993-.021,1.338-1.735a15.146,15.146,0,0,1,.5-1.838,5.635,5.635,0,0,0,.394-2.2c-.068-1.312-.749-1.35-.826-1.35h-.005a21.339,21.339,0,0,0,.819-6.183,9.361,9.361,0,0,0-2.659-6.71A10.955,10.955,0,0,0,45.152,25a11.092,11.092,0,0,0-8.165,3.108,9.25,9.25,0,0,0-2.646,6.727,22.312,22.312,0,0,0,.807,6.183v0Z" transform="translate(5 2)" />
                    </svg>


                <?php } else if ($icon_setting['type'] == 3) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100.001 100">
                        <g id="Gruppe_2709" data-name="Gruppe 2709" transform="translate(-675 -1175)">
                            <path id="Differenzmenge_11" data-name="Differenzmenge 11" d="M73.333,98H16.667A21.667,21.667,0,0,1-5,76.334V19.667A21.667,21.667,0,0,1,16.667-2H73.333A21.667,21.667,0,0,1,95,19.667V76.334A21.667,21.667,0,0,1,73.333,98ZM32.983,39.337h0c-.094,0-.922.046-1,1.642a6.851,6.851,0,0,0,.479,2.676,18.427,18.427,0,0,1,.605,2.234C33.469,47.976,34.678,48,34.691,48a40.8,40.8,0,0,0,1.231,5.556,21.821,21.821,0,0,0,1.67,3.181c.014.269.335,6.542-.234,7.226a7.677,7.677,0,0,1-3.533,1.627c-.778.174-1.714.309-2.7.453a27.821,27.821,0,0,0-4.932,1.012c-6.669,2.35-8.663,5.466-9.161,7.666a1.16,1.16,0,0,0,.229.981,1.185,1.185,0,0,0,.929.441H72.111a1.17,1.17,0,0,0,1.143-1.422c-.5-2.2-2.492-5.316-9.161-7.666a27.818,27.818,0,0,0-4.932-1.012c-.991-.143-1.927-.279-2.7-.453a7.677,7.677,0,0,1-3.533-1.627c-.58-.7-.238-7.161-.234-7.226a22.1,22.1,0,0,0,1.67-3.181A41.2,41.2,0,0,0,55.591,48c.014,0,1.207-.025,1.627-2.11a18.427,18.427,0,0,1,.605-2.234,6.851,6.851,0,0,0,.479-2.676c-.083-1.6-.91-1.642-1-1.642h-.006a25.777,25.777,0,0,0,1-7.519,11.384,11.384,0,0,0-3.234-8.16,13.323,13.323,0,0,0-9.9-3.8,13.489,13.489,0,0,0-9.929,3.78,11.252,11.252,0,0,0-3.219,8.181,27.113,27.113,0,0,0,.982,7.519Z" transform="translate(680 1177)" />
                        </g>
                    </svg>

                <?php } else if ($icon_setting['type'] == 4) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                        <g id="Gruppe_2714" data-name="Gruppe 2714" transform="translate(-675 -1300)">
                            <g id="Gruppe_2696" data-name="Gruppe 2696">
                                <path id="Pfad_3617" data-name="Pfad 3617" d="M21.667,6A15.684,15.684,0,0,0,6,21.667V78.333A15.684,15.684,0,0,0,21.667,94H78.333A15.684,15.684,0,0,0,94,78.333V21.667A15.684,15.684,0,0,0,78.333,6H21.667m0-6H78.333A21.667,21.667,0,0,1,100,21.667V78.333A21.667,21.667,0,0,1,78.333,100H21.667A21.667,21.667,0,0,1,0,78.333V21.667A21.667,21.667,0,0,1,21.667,0Z" transform="translate(675 1300)" />
                                <path id="Icon_ionic-ios-person" data-name="Icon ionic-ios-person" d="M60.754,59.363C59.7,54.7,53.675,52.43,51.594,51.7c-2.287-.806-5.54-1-7.637-1.466A7.759,7.759,0,0,1,40.425,48.6c-.586-.7-.235-7.226-.235-7.226A21.741,21.741,0,0,0,41.861,38.2a41.026,41.026,0,0,0,1.231-5.555s1.2,0,1.627-2.111c.454-2.3,1.173-3.2,1.085-4.91-.088-1.686-1.011-1.642-1.011-1.642a25.7,25.7,0,0,0,1-7.519C45.921,10.436,41.2,4.5,32.656,4.5c-8.663,0-13.28,5.936-13.148,11.96a26.805,26.805,0,0,0,.982,7.519s-.923-.044-1.011,1.642c-.088,1.715.63,2.609,1.085,4.91.41,2.111,1.627,2.111,1.627,2.111A41.026,41.026,0,0,0,23.422,38.2a21.741,21.741,0,0,0,1.671,3.181s.352,6.523-.235,7.226a7.759,7.759,0,0,1-3.532,1.627c-2.1.469-5.35.66-7.637,1.466-2.081.733-8.106,3-9.161,7.666a1.172,1.172,0,0,0,1.158,1.422H59.611A1.169,1.169,0,0,0,60.754,59.363Z" transform="translate(692.499 1317.358)" />
                            </g>
                        </g>
                    </svg>
                <?php } else { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                        <g id="Gruppe_2693" data-name="Gruppe 2693" transform="translate(-675 -1425)">
                            <path id="Icon_ionic-ios-person" data-name="Icon ionic-ios-person" d="M50.758,49.614c-.868-3.833-5.822-5.7-7.533-6.3-1.88-.663-4.556-.82-6.28-1.205a6.38,6.38,0,0,1-2.9-1.338c-.482-.579-.193-5.942-.193-5.942a17.877,17.877,0,0,0,1.374-2.615,33.736,33.736,0,0,0,1.012-4.568s.988,0,1.338-1.736c.374-1.892.964-2.628.892-4.038-.072-1.386-.832-1.35-.832-1.35a21.132,21.132,0,0,0,.82-6.183C38.561,9.381,34.68,4.5,27.653,4.5c-7.123,0-10.92,4.881-10.811,9.835a22.042,22.042,0,0,0,.808,6.183s-.759-.036-.832,1.35c-.072,1.41.518,2.145.892,4.038.337,1.736,1.338,1.736,1.338,1.736a33.735,33.735,0,0,0,1.012,4.568,17.877,17.877,0,0,0,1.374,2.615s.289,5.363-.193,5.942a6.38,6.38,0,0,1-2.9,1.338c-1.724.386-4.4.542-6.28,1.205-1.711.6-6.665,2.471-7.533,6.3a.964.964,0,0,0,.952,1.169H49.818A.961.961,0,0,0,50.758,49.614Z" transform="translate(697.499 1447.5)" />
                            <path id="Ellipse_1" data-name="Ellipse 1" d="M50,6A44,44,0,1,0,94,50,44.05,44.05,0,0,0,50,6m0-6A50,50,0,1,1,0,50,50,50,0,0,1,50,0Z" transform="translate(675 1425)" />
                        </g>
                    </svg>

                <?php } ?>
            </button>
        <?php

        }
    }

    public static function get_logout_icon($logout_icon_setting)
    {
        if ($logout_icon_setting['type'] == 'custom') {
        ?>
            <button class="insta-popup-icon">
                <img src="<?php echo esc_attr($logout_icon_setting['custom_src']) ?>" alt="login">
            </button>
        <?php
        } else {
        ?>
            <button class="insta-popup-icon">

                <?php if ($logout_icon_setting['type'] == 99999) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100.001 100">
                        <path id="Differenzmenge_14" data-name="Differenzmenge 14" d="M45,98A50.013,50.013,0,0,1,25.538,1.929,50.013,50.013,0,0,1,64.462,94.071,49.687,49.687,0,0,1,45,98ZM35.143,41.018c-.078,0-.758.039-.826,1.35a5.635,5.635,0,0,0,.394,2.2,15.146,15.146,0,0,1,.5,1.838c.334,1.715,1.327,1.735,1.337,1.735a33.8,33.8,0,0,0,1.013,4.568,17.994,17.994,0,0,0,1.374,2.615c.012.222.274,5.381-.193,5.942A6.313,6.313,0,0,1,35.835,62.6c-.639.143-1.407.254-2.221.372a22.839,22.839,0,0,0-4.059.833c-5.484,1.932-7.123,4.494-7.533,6.3a.955.955,0,0,0,.188.807.974.974,0,0,0,.764.362H67.317a.962.962,0,0,0,.94-1.169c-.409-1.809-2.049-4.371-7.533-6.3a22.84,22.84,0,0,0-4.06-.834c-.813-.118-1.581-.229-2.221-.372a6.31,6.31,0,0,1-2.9-1.338c-.477-.572-.2-5.888-.193-5.942a17.988,17.988,0,0,0,1.373-2.615,33.867,33.867,0,0,0,1.013-4.568c.012,0,.993-.021,1.338-1.735a15.146,15.146,0,0,1,.5-1.838,5.635,5.635,0,0,0,.394-2.2c-.068-1.312-.749-1.35-.826-1.35h-.005a21.339,21.339,0,0,0,.819-6.183,9.361,9.361,0,0,0-2.659-6.71A10.955,10.955,0,0,0,45.152,25a11.092,11.092,0,0,0-8.165,3.108,9.25,9.25,0,0,0-2.646,6.727,22.312,22.312,0,0,0,.807,6.183v0Z" transform="translate(5 2)" />
                    </svg>
                <?php } else { ?>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sign-out-alt" class="svg-inline--fa fa-sign-out-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path>
                    </svg>

                <?php } ?>
            </button>
<?php

        }
    }
}
