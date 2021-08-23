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
                    <form action="options.php" method="post">
                        <?= settings_fields('instalogin'); ?>
                        <?= do_settings_sections('instalogin'); ?>
                        <p>Info Texts</p>
                        <?= submit_button(__('Save Settings', 'instalogin')); ?>
                    </form>
                </div>
            <?php
            }, 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjIiIGJhc2VQcm9maWxlPSJ0aW55LXBzIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIj4KCTx0aXRsZT5OZXcgUHJvamVjdDwvdGl0bGU+Cgk8ZGVmcz4KCQk8aW1hZ2UgIHdpZHRoPSIzMCIgaGVpZ2h0PSIzMiIgaWQ9ImltZzEiIGhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQjRBQUFBZ0JBTUFBQUQzYnRWTUFBQUFBWE5TUjBJQjJja3Nmd0FBQUJoUVRGUkZBQUFBK2ZyNy8vLy80K2ZuNCtqdDd2RHg4L1gxL1AzOXZraStTQUFBQUFoMFVrNVRBTXIvTm1PRXF1KyszVFMxQUFBQXFFbEVRVlI0bkYyUlN4S0RNQXhEQlduTHRzTUpnakpselhBQ3p0SWo5UDZMMmdxWmZMekk4Q0pIVGdTZ21uaWdxUWZKbGczNXFmaDFqcUhnSkZ5NFoxeWRJN1JZelRST3N2Q05KNDMzMjVNdUcrYytOVGhmS0J2aU11Y2NHR3ZQQVdldmIraDFuOS9xZzE5Z2J1ajBCYjArUnd6bjArZ1h1MzU3eFR1L3AraFUyQXVsdjV3M0hZYXVjdWRnUzhoT1A5YzljcVF5eHFOby81Q3cza1BaN2hVMUJHM052UExISHowTkdxTFdTN2x3QUFBQUFFbEZUa1N1UW1DQyIvPgoJPC9kZWZzPgoJPHN0eWxlPgoJCXRzcGFuIHsgd2hpdGUtc3BhY2U6cHJlIH0KCTwvc3R5bGU+Cgk8dXNlIGlkPSJCYWNrZ3JvdW5kIiBocmVmPSIjaW1nMSIgeD0iMSIgeT0iMCIgLz4KPC9zdmc+');
        });

        add_action('admin_init', function () {
            $page = 'instalogin';
            $api_section = 'instalogin-api';

            // Add to wp
            add_settings_section($api_section, __('Instalog.in API Settings', 'instalogin'), function () {
                // Settings Section Title
            }, $page);

            // API Enabled
            $setting_name = 'instalogin-api-enabled';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Enable login via Instalog.in', 'instalogin'), function () {
                $setting_name = 'instalogin-api-enabled';
                $setting = get_option($setting_name); ?>
                <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <?php
            }, $page, $api_section);

            // Registration via API enabled
            $setting_name = 'instalogin-api-registration';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Enable registration via Instalog.in', 'instalogin'), function () {
                $setting_name = 'instalogin-api-registration';
                $setting = get_option($setting_name); ?>
                <input type="checkbox" name="<?= $setting_name ?>" value="1" <?= $setting == 1 ? 'checked' : '' ?> />
            <?php
            }, $page, $api_section);

            // Redirection
            $setting_name = 'instalogin-api-redirect';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('Redirect to after login', "instalogin"), function () {
                $setting_name = 'instalogin-api-redirect';
                $setting = get_option($setting_name); ?>
                <input type="text" placeholder="/wp-admin" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <?php
            }, $page, $api_section);

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
            <?php
            }, $page, $api_section);

            // API Secret
            $setting_name = 'instalogin-api-key';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('API Key', "instalogin"), function () {
                $setting_name = 'instalogin-api-key';
                $setting = get_option($setting_name); ?>
                <input type="text" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
            <?php
            }, $page, $api_section);

            // API Secret
            $setting_name = 'instalogin-api-secret';
            register_setting($page, $setting_name);
            add_settings_field($setting_name . "_field", __('API Secret', "instalogin"), function () {
                $setting_name = 'instalogin-api-secret';
                $setting = get_option($setting_name); ?>
                <input type="password" name="<?= $setting_name ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
<?php
            }, $page, $api_section);
        });
    }
}
