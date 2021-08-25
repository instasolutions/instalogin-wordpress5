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

                    <style>
                        input[type='radio'].tab-title {
                            display: none;
                        }

                        input+label {
                            display: inline-block;
                        }

                        input~.tab {
                            display: none;
                        }

                        #tab1:checked~.tab.content1,
                        #tab2:checked~.tab.content2,
                        #tab3:checked~.tab.content3,
                        #tab4:checked~.tab.content4,
                        #tab5:checked~.tab.content5 {
                            max-width: 30rem;
                            display: grid;
                            grid-template-columns: 1fr 1fr 1fr;
                            gap: .5rem;
                        }

                        input+label {
                            border: 1px solid #999;
                            background: #eee;
                            padding: 4px 12px;
                            border-radius: 4px 4px 0 0;
                            position: relative;
                            top: 1px;
                        }

                        input:checked+label {
                            background: #fff;
                            border-bottom: 1px solid transparent;
                        }

                        input~.tab {
                            border-top: 1px solid #999;
                            padding: 12px;
                        }

                        .insta-container {
                            background: white;
                            padding: 1rem 1.5rem;
                            padding-right: 3rem;
                            margin-bottom: .8rem;
                        }

                        .insta-container .header {
                            display: flex;
                            justify-content: space-between;
                        }

                        .insta-button {
                            font-size: 14px !important;
                            color: white !important;
                            background: #3E84AD !important;
                            border: none;
                            border-radius: 100px;
                            float: right;
                            margin-top: 1rem;
                            font-weight: bold;

                            padding: 8px 24px;

                            align-self: end;
                            text-decoration: none;
                            transition: transform .15s ease-out;
                            box-shadow: none;
                        }

                        .insta-button:hover {
                            transform: scale(1.05);
                            box-shadow: none;
                        }
                    </style>

                    <div class="insta-container">
                        <div class="header">
                            <h1>Instalogin</h1>
                            <div>
                                <button class="insta-button"><?= __('Account Login', 'instalogin') ?></button>
                            </div>
                        </div>
                        <p><?= __('Lorem ipsum dolor sit amet,  eiusmod tempor incididunt ut labore et dolore magna aliqua. Tincidunt vitae semper quis lectus nulla at volutpat diam. Nunc lobortis mattis aliquam faucibus purus in massa tempor.', 'instalogin') ?></p>
                    </div>

                    <form action="options.php" method="post">

                        <?= settings_fields("instalogin") ?>

                        <input type="radio" name="tabs" id="tab1" checked class="tab-title" />
                        <label for="tab1">Basic</label>

                        <input type="radio" name="tabs" id="tab2" class="tab" />
                        <label for="tab2">Lizenz (Key&Secret)</label>

                        <input type="radio" name="tabs" id="tab3" class="tab" />
                        <label for="tab3">Ansicht SmartCode</label>

                        <input type="radio" name="tabs" id="tab4" class="tab" />
                        <label for="tab4">Login Popup</label>

                        <div class="tab content1">
                            <?= do_settings_fields('instalogin', 'instalogin-basic'); ?>
                        </div>

                        <div class="tab content2">
                            <?= do_settings_fields('instalogin', 'instalogin-api'); ?>
                        </div>

                        <div class="tab content3">
                            <?= do_settings_fields('instalogin', 'instalogin-smartcode'); ?>
                        </div>

                        <div class="tab content4">
                            <?= do_settings_fields('instalogin', 'instalogin-popup'); ?>
                        </div>

                        <!-- <?= do_settings_sections('instalogin'); ?> -->
                        <?= submit_button(__('Save Settings', 'instalogin')); ?>
                    </form>
                </div>
            <?php
            }, 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjIiIGJhc2VQcm9maWxlPSJ0aW55LXBzIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIj4KCTx0aXRsZT5OZXcgUHJvamVjdDwvdGl0bGU+Cgk8ZGVmcz4KCQk8aW1hZ2UgIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMiIgaWQ9ImltZzEiIGhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQjRBQUFBZ0JBTUFBQUQzYnRWTUFBQUFBWE5TUjBJQjJja3Nmd0FBQUJoUVRGUkZBQUFBK2ZyNy8vLy80K2ZuNCtqdDd2RHg4L1gxL1AzOXZraStTQUFBQUFoMFVrNVRBTXIvTm1PRXF1KyszVFMxQUFBQXFFbEVRVlI0bkYyUlN4S0RNQXhEQlduTHRzTUpnakpselhBQ3p0SWo5UDZMMmdxWmZMekk4Q0pIVGdTZ21uaWdxUWZKbGczNXFmaDFqcUhnSkZ5NFoxeWRJN1JZelRST3N2Q05KNDMzMjVNdUcrYytOVGhmS0J2aU11Y2NHR3ZQQVdldmIraDFuOS9xZzE5Z2J1ajBCYjArUnd6bjArZ1h1MzU3eFR1L3AraFUyQXVsdjV3M0hZYXVjdWRnUzhoT1A5YzljcVF5eHFOby81Q3cza1BaN2hVMUJHM052UExISHowTkdxTFdTN2x3QUFBQUFFbEZUa1N1UW1DQyIvPgoJPC9kZWZzPgoJPHN0eWxlPgoJCXRzcGFuIHsgd2hpdGUtc3BhY2U6cHJlIH0KCTwvc3R5bGU+Cgk8dXNlIGlkPSJCYWNrZ3JvdW5kIiBocmVmPSIjaW1nMSIgeD0iMSIgeT0iMCIgLz4KPC9zdmc+');
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
            <div>info</div>
        <?php
        }, $page, $section);

        // Registration via API enabled
        $setting_name = 'instalogin-api-registration';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Enable registration via Instalog.in', 'instalogin'), function () {
            $setting_name = 'instalogin-api-registration';
            $setting = get_option($setting_name); ?>
            <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <div>info</div>
        <?php
        }, $page, $section);

        // Redirection
        $setting_name = 'instalogin-api-redirect';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Redirect to after login', "instalogin"), function () {
            $setting_name = 'instalogin-api-redirect';
            $setting = get_option($setting_name); ?>
            <input type="text" placeholder="/wp-admin" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div>info</div>
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
            <input type="text" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div>info</div>
        <?php
        }, $page, $section);

        // API Secret
        $setting_name = 'instalogin-api-secret';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('API Secret', "instalogin"), function () {
            $setting_name = 'instalogin-api-secret';
            $setting = get_option($setting_name); ?>
            <input type="password" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div>info</div>
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
                <option value="qr" <?php selected($setting, 'qr') ?>>QR Code</option>
                <option value="si" <?php selected($setting, 'si') ?>>Smart Image</option>
            </select>
            <div>info</div>
<?php
        }, $page, $section);
    }
}
