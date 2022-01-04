<?php

class InstaloginSettings
{

    public $page_suffix = '';

    public function __construct()
    {
        $this->main_page();
        $this->plugins_shortcut();
    }

    private function plugins_shortcut()
    {
        // Settings link in plugin overview on plugins page
        add_filter('plugin_row_meta', function ($links, $file_name) {
            if ($file_name == 'instalogin/plugin.php') {
                return array_merge($links, ['settings' => "<a href='" . admin_url('?page=instalogin') . "'>" . __('Settings', 'instalogin-me') . "</a>"]);
            }
            return $links;
        }, 10, 2);
    }

    // Add settings page to admin panel.
    private function main_page()
    {

        add_action('admin_enqueue_scripts', function ($hook) {
            if ($hook == $this->page_suffix) {
                wp_enqueue_style('insta-settings-style', plugin_dir_url(__FILE__) . "style.css", ['insta-global'], '1');
            }
        });

        add_action('admin_menu', function () {

            $this->page_suffix = add_menu_page('Instalogin Settings', 'InstalogIn', 'manage_options', 'instalogin', function () {
                if (!current_user_can('manage_options')) {
                    return;
                }

                require_once(dirname(__FILE__) . '/header.php');

                // show settings saved info
                if (isset($_GET['settings-updated'])) {
                    add_settings_error('instalogin_messages', 'instalogin_message', __('Settings Saved', 'instalogin-me'), 'updated');
                }
                // show messages/errors
                settings_errors('instalogin_messages');

                // Render Settings
?>
                <div class="wrap">

                    <style>

                    </style>


                    <form action="options.php" method="post">
                        <?php echo settings_header() ?>

                        <?php echo settings_fields("instalogin") ?>

                        <input type="radio" name="tabs" id="tab1" checked class="tab-title" />
                        <label for="tab1"><?php _e("Basic Settings", 'instalogin-me') ?></label>

                        <input type="radio" name="tabs" id="tab2" class="tab" />
                        <label for="tab2"><?php _e("License (Key&Secret)", 'instalogin-me') ?></label>

                        <input type="radio" name="tabs" id="tab3" class="tab" />
                        <label for="tab3"><?php _e("SmartCode Type", 'instalogin-me') ?></label>

                        <input type="radio" name="tabs" id="tab4" class="tab" />
                        <label for="tab4"><?php _e("Login Popup Style", 'instalogin-me') ?></label>

                        <input type="radio" name="tabs" id="tab5" class="tab" />
                        <label for="tab5"><?php _e("Usage", 'instalogin-me') ?></label>

                        <div class="tab content1">
                            <!-- activate/deactive -->
                            <h3 class="insta-h3" style="grid-column: span 3;"><?php _e("Activate / Deactivate", 'instalogin-me') ?></h3>
                            <?php echo do_settings_fields('instalogin', 'instalogin-basic'); ?>
                            <!-- redirect -->
                            <h3 class="insta-h3" style="grid-column: span 3; margin-top: 2.5rem;"><?php _e("Redirection", 'instalogin-me') ?></h3>
                            <?php echo do_settings_fields('instalogin', 'instalogin-basic-redirect'); ?>

                            <!-- icon -->
                            <h3 class="insta-h3" style="grid-column: span 3; margin-top: 2.5rem;"><?php _e("Login Icon (PopUp)", 'instalogin-me') ?></h3>
                            <?php echo do_settings_fields('instalogin', 'instalogin-basic-icon'); ?>

                            <!-- reinstall -->
                            <div class="insta-3-col-s" style="background: transparent; margin-top: 3rem;">
                                <div style="display: flex; align-items: center;">
                                    <a class="insta-button insta-button-red" href="<?php echo esc_attr(admin_url('?page=instalogin-wizard')) ?>"><?php _e("Reinstall", 'instalogin-me') ?></a>
                                </div>
                                <div></div>
                                <div class="insta-info"><?php _e("If you want to restore the default settings just restart the setup and you will be guided through the steps.", 'instalogin-me') ?></div>
                            </div>
                        </div>

                        <div class="tab content2">

                            <h3 class='insta-h3'><?php _e("License Key (Instalogin Key & Secret)", 'instalogin-me') ?></h3>

                            <p class="insta-info" style="max-width: 110ch;"><?php _e("The Key and Secret are needed to secure the communication and login process. At the same time they are the license keys and the connection to the Instalogin servers and both also serve as license keys. If you don't have a Key & Secret from us yet, you can order it here:", 'instalogin-me') ?></p>

                            <div class="license-settings">

                                <div class="insta-2-col">
                                    <div class="insta-settings-label"><?php _e("Status", 'instalogin-me') ?></div>
                                    <div id="status" class="insta-settings-label"></div>
                                </div>
                                <?php echo do_settings_fields('instalogin', 'instalogin-api'); ?>

                                <div class="insta-2-col">
                                    <div></div>
                                    <button id="btn-verify" class="insta-button insta-button-blue-light">Verify Credentials</button>

                                    <script>
                                        {
                                            const button = document.querySelector('#btn-verify');
                                            const key = document.querySelector('#instalogin-api-key');
                                            const secret = document.querySelector('#instalogin-api-secret');
                                            const status = document.querySelector('#status');


                                            async function start() {
                                                const response = await validate(key.value, secret.value);

                                                if (response.ok) {
                                                    status.textContent = "<?php _e("Active", "instalogin-me") ?>";
                                                } else {
                                                    status.textContent = "<?php _e("Inactive", "instalogin-me") ?>";
                                                }
                                            }

                                            async function validate(key, secret) {
                                                return await fetch(
                                                    insta_api + "verify_credentials", {
                                                        method: "post",
                                                        body: JSON.stringify({
                                                            key,
                                                            secret
                                                        }),
                                                        headers: {
                                                            "Content-Type": "application/json",
                                                            "X-WP-NONCE": "<?php echo esc_html(wp_create_nonce('wp_rest')) ?>",
                                                        },
                                                    }
                                                );
                                            }

                                            // check key when loading page
                                            start();

                                            // check on button press
                                            if (!button) {
                                                console.warn('Key validation: Could not find button.');
                                            } else {



                                                button.addEventListener('click', async (event) => {
                                                    event.preventDefault();

                                                    if (!key) {
                                                        console.warn('Key validation: Could not find key input.');
                                                        return;
                                                    }
                                                    if (!secret) {
                                                        console.warn('Key validation: Could not find secret input.');
                                                        return;
                                                    }

                                                    button.disabled = true;

                                                    const response = await validate(key.value, secret.value);

                                                    if (response.ok) {
                                                        button.textContent = "<?php _e("License activated!", "instalogin-me") ?>";
                                                        button.classList.add('insta-button-green');
                                                        button.disabled = true;
                                                        status.textContent = "<?php _e("Active", "instalogin-me") ?>";
                                                    } else {
                                                        button.innerHTML = "<?php _e("Invalid license. Please try again.", "instalogin-me") ?>";
                                                        button.classList.add('insta-button-red');
                                                        button.disabled = false;
                                                        status.textContent = "<?php _e("Inactive", "instalogin-me") ?>";
                                                    }

                                                });
                                            }

                                        }
                                    </script>
                                </div>
                            </div>
                            <div>
                                <a href="https://instalogin.me/shop" rel="noopener" target="_blank" class="insta-button insta-button-blue-light-fill">
                                    <?php _e("Request license key", 'instalogin-me') ?>
                                </a>

                                <a style="margin-left: 1rem;" href="https://instalogin.me/my-account" rel="noopener" target="_blank" class="insta-button insta-button-blue-light">
                                    <?php _e("Extend license", 'instalogin-me') ?>
                                </a>

                            </div>
                        </div>

                        <div class="tab content3">
                            <?php echo do_settings_fields('instalogin', 'instalogin-smartcode'); ?>
                        </div>

                        <div class="tab content4">
                            <?php echo do_settings_fields('instalogin', 'instalogin-popup'); ?>
                        </div>

                        <div class="tab content5">
                            <div style="grid-column: span 3; max-width: initial;" class="insta-info">
                                <h3 class="insta-h3"><?php _e("Usage", 'instalogin-me') ?></h3>

                                <p>
                                    <?php _e("A Instalogin login code will be added to the wordpress login page automatically.", 'instalogin-me') ?><br>
                                    <?php _e("If <b>'registration via Instalogin'</b> is enabled in the basic settings tab, an email allowing users to connect their account to the InstaApp will be sent out automatically.", 'instalogin-me') ?>
                                </p>

                                <h3 class="insta-h3"><?php _e("Shortcodes", 'instalogin-me') ?></h3>
                                <p>
                                    <?php _e("If you would like to add login and registration options to the frontend of your website you may use these shortcodes:", 'instalogin-me') ?>
                                </p>

                                <p>
                                    <b class="insta-b">[insta-login]</b>
                                    <?php _e(" adds an InstaCode to your page.", 'instalogin-me') ?>
                                    <br>
                                    <?php _e("Instalogin will use these settings by default as such <b>[insta-login size='100px' show_when_logged_in='false' border='false' redirect='' ]</b> .", 'instalogin-me') ?>
                                    <br>
                                    <?php _e("Feel free to edit any or all settings.", 'instalogin-me') ?>
                                    <?php _e("Set redirect to any url to send the user to a non default login page like a dashboard or marketing page e.g. '/dashboard' or '/marketing'.","instalogin-me") ?>
                                    <br>
                                    <?php _e("Alternatively: Set redirect to 'stayonpage' to just refresh the page the user is viewing.", "instalogin-me") ?>
                                </p>

                                <p>
                                    <b class="insta-b">[insta-register]</b>
                                    <?php _e(" adds a simple registration form to your page.", 'instalogin-me') ?>
                                    <br>
                                    <?php _e("With optional default settings: <b>[insta-register require_username='true' show_button='true' button_text='Submit' show_when_logged_in='false' ]</b> .", 'instalogin-me') ?>
                                    <br>
                                    <?php _e("If a username is not required, the users email address will be used.", 'instalogin-me') ?>
                                </p>

                                <p>
                                    <b class="insta-b">[insta-popup]</b>
                                    <?php _e(" May be used to add a login popup to a page. Alternatively you can add a popup by adding Insta-PopUp to a menu in <b>Appearance > Customize > Menus</b>.", 'instalogin-me') ?>
                                </p>
                            </div>
                        </div>

                        <div class="insta-save-box">
                            <input type="submit" name="submit" id="submit" class="insta-button insta-cta" value="<?php _e('Save Settings', 'instalogin-me') ?>">
                        </div>
                    </form>
                </div>
            <?php
            }, 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+DQogIDxnIGlkPSJBcHBfSWNvbiIgZGF0YS1uYW1lPSJBcHAgSWNvbiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTE5NyAtNDQwKSI+DQogICAgPHBhdGggaWQ9IlBmYWRfMzU5NiIgZGF0YS1uYW1lPSJQZmFkIDM1OTYiIGQ9Ik0yMS42NjcsMEg3OC4zMzNBMjEuNjY3LDIxLjY2NywwLDAsMSwxMDAsMjEuNjY3Vjc4LjMzM0EyMS42NjcsMjEuNjY3LDAsMCwxLDc4LjMzMywxMDBIMjEuNjY3QTIxLjY2NywyMS42NjcsMCwwLDEsMCw3OC4zMzNWMjEuNjY3QTIxLjY2NywyMS42NjcsMCwwLDEsMjEuNjY3LDBaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxOTcgNDQwKSIgZmlsbD0id2hpdGUiLz4NCiAgICA8ZyBpZD0iR3J1cHBlXzI2NjkiIGRhdGEtbmFtZT0iR3J1cHBlIDI2NjkiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDIyNC4zOTMgNDY0LjM0MSkiPg0KICAgICAgPGcgaWQ9IkdydXBwZV8yNjM3IiBkYXRhLW5hbWU9IkdydXBwZSAyNjM3IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDApIj4NCiAgICAgICAgPGcgaWQ9IktvbXBvbmVudGVfMzVfNzgiIGRhdGEtbmFtZT0iS29tcG9uZW50ZSAzNSDigJMgNzgiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMCkiPg0KICAgICAgICAgIDxwYXRoIGlkPSJQZmFkXzMyNDQiIGRhdGEtbmFtZT0iUGZhZCAzMjQ0IiBkPSJNMzQuODQzLTc3My40MjcsMTQuMTk0LTc2MS41NDhsNi40NTUsMy43MTNhNC40NDUsNC40NDUsMCwwLDAsNC40MzEsMGw5Ljc2My01LjYxNlptOC42NzEtMjMuODY3LTkuOTgtNS43NDEtOC42Nyw0Ljk4OCwyMC44NjYsMTJ2LTcuNDI2YTQuNDEyLDQuNDEyLDAsMCwwLTIuMjE2LTMuODIzWk04LjY3MS03NzcuMzc4di0yMy42MjlsLTYuNDU1LDMuNzEzQTQuNDEzLDQuNDEzLDAsMCwwLDAtNzkzLjQ3djEwLjkzWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCA4MDguMzYyKSIvPg0KICAgICAgICAgIDxwYXRoIGlkPSJQZmFkXzMyNDMiIGRhdGEtbmFtZT0iUGZhZCAzMjQzIiBkPSJNMi4yMTYtNzY3Ljk0N2w5Ljc2Miw1LjYxNSw4LjU1Mi00LjkyTDguNy03NzQuMjkzaC0uMDN2LS4wMThMMC03NzkuNDczdjcuN2E0LjQxMyw0LjQxMywwLDAsMCwyLjIxNiwzLjgyNFptOC42NzEtMjQuMTE5TDMxLjMxOC04MDMuODJsLTYuMjM4LTMuNTg4YTQuNDQzLDQuNDQzLDAsMCwwLTQuNDMxLDBsLTkuNzYzLDUuNjE2Wk00NS43My03ODN2MTEuMjM0YTQuNDEzLDQuNDEzLDAsMCwxLTIuMjE2LDMuODI0bC02LjQ1NSwzLjcxM3YtMjMuNzU5WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCA4MDgpIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz4NCiAgICAgICAgPC9nPg0KICAgICAgPC9nPg0KICAgIDwvZz4NCiAgICA8ZyBpZD0iR3J1cHBlXzI2NzAiIGRhdGEtbmFtZT0iR3J1cHBlIDI2NzAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDIwOC42NjcgNDUxLjY2NykiPg0KICAgICAgPHBhdGggaWQ9IlZlcmVpbmlndW5nc21lbmdlXzQiIGRhdGEtbmFtZT0iVmVyZWluaWd1bmdzbWVuZ2UgNCIgZD0iTTU3Ljc2OSw3Ni42MjRhMS45NTIsMS45NTIsMCwwLDEsMC0zLjloOS41MTRhNS4xODYsNS4xODYsMCwwLDAsNS4xOC01LjE4MVY1OC4wMjVhMS45NTIsMS45NTIsMCwwLDEsMy45LDB2OS41MTRhOS4xLDkuMSwwLDAsMS05LjA4NCw5LjA4NVptLTQ4LjkzNiwwQTkuMSw5LjEsMCwwLDEsMCw2OS42NzVWNTcuMDYzYTEuOTUyLDEuOTUyLDAsMCwxLDMuNjUuOTYydjkuNTE0QTUuMTg4LDUuMTg4LDAsMCwwLDguODMzLDcyLjcyaDkuNTEyYTEuOTUyLDEuOTUyLDAsMCwxLDEuOTUsMS45MTR2LjA3OWExLjk1MSwxLjk1MSwwLDAsMS0xLjk1LDEuOTEyWk03NC4zNzYsMjAuNTUxQTEuOTUzLDEuOTUzLDAsMCwxLDcyLjQ2MiwxOC42VjkuMDg0YTUuMTg1LDUuMTg1LDAsMCwwLTUuMTgtNS4xNzlINTcuNzY5QTEuOTUzLDEuOTUzLDAsMCwxLDU3LjczLDBoOS42MzhhOS4xLDkuMSwwLDAsMSw5LDkuMDg0VjE4LjZhMS45NTQsMS45NTQsMCwwLDEtMS45MTQsMS45NTNabS03Mi43MTcsMEExLjk1LDEuOTUsMCwwLDEsMCwxOS41NjFWNi45NTFBOS4xLDkuMSwwLDAsMSw4Ljc0NCwwaDkuNjM5YTEuOTU0LDEuOTU0LDAsMCwxLDEuOTExLDEuOTE0di4wNzdhMS45NTIsMS45NTIsMCwwLDEtMS45NSwxLjkxNEg4LjgzMUE1LjE4Nyw1LjE4NywwLDAsMCwzLjY1LDkuMDg2VjE4LjZhMS45NTMsMS45NTMsMCwwLDEtMS45MTIsMS45NTNaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwKSIvPg0KICAgIDwvZz4NCiAgPC9nPg0KPC9zdmc+DQo=');
        });

        add_action('admin_init', function () {
            $page = 'instalogin';
            $this->basic_tab($page);
            $this->basic_tab_redirect($page);
            $this->basic_tab_icon($page);
            $this->api_tab($page);
            $this->api_smartcode($page);

            require_once(dirname(__FILE__) . '/../popup/settings.php');
            new InstaloginPopupSettings($page);
        });
    }

    private function basic_tab($page)
    {
        $section = 'instalogin-basic';

        // Add to wp
        add_settings_section($section, __('Basic', 'instalogin-me'), function () {
            // Settings Section Title
        }, $page);

        // API Enabled
        $setting_name = 'instalogin-api-enabled';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-api-enabled';
            $setting = get_option($setting_name); ?>

            <div class="insta-3-col-s">
                <div class="insta-settings-label"><?php _e("Enable login via Instalogin", 'instalogin-me') ?></div>
                <div>
                    <input type="checkbox" name="<?php echo esc_attr($setting_name) ?>" value="1" <?php echo esc_attr($setting) == 1 ? 'checked' : '' ?> />
                </div>
                <div class="insta-info"><?php _e("Enable or disable all Instalogin methods. Users may not use Instalogin to sign in to or to create new accounts.", 'instalogin-me') ?></div>
            </div>
        <?php
        }, $page, $section);

        // Registration via API enabled
        $setting_name = 'instalogin-api-registration';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-api-registration';
            $setting = get_option($setting_name); ?>

            <div class="insta-3-col-s">
                <div class="insta-settings-label"><?php _e("Activation on Register", 'instalogin-me') ?></div>
                <div>
                    <input type="checkbox" name="<?php echo esc_attr($setting_name) ?>" value="1" <?php echo esc_attr($setting) == 1 ? 'checked' : '' ?> />
                </div>
                <div class="insta-info"><?php _e("An Instalogin mail will be sent to every new user upon registration.", 'instalogin-me') ?></div>
            </div>
        <?php
        }, $page, $section);
    }

    private function basic_tab_redirect($page)
    {
        $section = 'instalogin-basic-redirect';

        add_settings_section($section, __('Basic-Redirect', 'instalogin-me'), function () {
            // Settings Section Title
        }, $page);

        // Redirection
        $setting_name = 'instalogin-api-redirect';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-api-redirect';
            $setting = get_option($setting_name); ?>
            <div class="insta-3-col-s">

                <div class="insta-settings-label"><?php _e("Redirect to after login", 'instalogin-me') ?></div>
                <div></div>
                <div class="insta-info">
                    <?php _e("Decide to which page users should be redirected to after successfully logging in. '/wp-admin' is the common directory.", 'instalogin-me') ?>
                    <input type="text" placeholder="/wp-admin" name="<?php echo esc_attr($setting_name) ?>" value="<?php echo esc_attr($setting); ?>" />
                </div>
            </div>
        <?php
        }, $page, $section);
    }

    private function basic_tab_icon($page)
    {
        $section = 'instalogin-basic-icon';

        add_settings_section($section, __('Basic-Redirect', 'instalogin-me'), function () {
            // Settings Section Title
        }, $page);

        // Custom Item
        $setting_name = 'instalogin-popup-icon';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-popup-icon';
            $setting = get_option($setting_name, [
                'type' => 1,
                'custom_src' => ''
            ]);

        ?>
            <style>
                .insta-icon-select-option {
                    display: flex;
                    flex-flow: column;
                    align-items: center;
                    gap: 1rem;
                }
            </style>
            <div class="insta-3-col-s">


                <div class="insta-settings-label"><?php _e("Icon shown in the login popup", 'instalogin-me') ?></div>
                <div></div>
                <div class="media-selector">
                    <div class="insta-info">
                        <?php _e('Choose a login icon which will be integrated into your navigation in the menu or in the footer. You can make further settings under "Login Popup Style", such as size and color, to adapt the icon to your style.', 'instalogin-me') ?>
                    </div>
                    <div style="display: flex; gap: 1.2rem; margin-top: 1rem;">

                        <div class="insta-icon-select-option">
                            <img width="30px" src="<?php echo plugin_dir_url(__FILE__) . "../../img/login1.svg"; ?>" alt="logo1">
                            <input type="radio" name="<?php echo esc_attr($setting_name) ?>[type]" value="1" <?php echo esc_attr($setting['type']) == 1 ? 'checked' : '' ?> />
                        </div>
                        <div class="insta-icon-select-option">
                            <img width="30px" src="<?php echo plugin_dir_url(__FILE__) . "../../img/login2.svg"; ?>" alt="logo1">
                            <input type="radio" name="<?php echo esc_attr($setting_name) ?>[type]" value="2" <?php echo esc_attr($setting['type']) == 2 ? 'checked' : '' ?> />
                        </div>
                        <div class="insta-icon-select-option">
                            <img width="30px" src="<?php echo plugin_dir_url(__FILE__) . "../../img/login3.svg"; ?>" alt="logo1">
                            <input type="radio" name="<?php echo esc_attr($setting_name) ?>[type]" value="3" <?php echo esc_attr($setting['type']) == 3 ? 'checked' : '' ?> />
                        </div>
                        <div class="insta-icon-select-option">
                            <img width="30px" src="<?php echo plugin_dir_url(__FILE__) . "../../img/login4.svg"; ?>" alt="logo1">
                            <input type="radio" name="<?php echo esc_attr($setting_name) ?>[type]" value="4" <?php echo esc_attr($setting['type']) == 4 ? 'checked' : '' ?> />
                        </div>

                        <div class="insta-icon-select-option">
                            <style>
                                /* Hide Chrome "image missing" icon error. TODO: move to css */
                                img {
                                    position: relative;
                                }

                                img[alt]:after {
                                    display: block;
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                    background-color: #fff;
                                    font-family: 'Helvetica';
                                    font-weight: 300;
                                    line-height: 2;
                                    text-align: center;
                                    content: attr(alt);
                                }
                            </style>
                            <?php
                            echo '<img id="selected-media" width="30px" height="30px" style="border: none;" src="' . esc_attr($setting["custom_src"]) . '" alt=" ">';
                            ?>

                            <input type="radio" name="<?php echo esc_attr($setting_name) ?>[type]" value="custom" <?php echo esc_attr($setting['type']) == 'custom' ? 'checked' : '' ?> />
                        </div>

                        <div>
                            <button class="button" style="border: 1px solid #707070; color: #707070; border-radius: 4px; font-size: 14px; font-weight: bold;"><?php _e("Select custom icon", 'instalogin-me') ?></button>
                            <input type="hidden" name="<?php echo esc_attr($setting_name) ?>[custom_src]" value="<?php echo esc_attr($setting['custom_src']) ?>">
                            <!-- <img width="30px" style="border: none;" src="<?php echo esc_attr($setting['custom_src']) ?>" alt=""> -->
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }, $page, $section);
    }

    private function api_tab($page)
    {
        $section = 'instalogin-api';

        add_settings_section($section, __('API', 'instalogin-me'), function () {
            // Settings Section Title
        }, $page);

        // API Secret
        $setting_name = 'instalogin-api-key';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-api-key';
            $setting = get_option($setting_name); ?>
            <div class="insta-2-col">
                <div class="insta-settings-label"><?php _e("API Key", 'instalogin-me') ?></div>
                <div>
                    <input type="text" id="<?php echo esc_attr($setting_name) ?>" name="<?php echo esc_attr($setting_name) ?>" value="<?php echo esc_attr($setting); ?>" />
                </div>
            </div>
        <?php
        }, $page, $section);

        // API Secret
        $setting_name = 'instalogin-api-secret';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-api-secret';
            $setting = get_option($setting_name); ?>
            <div class="insta-2-col">
                <div class="insta-settings-label"><?php _e("API Secret", 'instalogin-me') ?></div>
                <div>
                    <input type="password" id="<?php echo esc_attr($setting_name) ?>" name="<?php echo esc_attr($setting_name) ?>" value="<?php echo esc_attr($setting); ?>" />
                </div>
            </div>
        <?php
        }, $page, $section);
    }

    private function api_smartcode($page)
    {
        $section = 'instalogin-smartcode';

        // Add to wp
        add_settings_section($section, __('Smartcode', 'instalogin-me'), function () {
            // Settings Section Title
        }, $page);

        // Use QR Code or Smart Image for login
        $setting_name = 'instalogin-api-type';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-api-type';
            $setting = get_option($setting_name); ?>


            <div style="background: #F5F5F5; padding: 1rem 1rem;">
                <div class="insta-3-col-l">
                    <div class="insta-settings-label"><?php _e("InstaCode at login", 'instalogin-me') ?></div>
                    <div>
                        <select name="instalogin-api-type">
                            <option value="qr" <?php selected($setting, 'qr') ?>>InstaCode</option>
                            <option value="si" <?php selected($setting, 'si') ?>>Smart Image</option>
                        </select>
                    </div>
                    <div class="insta-info" style="grid-row: span 2;"><?php _e("Should the InstaCode be displayed in QR format or as a smart image, e.g. your logo?", 'instalogin-me') ?></div>
                </div>

                <!-- TODO: proper settings -->

                <div class="insta-3-col-l">
                    <h3 style="margin: 0; color: var(--insta-new-blue);">
                        COMING SOON
                    </h3>
                </div>

                <div class=" insta-3-col-l" style="opacity: .5;">
                    <div class="insta-settings-label"><?php _e("Upload smartimage", 'instalogin-me') ?></div>
                    <div>
                        <select name="instalogin-api-type">
                            <option value="">...</option>
                        </select>
                    </div>
                    <div class="insta-info" style="grid-row: span 2;"><?php _e("To use your logo or other image as SmartCode, please upload a PNG with 500x500 pixels and maximum 4 colors.", 'instalogin-me') ?></div>
                </div>

                <div class="insta-3-col-l" style="opacity: .5;">
                    <div class="insta-settings-label"><?php _e("Upload icon", 'instalogin-me') ?></div>
                    <div>
                        <select name="instalogin-api-type">
                            <option value="">...</option>
                        </select>
                    </div>
                    <div class="insta-info" style="grid-row: span 2;"><?php _e("Upload your app icon. The icon will be displayed on your account in the app. The icon should be a PNG with 500x500 pixels.", 'instalogin-me') ?></div>
                </div>


            </div>

            <h3 class="insta-h3" style="margin-top: 2rem;"><?php _e("Examples", 'instalogin-me') ?></h3>
            <div class="insta-info" style="grid-row: span 2; max-width: 110ch;"><?php _e("During the beta phase, Smart Images must be configured by an Instalogin representative. You can submit a request to <a href='mailto:support@instalogin.me'>support@instalogin.me</a>. Send us a 500px x 500px image of your logo or an image and we'll do the rest.", 'instalogin-me') ?></div>

            <div>
                <div style="display: flex; gap: 6rem; color: var(--insta-blue-darker); font-size: 14px; font-weight: bold;">
                    <div>
                        <h4 style="">InstaCode</h4>
                        <img height="200px" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../../img/qr.png" alt="">
                    </div>
                    <div>
                        <h4 style="">SmartImage</h4>
                        <img height="200px" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../../img/si.png" alt="">
                    </div>
                </div>
            </div>


<?php
        }, $page, $section);
    }
}
