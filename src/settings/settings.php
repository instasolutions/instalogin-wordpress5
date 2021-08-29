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
                wp_enqueue_style('insta-settings-style', plugin_dir_url(__FILE__) . "style.css", [], '1');
            }
        });

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

                    </style>


                    <form action="options.php" method="post">
                        <div class="insta-container">
                            <div class="insta-header">
                                <svg class="insta-logo" viewbox="0 0 300 60">
                                    <path fill="#3E84AD" d="M49.78,27.31v12.27c0,1.72-0.92,3.32-2.41,4.18l-7.03,4.06V21.86L49.78,27.31z M9.44,33.99V8.17  l-7.03,4.06C0.92,13.09,0,14.69,0,16.41v11.94L9.44,33.99z M47.37,12.23L36.5,5.96l-9.44,5.45l22.71,13.11v-8.11  C49.78,14.69,48.86,13.09,47.37,12.23z M11.85,17.41L34.09,4.57L27.3,0.65c-1.49-0.86-3.33-0.86-4.82,0L11.85,6.78V17.41z   M37.93,38.31L15.45,51.29l7.03,4.06c1.49,0.86,3.33,0.86,4.82,0l10.63-6.14V38.31z M2.41,43.76l10.63,6.14l9.31-5.37L9.47,36.83  H9.44v-0.02L0,31.17v8.41C0,41.3,0.92,42.9,2.41,43.76z M70.66,14.21c-0.69,0.66-1.63,0.99-2.82,0.99c-1.1,0-1.97-0.33-2.62-0.99  c-0.65-0.66-0.97-1.53-0.97-2.62s0.33-1.96,0.99-2.62c0.66-0.66,1.53-0.99,2.61-0.99c1.19,0,2.13,0.33,2.82,0.99  c0.69,0.66,1.03,1.53,1.03,2.62S71.35,13.55,70.66,14.21z M71.23,41.89h-6.5V18.32h6.5V41.89z M83.29,21.2  c0.19-0.25,0.45-0.56,0.8-0.93s1.1-0.86,2.27-1.49c1.17-0.63,2.39-0.94,3.67-0.94c2.69,0,4.89,0.93,6.59,2.79  c1.7,1.86,2.55,4.38,2.55,7.54v13.71h-6.48V28.91c0-1.44-0.43-2.6-1.28-3.49c-0.85-0.88-1.95-1.33-3.3-1.33  c-1.47,0-2.64,0.48-3.51,1.44c-0.87,0.96-1.3,2.33-1.3,4.1v12.26h-6.48V18.32h6.48L83.29,21.2z M117.49,25.29  c-0.09-0.13-0.25-0.3-0.46-0.52c-0.21-0.22-0.65-0.5-1.33-0.86c-0.67-0.35-1.36-0.53-2.07-0.53c-0.89,0-1.6,0.19-2.11,0.58  c-0.52,0.38-0.77,0.83-0.77,1.33c0,0.61,0.37,1.12,1.1,1.53c0.74,0.41,1.63,0.77,2.69,1.09c1.06,0.32,2.12,0.7,3.2,1.15  s1.99,1.17,2.72,2.16c0.74,0.99,1.1,2.23,1.1,3.7c0,2.11-0.83,3.88-2.5,5.31c-1.67,1.42-3.96,2.14-6.87,2.14  c-1.25,0-2.44-0.15-3.57-0.45s-2.04-0.66-2.75-1.08c-0.7-0.42-1.32-0.84-1.84-1.26c-0.52-0.41-0.9-0.76-1.14-1.04l-0.33-0.49  l3.62-3.59c0.13,0.19,0.32,0.43,0.6,0.72c0.27,0.29,0.85,0.68,1.73,1.17c0.88,0.49,1.78,0.74,2.72,0.74c1.03,0,1.81-0.2,2.34-0.6  c0.52-0.4,0.79-0.92,0.79-1.56s-0.37-1.18-1.1-1.61c-0.74-0.43-1.64-0.81-2.72-1.13c-1.08-0.32-2.15-0.71-3.21-1.16  c-1.06-0.45-1.95-1.16-2.69-2.11c-0.74-0.95-1.1-2.16-1.1-3.62c0-1.89,0.88-3.61,2.64-5.14c1.76-1.53,3.93-2.3,6.49-2.3  c1.63,0,3.13,0.31,4.5,0.92c1.37,0.61,2.32,1.21,2.85,1.78l0.82,0.92L117.49,25.29z M128.36,11.37h5.78v7.44h4.79v5.78h-4.79v9.37  c0,0.74,0.27,1.36,0.81,1.88s1.22,0.77,2.05,0.77c0.42,0,0.83-0.04,1.23-0.13c0.4-0.09,0.7-0.18,0.9-0.27l0.28-0.09v5.54  c-1.28,0.49-2.73,0.72-4.34,0.7c-4.96,0-7.44-2.72-7.44-8.17v-9.6h-4.09v-5.78h2.16c0.74,0,1.36-0.29,1.88-0.87  c0.52-0.58,0.77-1.33,0.77-2.25V11.37z M157.15,39.49c-0.19,0.22-0.45,0.49-0.79,0.81c-0.34,0.32-1.07,0.74-2.2,1.27  c-1.13,0.52-2.3,0.79-3.52,0.79c-2.35,0-4.26-0.65-5.73-1.96c-1.47-1.31-2.21-2.89-2.21-4.75c0-1.93,0.62-3.53,1.85-4.81  c1.24-1.28,2.94-2.12,5.12-2.51l7.47-1.34c0-0.99-0.36-1.79-1.08-2.42c-0.72-0.63-1.72-0.94-3.01-0.94c-1.06,0-2.03,0.2-2.91,0.6  c-0.88,0.4-1.49,0.81-1.85,1.23l-0.54,0.56l-3.59-3.59c0.09-0.13,0.22-0.29,0.39-0.5c0.16-0.21,0.53-0.58,1.1-1.09  c0.57-0.52,1.19-0.97,1.87-1.37c0.67-0.4,1.56-0.77,2.65-1.1s2.21-0.5,3.36-0.5c2.97,0,5.4,0.89,7.28,2.66  c1.88,1.78,2.82,4.01,2.82,6.7v14.67h-6.48L157.15,39.49z M155.84,35.68c0.87-0.93,1.3-2.23,1.3-3.89v-0.73l-5.28,0.96  c-1.77,0.28-2.65,1.17-2.65,2.65c0,1.6,1.04,2.39,3.12,2.39C153.81,37.08,154.98,36.61,155.84,35.68z M174.89,41.89h-6.5V8.22h6.5  V41.89z M200.03,38.68c-2.48,2.45-5.4,3.67-8.77,3.67c-3.37,0-6.29-1.22-8.78-3.67c-2.49-2.45-3.73-5.31-3.73-8.58  c0-3.27,1.24-6.13,3.73-8.58c2.49-2.45,5.42-3.67,8.78-3.67c3.37,0,6.29,1.22,8.77,3.67c2.48,2.45,3.72,5.31,3.72,8.58  C203.75,33.37,202.51,36.23,200.03,38.68z M187.2,34.55c1.14,1.2,2.5,1.8,4.06,1.8s2.92-0.6,4.06-1.8c1.14-1.2,1.71-2.68,1.71-4.44  c0-1.76-0.57-3.24-1.71-4.45c-1.14-1.21-2.5-1.81-4.06-1.81s-2.92,0.6-4.06,1.81c-1.14,1.21-1.71,2.69-1.71,4.45  C185.49,31.88,186.06,33.35,187.2,34.55z M224.32,38.04c-0.06,0.09-0.16,0.22-0.29,0.36c-0.13,0.15-0.41,0.41-0.85,0.8  c-0.43,0.38-0.89,0.73-1.39,1.03c-0.49,0.31-1.14,0.58-1.95,0.82c-0.81,0.24-1.64,0.36-2.5,0.36c-2.69,0-5.13-1.17-7.31-3.51  c-2.18-2.34-3.28-5.1-3.28-8.28c0-3.18,1.09-5.94,3.28-8.28c2.18-2.34,4.62-3.51,7.31-3.51c1.35,0,2.61,0.28,3.78,0.85  c1.17,0.56,2,1.11,2.47,1.64l0.73,0.87v-2.89h6.48v21.65c0,3.4-1.19,6.25-3.56,8.56c-2.37,2.31-5.35,3.46-8.93,3.46  c-1.35,0-2.64-0.18-3.87-0.53c-1.24-0.35-2.26-0.77-3.08-1.24c-0.81-0.48-1.53-0.97-2.14-1.47c-0.61-0.5-1.04-0.92-1.29-1.27  l-0.45-0.54l4.09-3.85c0.16,0.22,0.4,0.51,0.72,0.87c0.32,0.36,1.04,0.83,2.17,1.4c1.13,0.57,2.33,0.86,3.62,0.86  c1.93,0,3.45-0.56,4.57-1.68c1.12-1.12,1.68-2.64,1.68-4.57V38.04z M215.04,33.77c1.03,1.1,2.28,1.64,3.76,1.64s2.77-0.56,3.87-1.69  c1.1-1.13,1.66-2.49,1.66-4.1c0-1.6-0.55-2.97-1.66-4.09c-1.1-1.12-2.39-1.68-3.87-1.68s-2.73,0.54-3.76,1.63  c-1.03,1.09-1.54,2.47-1.54,4.13C213.5,31.29,214.01,32.67,215.04,33.77z M242.05,14.21c-0.69,0.66-1.63,0.99-2.82,0.99  c-1.1,0-1.97-0.33-2.62-0.99c-0.65-0.66-0.97-1.53-0.97-2.62s0.33-1.96,0.99-2.62c0.66-0.66,1.53-0.99,2.61-0.99  c1.19,0,2.13,0.33,2.82,0.99c0.69,0.66,1.03,1.53,1.03,2.62S242.74,13.55,242.05,14.21z M242.61,41.89h-6.5V18.32h6.5V41.89z   M254.68,21.2c0.19-0.25,0.45-0.56,0.8-0.93c0.34-0.37,1.1-0.86,2.27-1.49c1.17-0.63,2.39-0.94,3.67-0.94  c2.69,0,4.89,0.93,6.59,2.79c1.7,1.86,2.55,4.38,2.55,7.54v13.71h-6.48V28.91c0-1.44-0.43-2.6-1.28-3.49  c-0.85-0.88-1.95-1.33-3.3-1.33c-1.47,0-2.64,0.48-3.51,1.44c-0.87,0.96-1.3,2.33-1.3,4.1v12.26h-6.48V18.32h6.48L254.68,21.2z   M282.25,40.7c-0.81,0.8-1.82,1.2-3.01,1.2c-1.2,0-2.2-0.4-3.01-1.2c-0.81-0.8-1.22-1.77-1.22-2.92s0.41-2.12,1.22-2.92  s1.82-1.2,3.01-1.2c1.2,0,2.2,0.4,3.01,1.2s1.22,1.77,1.22,2.92S283.06,39.9,282.25,40.7z M281.58,35.43  c-0.65-0.65-1.43-0.97-2.35-0.97c-0.91,0-1.69,0.32-2.34,0.97s-0.97,1.43-0.97,2.35s0.32,1.7,0.97,2.35  c0.65,0.65,1.43,0.97,2.34,0.97c0.91,0,1.7-0.32,2.35-0.97c0.65-0.65,0.97-1.43,0.97-2.35S282.23,36.08,281.58,35.43z M281.13,39.9  h-0.92l-0.86-1.6h-0.74v1.6h-0.86v-4.23h1.78c0.39,0,0.73,0.14,1.01,0.41s0.42,0.59,0.42,0.97c0,0.23-0.07,0.44-0.2,0.63  s-0.27,0.31-0.4,0.37l-0.21,0.09L281.13,39.9z M278.61,37.49h0.92c0.17,0,0.3-0.05,0.41-0.15s0.16-0.22,0.16-0.37  s-0.05-0.27-0.16-0.37c-0.11-0.1-0.24-0.15-0.41-0.15h-0.92V37.49z"></path>
                                </svg>
                                <div>
                                    <button class="insta-button"><?= __('Account Login', 'instalogin') ?></button>
                                </div>
                            </div>
                            <p><?= __('Instalogin enables secure authentication by scanning of the InstaCode instead of using a combination of password and username.<br>Forgetting your password is a relic of the past - there are none!', 'instalogin') ?></p>
                        </div>

                        <?= settings_fields("instalogin") ?>

                        <input type="radio" name="tabs" id="tab1" checked class="tab-title" />
                        <label for="tab1"><?= __("Basic Settings", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab2" class="tab" />
                        <label for="tab2"><?= __("License (Key&Secret)", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab3" class="tab" />
                        <label for="tab3"><?= __("SmartCode Type", 'instalogin') ?></label>

                        <input type="radio" name="tabs" id="tab4" class="tab" />
                        <label for="tab4"><?= __("Login Popup Style", 'instalogin') ?></label>

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
                        <!-- <?= submit_button(__('Save Settings', 'instalogin')); ?> -->
                        <div class="insta-save-box">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Save Settings', 'instalogin') ?>">
                        </div>
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
            <div class="insta-info"><?= __("Enable or disable all Instalogin methods. Users may not use Instalogin to sign in to or to create new accounts.", 'instalogin') ?></div>
        <?php
        }, $page, $section);

        // Registration via API enabled
        $setting_name = 'instalogin-api-registration';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('Enable registration via Instalog.in', 'instalogin'), function () {
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
            <input type="text" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <div class="insta-info"><?= __("Your public API key provided by Instalogin.", 'instalogin') ?></div>
        <?php
        }, $page, $section);

        // API Secret
        $setting_name = 'instalogin-api-secret';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('API Secret', "instalogin"), function () {
            $setting_name = 'instalogin-api-secret';
            $setting = get_option($setting_name); ?>
            <input type="password" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
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
                <option value="qr" <?php selected($setting, 'qr') ?>>QR Code</option>
                <option value="si" <?php selected($setting, 'si') ?>>Smart Image</option>
            </select>
            <div class="insta-info"><?= __("You may set a custom smart image on your Instalgin <a href='#'>account configuration</a> page.", 'instalogin') ?></div>
<?php
        }, $page, $section);
    }
}
