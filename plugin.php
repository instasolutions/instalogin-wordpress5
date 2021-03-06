<?php

/**
 * Plugin Name: Official Instalogin
 * Plugin URI: https://instalogin.me/
 * Description: Official Instalogin integration enables login via InstaCode on your Wordpress installation.
 * Author: InstaSolutions GmbH
 * Author URI: https://instalogin.me
 * Requires at least: 5.0
 * Version: 0.12.3
 * Licence: GPL v2 or later
 * Licence URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: instalogin-me
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

// TODO: This is a fix for media selector not working. Check if really required
function load_wp_media_files()
{
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'load_wp_media_files');

class InstalogIn
{
    private $client = false;

    public function __construct()
    {
        $this->init_client();

        // Shortcodes
        require_once('src/login/shortcode.php');
        new InstaloginLoginShortcode();

        require_once('src/register/shortcode.php');
        new InstaloginRegisterShortcode();

        require_once('src/devices/shortcode.php');
        new InstaloginDevicesShortcode();

        require_once('src/popup/shortcode.php');
        new InstaloginPopupShortcode();

        // Backend
        require_once('src/devices/rest.php');
        new InstalogInDevicesAPI($this->client);

        require_once('src/settings/rest.php');
        new InstaloginSettingsAPI();

        require_once('src/login/rest.php');
        new InstaloginLoginAPI($this->client);

        require_once('src/register/rest.php');
        new InstaloginRegisterAPI($this->client);

        // Pages

        require_once('src/settings/settings.php');
        new InstaloginSettings();

        require_once('src/register/register_mail.php');
        new InstaloginRegisterMail($this->client);

        require_once('src/login/login_page.php');
        new InstaloginLoginPage();

        require_once('src/profile/profile_page.php');
        new InstaloginProfilePage();

        // menu customizer
        require_once('src/popup/menu.php');
        new InstaloginPopupMenuItem();

        require_once('src/popup/preview.php');
        new InstaloginPopupPreviewPage();

        // wizard
        require_once('wizard/index.php');
        new InstaloginWizardPage();

        // Run wizard on first activation
        add_action('activated_plugin', function ($plugin) {
            if (strpos($plugin, 'instalogin') !== false) {
                if (!get_option('instalogin-api-key', false)) {
                    update_option('instalogin-redirect-wizard', true);
                }
            }
        }, 10, 1);

        add_action('admin_init', function () {
            if (get_option('instalogin-redirect-wizard', false)) {
                delete_option('instalogin-redirect-wizard');
                wp_redirect(admin_url('?page=instalogin-wizard'));
            }
        });

        // global scripts

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('insta-global', plugin_dir_url(__FILE__) . "style/global.css", [], '1');

            wp_enqueue_script('insta_global_scripts', plugin_dir_url(__FILE__) . "scripts/global.js", [], '0.0.1', false);
            wp_add_inline_script('insta_global_scripts', "const insta_api = '" . rest_url('instalogin/v1/') . "'", 'before');
        });

        add_action('admin_enqueue_scripts', function ($hook) {
            wp_enqueue_style('insta-global', plugin_dir_url(__FILE__) . "style/global.css", [], '1');
            wp_enqueue_script('insta-media-selectors', plugin_dir_url(__FILE__) . "scripts/media.js", [], '1', true);

            wp_enqueue_script('insta_global_scripts_admin', plugin_dir_url(__FILE__) . "scripts/global.js", [], '0.0.1', false);
            wp_add_inline_script('insta_global_scripts_admin', "const insta_api = '" . rest_url('instalogin/v1/') . "';", 'before');
        });

        add_action('init', function () {
            load_plugin_textdomain('instalogin', false, dirname(plugin_basename(__FILE__)) . "/languages");
        });
    }

    // Initialize Instalog.in SDK client
    private function init_client()
    {
        $api_key = get_option('instalogin-api-key');
        $api_secret = get_option('instalogin-api-secret');
        if ($api_key == false || $api_secret == false) {
            add_action('admin_notices', function () {
?>
                <div class="notice notice-info">
                    <h3>Instalogin Setup</h3>
                    <p>
                        <?php _e('You are almost ready to use Instalogin!', 'instalogin-me') ?><br>
                        <?php _e('Please finish the setup: ', 'instalogin-me') ?><br>
                    </p>
                    <p>
                        <a class="button" href="<?php echo esc_attr(admin_url('?page=instalogin-wizard')) ?>">Run Installation Wizard</a>
                    </p>
                </div>
<?php
            });
            return;
        }

        // TODO: does not throw on error
        try {
            $this->client = new \Instalogin\Client($api_key, $api_secret);
        } catch (\Throwable $th) {
            add_action('admin_notices', function () {
                echo "<div class='error'><b>Instalogin</b> " . __('API key or secret invalid.', 'instalogin-me') . "</div>";
                echo "<br>Go to <a href='" . esc_attr(admin_url('?page=instalogin')) . "'>settings</a>.</div>";
            });
        }
    }
}

$instalog_in = new InstalogIn();
