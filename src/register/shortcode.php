<?php

class InstaloginRegisterShortcode
{
    public function __construct()
    {
        add_shortcode('insta-register', function ($attributes = [], $content = null) {
            $api_enabled = get_option('instalogin-api-enabled');
            if ($api_enabled != 1) {
                return false;
            }

            // SETTINGS
            $attributes = shortcode_atts([
                'require_username' => "true",
                'show_button' => "true",
                'button_text' => __("Submit", 'instalogin-me'),
                'show_when_logged_in' => "false",
            ], $attributes, 'insta-register');

            $show_when_logged_in = $attributes['show_when_logged_in'] == 'true';
            $require_username = $attributes['require_username'] == 'true';
            $show_button = $attributes['show_button'] == 'true';
            $button_text = $attributes['button_text'];

            if (!$show_when_logged_in && is_user_logged_in()) {
                return '';
            }

            // SCRIPTS
            wp_enqueue_style('instalogin-login', plugin_dir_url(__FILE__) . '../../style/form.css?v=3');
            wp_enqueue_script('instalogin-register', plugin_dir_url(__FILE__) . '../../scripts/register.js?v=1', ['wp-i18n']);

            // RENDER
            ob_start(); ?>
            <div class="instalogin-register">

                <?php if ($require_username) { ?>
                    <label>
                        <span class="instalogin-label"><?php _e("Username", 'instalogin-me') ?></span>
                        <input type="text" required class="instalogin-username" class="instalogin-input">
                    </label>
                <?php } ?>

                <label>
                    <span class="instalogin-label"><?php _e("Email", 'instalogin-me') ?></span>
                    <input type="email" required class="instalogin-email" class="instalogin-input">
                </label>

                <?php if ($show_button) { ?>
                    <div class="instalogin-submit"><?php echo esc_attr($button_text) ?></div>
                <?php } ?>

                <p class="instalogin-error"></p>
                <p class="instalogin-info"></p>
            </div>
<?php
            return ob_get_clean();
        });
    }
}
