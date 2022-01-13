<?php

if (!defined('ABSPATH')) {
    exit;
}


class InstaloginLoginShortcode
{
    public function __construct()
    {
        add_shortcode('insta-login', function ($attributes = [], $content = null) {
            // API disabled via settings?
            $api_enabled = get_option('instalogin-api-enabled');
            if ($api_enabled != 1) {
                return '';
            }

            // SETTINGS
            $attributes = shortcode_atts([
                'size' => '168px',
                'show_when_logged_in' => "false",
                'border' => "false",
                'redirect' => '',
                'login_page' => 'false',
            ], $attributes, 'insta-login');

            $size = $attributes['size'];
            $showWhenLoggedIn = $attributes['show_when_logged_in'] == 'true';
            $border = $attributes['border'] == 'true';
            $redirect = $attributes['redirect'];
            $login_page = $attributes['login_page'] == 'true';

            if (!$showWhenLoggedIn && is_user_logged_in()) {
                return '';
            }

            // SCRIPTS
            wp_enqueue_script('instalogin-api', 'https://cdn.instalog.in/js/instalogin-0.7.2.js');

            $container_id = 'instalogin';
            if (!$login_page) {
                // so we can have multiple codes on a single page
                $container_id = wp_generate_password(5, false);
            }

            $api_key = get_option('instalogin-api-key');
            $display_type = get_option('instalogin-api-type', 'qr');

            wp_enqueue_script('instalogin-login', plugin_dir_url(__FILE__) . '../../scripts/login.js?v=3', ['instalogin-api'], null, true);
            wp_add_inline_script('instalogin-login', "init_insta('$container_id', '$api_key', '$display_type', '$redirect');", 'after');

            // TOOD: if key unset, display error message

            // RENDER
            if (!$login_page) {
                echo $this->html($size, $container_id);
            } else {
                // We can't insert HTML into the login page regularly, so we must use js.

?>
                <script>
                    /** @type {HTMLButtonElement} */
                    let submit = document.querySelector("p.submit");
                    console.log(submit);

                    if (!submit) {
                        console.warn(
                            "insta: could not find Button `Log In`. Trying to find shortcode container."
                        );
                    } else {
                        const div = document.createElement("div");
                        div.classList.add('insta-login-page-container');
                        div.innerHTML += `
                            <?php echo $this->html($size, 'instalogin') ?>
                        `;

                        submit.after(div);
                    }
                </script>

        <?php
            }
        });
    }

    public function html($size, $container_id)
    {
        ob_start(); ?>
        <style>
            .insta-login-page-container {
                display: flex;
                justify-content: center;
                width: 100%;
                margin-top: 5rem;
            } 
            .instalogin-login .instalogin-container {
                /* border: <?php // echo esc_html($border) ? " 1px solid rgb(200, 200, 200);" : " none !important;" 
                            ?>; */
                width: <?php echo esc_html($size) ?>;
            }

            .instalogin-login .instalogin-image-container {
                /* width: <?php echo esc_html($size) ?>; */
                background-color: transparent !important;
            }

            .insta-card {
                background: #cbcbcb;
                border-radius: 18px;
                overflow: hidden;
                max-width: min-content;
                width: 100%;
            }

            .insta-card .insta-content {
                display: flex;
                flex-flow: column nowrap;
                align-items: center;
                gap: .8rem;
                margin-top: 1rem;
                margin-bottom: .5rem;
            }

            .insta-card .insta-content a {
                background: #3C3C3B;
                color: white;
                border-radius: 15px;
                border: 1px solid white;
                text-transform: none;
                text-decoration: none;

                font-size: 14px;

                padding: .2rem 1rem;
            }

            .insta-card .insta-logo {
                max-height: 15px;
            }
        </style>

        <div class="insta-card">
            <div class="instalogin-login" id="<?php echo esc_attr($container_id) ?>"></div>
            <div class="insta-content">
                <a href=" https://instalogin.me" target="_blank" rel="noopener"><?php _e("Learn more...", "instalogin-me") ?></a>
                <img class="insta-logo" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../../img/Logo-Instlogin-v2-gray.svg" alt="instalogin logo">
            </div>
        </div>
<?php

        return ob_get_clean();
    }
}
