<?php

/**
 * Plugin Name: Official Instalogin Integration
 * Plugin URI: https://instalogin.me/
 * Author: Christian Schemoschek
 * Author URI: https://allbut.social
 * Requires at least: 5.0
 * Version: 0.5.0
 * Licence: TODO
 * Licence URI: TODO
 * Text Domain: instalogin
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

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

        require_once('src/login/rest.php');
        new InstaloginLoginAPI($this->client);

        require_once('src/register/rest.php');
        new InstaloginRegisterAPI($this->client);


        // wizard
        require_once('wizard/settings_rest.php');
        new InstalogWizardSettingsAPI();

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

        // global styles

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('insta-global', plugin_dir_url(__FILE__) . "style/global.css", [], '1');
        });

        add_action('admin_enqueue_scripts', function ($hook) {
            wp_enqueue_style('insta-global', plugin_dir_url(__FILE__) . "style/global.css", [], '1');
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
                        <?= __('You are almost ready to use Instalogin!', 'instalogin') ?><br>
                        <?= __('Please finish the setup: ', 'instalogin') ?><br>
                    </p>
                    <p>
                        <a class="button" href="<?= plugin_dir_url(__FILE__) ?>/wizard">Run Installation Wizard</a>
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
                echo "<div class='error'><b>Instalog.in</b> " . __('API key or secret invalid.', 'instalogin') . "</div>";
                echo "<br>Go to <a href='/wp-admin/admin.php?page=instalogin'>settings</a>.</div>";
            });
        }
    }
}

$instalog_in = new InstalogIn();
