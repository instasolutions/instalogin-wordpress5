<?php

class InstaloginSettings
{

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
                return array_merge($links, ['settings' => "<a href='/wp-admin/admin.php?page=instalogin'>" . __("Settings", 'instalogin') . "</a>"]);
            }
            return $links;
        }, 10, 2);
    }

    // Add settings page to admin panel.
    private function main_page()
    {

        add_action('admin_enqueue_scripts', function ($hook) {
            if (strpos($hook, "instalogin") !== false) {
                wp_enqueue_style('insta-settings-style', plugin_dir_url(__FILE__) . "style.css", ['insta-global'], '1');
            }
        });

        add_action('admin_menu', function () {

            add_menu_page('Instalogin Settings', 'InstalogIn', 'manage_options', 'instalogin', function () {
                if (!current_user_can('manage_options')) {
                    return;
                }

                require_once(dirname(__FILE__) . '/header.php');

                // show settings saved info
                if (isset($_GET['settings-updated'])) {
                    add_settings_error('instalogin_messages', 'instalogin_message', __('Settings Saved', 'instalogin'), 'updated');
                }
                // show messages/errors
                settings_errors('instalogin_messages');

                // Render Settings
?>
                <div class="wrap">

                    <style>

                    </style>


                    <form action="options.php" method="post">
                        <?= settings_header() ?>

                        <?= settings_fields("instalogin") ?>

                        <input type="radio" name="tabs" id="tab1" checked class="tab-title" />
                        <label for="tab1"><?= __("Basic Settings", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab2" class="tab" />
                        <label for="tab2"><?= __("License (Key&Secret)", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab3" class="tab" />
                        <label for="tab3"><?= __("SmartCode Type", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab4" class="tab" />
                        <label for="tab4"><?= __("Login Popup Style", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab5" class="tab" />
                        <label for="tab5"><?= __("Usage", 'instalogin') ?></label>

                        <div class="tab content1">
                            <?= do_settings_fields('instalogin', 'instalogin-basic'); ?>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <a class="button red" href="<?= plugin_dir_url(__FILE__) ?>/../../../wizard/"><?= __("Reinstall", 'instalogin') ?></a>
                            <div></div>
                            <div><?= __("Rerun the setup installation wizard.", 'instalogin') ?></div>
                        </div>

                        <div class="tab content2">
                            <?= do_settings_fields('instalogin', 'instalogin-api'); ?>

                            <div>
                                <button id="btn-verify" class="button">Verify Credentials</button>
                                <span id="result" style="height: 100%; display: inline-flex; align-items: center; margin-left: .5rem;"></span>

                                <script>
                                    {
                                        const button = document.querySelector('#btn-verify');
                                        const result = document.querySelector('#result');
                                        const key = document.querySelector('#instalogin-api-key');
                                        const secret = document.querySelector('#instalogin-api-secret');

                                        if (!button) {
                                            console.warn('Key validation: Could not find button.');
                                        } else {

                                            button.addEventListener('click', async (event) => {
                                                if (!result) {
                                                    console.warn('Key validation: Could not find result.');
                                                    return;
                                                }
                                                if (!key) {
                                                    console.warn('Key validation: Could not find key input.');
                                                    return;
                                                }
                                                if (!secret) {
                                                    console.warn('Key validation: Could not find secret input.');
                                                    return;
                                                }

                                                event.preventDefault();
                                                button.disabled = true;

                                                const response = await fetch(
                                                    "/index.php/wp-json/instalogin/v1/verify_credentials", {
                                                        method: "post",
                                                        body: JSON.stringify({
                                                            key: key.value,
                                                            secret: secret.value
                                                        }),
                                                        headers: {
                                                            "Content-Type": "application/json",
                                                            "X-WP-NONCE": "<?= wp_create_nonce('wp_rest') ?>",
                                                        },
                                                    }
                                                );

                                                if (response.ok) {
                                                    result.innerHTML = 'OK';
                                                    result.style.color = 'green';
                                                } else {
                                                    result.innerHTML = "INVALID";
                                                    result.style.color = 'red';
                                                }

                                                button.disabled = false;
                                            });
                                        }

                                    }
                                </script>
                            </div>
                            <div></div>
                            <div></div>
                            <p>
                                <?= __("If you do not have the necessary credentials, you may request them ", 'instalogin') ?>
                                <a href="<?= __("https://instalogin.me/de/keysecret/", 'instalogin') ?>" target="_blank" rel="noopener">here!</a>
                            </p>
                        </div>

                        <div class="tab content3">
                            <?= do_settings_fields('instalogin', 'instalogin-smartcode'); ?>
                        </div>

                        <div class="tab content4">
                            <?= do_settings_fields('instalogin', 'instalogin-popup'); ?>
                        </div>

                        <div class="tab content5">
                            <div style="grid-column: span 3;" class="insta-info">
                                <h3 style="color: var(--insta-blue);"><?= __("Usage", 'instalogin') ?></h3>

                                <p>
                                    <?= __("A Instalogin login code will be added to the wordpress login page automatically.", 'instalogin') ?><br>
                                    <?= __("If <b>'registration via Instalogin'</b> is enabled in the basic settings tab, an email allowing users to connect their account to the InstaApp will be sent out automatically.", 'instalogin') ?>
                                </p>

                                <h4 style="color: var(--insta-blue);"><?= __("Shortcodes", 'instalogin') ?></h4>
                                <p>
                                    <?= __("If you would like to add login and registration options to the frontend of your website you may use these shortcodes:", 'instalogin') ?>
                                </p>

                                <p>
                                    <?= __("<b>[insta-login]</b> adds an InstaCode to your page.", 'instalogin') ?>
                                    <br>
                                    <?= __("Instalogin will use these settings by default as such <b>[insta-login size='100px' show_when_logged_in='false' border='false' ]</b> .", 'instalogin') ?>
                                    <br>
                                    <?= __("Feel free to edit any or all settings.", 'instalogin') ?>
                                </p>

                                <p>
                                    <?= __("<b>[insta-register]</b> adds a simple registration form to your page.", 'instalogin') ?>
                                    <br>
                                    <?= __("With optional default settings: <b>[insta-register require_username='true' show_button='true' button_text='Submit' show_when_logged_in='false' ]</b> .", 'instalogin') ?>
                                    <br>
                                    <?= __("If a username is not required, the users email address will be used.", 'instalogin') ?>
                                </p>

                                <p><?= __("<b>[insta-popup]</b> May be used to add a login popup to a page. Alternatively you can add a popup by adding Insta-PopUp to a menu in <b>Appearance > Customize > Menus</b>.", 'instalogin') ?></p>
                            </div>
                        </div>

                        <!-- <?= do_settings_sections('instalogin'); ?> -->
                        <!-- <?= submit_button(__('Save Settings', 'instalogin')); ?> -->
                        <div class="insta-save-box">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Save Settings', 'instalogin') ?>">
                        </div>
                    </form>
                </div>
            <?php
            }, 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+DQogIDxnIGlkPSJBcHBfSWNvbiIgZGF0YS1uYW1lPSJBcHAgSWNvbiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTE5NyAtNDQwKSI+DQogICAgPHBhdGggaWQ9IlBmYWRfMzU5NiIgZGF0YS1uYW1lPSJQZmFkIDM1OTYiIGQ9Ik0yMS42NjcsMEg3OC4zMzNBMjEuNjY3LDIxLjY2NywwLDAsMSwxMDAsMjEuNjY3Vjc4LjMzM0EyMS42NjcsMjEuNjY3LDAsMCwxLDc4LjMzMywxMDBIMjEuNjY3QTIxLjY2NywyMS42NjcsMCwwLDEsMCw3OC4zMzNWMjEuNjY3QTIxLjY2NywyMS42NjcsMCwwLDEsMjEuNjY3LDBaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxOTcgNDQwKSIgZmlsbD0id2hpdGUiLz4NCiAgICA8ZyBpZD0iR3J1cHBlXzI2NjkiIGRhdGEtbmFtZT0iR3J1cHBlIDI2NjkiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDIyNC4zOTMgNDY0LjM0MSkiPg0KICAgICAgPGcgaWQ9IkdydXBwZV8yNjM3IiBkYXRhLW5hbWU9IkdydXBwZSAyNjM3IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDApIj4NCiAgICAgICAgPGcgaWQ9IktvbXBvbmVudGVfMzVfNzgiIGRhdGEtbmFtZT0iS29tcG9uZW50ZSAzNSDigJMgNzgiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMCkiPg0KICAgICAgICAgIDxwYXRoIGlkPSJQZmFkXzMyNDQiIGRhdGEtbmFtZT0iUGZhZCAzMjQ0IiBkPSJNMzQuODQzLTc3My40MjcsMTQuMTk0LTc2MS41NDhsNi40NTUsMy43MTNhNC40NDUsNC40NDUsMCwwLDAsNC40MzEsMGw5Ljc2My01LjYxNlptOC42NzEtMjMuODY3LTkuOTgtNS43NDEtOC42Nyw0Ljk4OCwyMC44NjYsMTJ2LTcuNDI2YTQuNDEyLDQuNDEyLDAsMCwwLTIuMjE2LTMuODIzWk04LjY3MS03NzcuMzc4di0yMy42MjlsLTYuNDU1LDMuNzEzQTQuNDEzLDQuNDEzLDAsMCwwLDAtNzkzLjQ3djEwLjkzWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCA4MDguMzYyKSIvPg0KICAgICAgICAgIDxwYXRoIGlkPSJQZmFkXzMyNDMiIGRhdGEtbmFtZT0iUGZhZCAzMjQzIiBkPSJNMi4yMTYtNzY3Ljk0N2w5Ljc2Miw1LjYxNSw4LjU1Mi00LjkyTDguNy03NzQuMjkzaC0uMDN2LS4wMThMMC03NzkuNDczdjcuN2E0LjQxMyw0LjQxMywwLDAsMCwyLjIxNiwzLjgyNFptOC42NzEtMjQuMTE5TDMxLjMxOC04MDMuODJsLTYuMjM4LTMuNTg4YTQuNDQzLDQuNDQzLDAsMCwwLTQuNDMxLDBsLTkuNzYzLDUuNjE2Wk00NS43My03ODN2MTEuMjM0YTQuNDEzLDQuNDEzLDAsMCwxLTIuMjE2LDMuODI0bC02LjQ1NSwzLjcxM3YtMjMuNzU5WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCA4MDgpIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz4NCiAgICAgICAgPC9nPg0KICAgICAgPC9nPg0KICAgIDwvZz4NCiAgICA8ZyBpZD0iR3J1cHBlXzI2NzAiIGRhdGEtbmFtZT0iR3J1cHBlIDI2NzAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDIwOC42NjcgNDUxLjY2NykiPg0KICAgICAgPHBhdGggaWQ9IlZlcmVpbmlndW5nc21lbmdlXzQiIGRhdGEtbmFtZT0iVmVyZWluaWd1bmdzbWVuZ2UgNCIgZD0iTTU3Ljc2OSw3Ni42MjRhMS45NTIsMS45NTIsMCwwLDEsMC0zLjloOS41MTRhNS4xODYsNS4xODYsMCwwLDAsNS4xOC01LjE4MVY1OC4wMjVhMS45NTIsMS45NTIsMCwwLDEsMy45LDB2OS41MTRhOS4xLDkuMSwwLDAsMS05LjA4NCw5LjA4NVptLTQ4LjkzNiwwQTkuMSw5LjEsMCwwLDEsMCw2OS42NzVWNTcuMDYzYTEuOTUyLDEuOTUyLDAsMCwxLDMuNjUuOTYydjkuNTE0QTUuMTg4LDUuMTg4LDAsMCwwLDguODMzLDcyLjcyaDkuNTEyYTEuOTUyLDEuOTUyLDAsMCwxLDEuOTUsMS45MTR2LjA3OWExLjk1MSwxLjk1MSwwLDAsMS0xLjk1LDEuOTEyWk03NC4zNzYsMjAuNTUxQTEuOTUzLDEuOTUzLDAsMCwxLDcyLjQ2MiwxOC42VjkuMDg0YTUuMTg1LDUuMTg1LDAsMCwwLTUuMTgtNS4xNzlINTcuNzY5QTEuOTUzLDEuOTUzLDAsMCwxLDU3LjczLDBoOS42MzhhOS4xLDkuMSwwLDAsMSw5LDkuMDg0VjE4LjZhMS45NTQsMS45NTQsMCwwLDEtMS45MTQsMS45NTNabS03Mi43MTcsMEExLjk1LDEuOTUsMCwwLDEsMCwxOS41NjFWNi45NTFBOS4xLDkuMSwwLDAsMSw4Ljc0NCwwaDkuNjM5YTEuOTU0LDEuOTU0LDAsMCwxLDEuOTExLDEuOTE0di4wNzdhMS45NTIsMS45NTIsMCwwLDEtMS45NSwxLjkxNEg4LjgzMUE1LjE4Nyw1LjE4NywwLDAsMCwzLjY1LDkuMDg2VjE4LjZhMS45NTMsMS45NTMsMCwwLDEtMS45MTIsMS45NTNaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwKSIvPg0KICAgIDwvZz4NCiAgPC9nPg0KPC9zdmc+DQo=');
        });

        add_action('admin_init', function () {
            $page = 'instalogin';
            $this->basic_tab($page);
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
        add_settings_section($section, __('Basic', 'instalogin'), function () {
            // Settings Section Title
        }, $page);

        // API Enabled
        $setting_name = 'instalogin-api-enabled';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Enable login via Instalog.in', 'instalogin'), function () {
            $setting_name = 'instalogin-api-enabled';
            $setting = get_option($setting_name); ?>
            <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <div class="insta-info"><?= __("Enable or disable all Instalogin methods. Users may not use Instalogin to sign in to or to create new accounts.", 'instalogin') ?></div>
        <?php
        }, $page, $section);

        // Registration via API enabled
        $setting_name = 'instalogin-api-registration';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Enable registration via Instalogin', 'instalogin'), function () {
            $setting_name = 'instalogin-api-registration';
            $setting = get_option($setting_name); ?>
            <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <div class="insta-info"><?= __("Allow users to create accounts without using a password. Their accounts will be protected by Instalogin.", 'instalogin') ?></div>
        <?php
        }, $page, $section);

        // Redirection
        $setting_name = 'instalogin-api-redirect';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Redirect to after login', "instalogin"), function () {
            $setting_name = 'instalogin-api-redirect';
            $setting = get_option($setting_name); ?>
            <input type="text" placeholder="/wp-admin" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div class="insta-info"><?= __("Decide to which page users should be redirected to after successfully login in. '/wp-admin' is the common directory.", 'instalogin') ?></div>
        <?php
        }, $page, $section);
    }

    private function api_tab($page)
    {
        $section = 'instalogin-api';

        // Add to wp
        add_settings_section($section, __('API', 'instalogin'), function () {
            // Settings Section Title
        }, $page);

        // API Secret
        $setting_name = 'instalogin-api-key';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('API Key', "instalogin"), function () {
            $setting_name = 'instalogin-api-key';
            $setting = get_option($setting_name); ?>
            <input type="text" id="<?= $setting_name ?>" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div class="insta-info"><?= __("Your public API key provided by Instalogin.", 'instalogin') ?></div>
        <?php
        }, $page, $section);

        // API Secret
        $setting_name = 'instalogin-api-secret';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('API Secret', "instalogin"), function () {
            $setting_name = 'instalogin-api-secret';
            $setting = get_option($setting_name); ?>
            <input type="password" id="<?= $setting_name ?>" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div class="insta-info"><?= __("Your private secret provided by Instalogin.", 'instalogin') ?></div>
        <?php
        }, $page, $section);
    }

    private function api_smartcode($page)
    {
        $section = 'instalogin-smartcode';

        // Add to wp
        add_settings_section($section, __('Smartcode', 'instalogin'), function () {
            // Settings Section Title
        }, $page);

        // Use QR Code or Smart Image for login
        $setting_name = 'instalogin-api-type';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Display Type', "instalogin"), function () {
            $setting_name = 'instalogin-api-type';
            $setting = get_option($setting_name); ?>
            <select name="instalogin-api-type">
                <option value="qr" <?php selected($setting, 'qr') ?>>InstaCode</option>
                <option value="si" <?php selected($setting, 'si') ?>>Smart Image</option>
            </select>
            <div class="insta-info" style="grid-row: span 2;"><?= __("During beta smart images must be configured by an Instalogin employee.<br> You may submit a request to <a href='emailto:smartimage@instalogin.me'>smartimage@instalogin.me</a>. <br>Send us a 500px x 500px image an we will do the rest.", 'instalogin') ?></div>
            <div style="grid-column: span 3;">
                <h4>Examples:</h4>
            </div>
            <div style="grid-column: span 3; display: flex;">
                <div>
                    <h5 style="text-align: center;">InstaCode</h5>
                    <img src="<?= plugin_dir_url(__FILE__) ?>../../img/qr.png" alt="">
                </div>
                <div>
                    <h5 style="text-align: center;">SmartImage</h5>
                    <img src="<?= plugin_dir_url(__FILE__) ?>../../img/si.png" alt="">
                </div>
            </div>

<?php
        }, $page, $section);
    }
}
