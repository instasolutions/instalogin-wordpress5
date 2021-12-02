<?php
class InstaloginWizardPage
{

    public $page_suffix = '';

    public function __construct()
    {
        add_action('admin_menu', function () {
            $this->page_suffix = add_submenu_page(
                null,
                __('Instalogin Wizard', 'instalogin-me'),
                __('Instalogin Wizard', 'instalogin-me'),
                'manage_options',
                'instalogin-wizard',
                [$this, 'render']
            );
        });

        add_action('admin_enqueue_scripts', function ($hook) {
            if ($hook == $this->page_suffix) {
                wp_deregister_style('wp-admin');

                wp_enqueue_style('insta-wizard-style', plugin_dir_url(__FILE__) . 'style.css', [], '0');
                wp_enqueue_script('insta-wizard-settings', plugin_dir_url(__FILE__) . 'settings.js', [], '0', true);
                wp_enqueue_script('insta-wizard', plugin_dir_url(__FILE__) . 'wizard.js', ['insta-wizard-settings'], '0', true);

                $user_id = get_current_user_id();
                $nonce = wp_create_nonce('wp_rest');
                $license_active = __('LICENSE ACTIVE!', 'instalogin-me');
                $activation_failed = __('Activation Failed! Try again.', 'instalogin-me');

                wp_add_inline_script('insta-wizard', "const user_id = '$user_id'; const nonce = '$nonce'; const licence_active_text = '$license_active'; const licence_bad_text = '$activation_failed'; ", 'before');
            }
        });
    }

    public function render()
    {
?>
        <div class="wizard">
            <header>
                <nav>
                    <div class="step-label done">1. <?php _e("Welcome", 'instalogin-me') ?></div>
                    <div class="line"></div>
                    <div class="step-label active">2. <?php _e("Setup", 'instalogin-me') ?></div>
                    <div class="line"></div>
                    <div class="step-label">3. <?php _e("License", 'instalogin-me') ?></div>
                    <div class="line"></div>
                    <div class="step-label">4. <?php _e("Finalize", 'instalogin-me') ?></div>
                    <div class="line"></div>
                    <div class="step-label">5. <?php _e("Connect Devices", 'instalogin-me') ?></div>
                </nav>
                <div>
                    <img src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>images/Instalogin-horiz_Blue-Grey.svg" alt="">
                </div>
            </header>

            <main>
                <?php include plugin_dir_path(__FILE__) . "steps/step1.php"; ?>
                <?php include plugin_dir_path(__FILE__) . "steps/step2.php"; ?>
                <?php include plugin_dir_path(__FILE__) . "steps/step3.php"; ?>
                <?php include plugin_dir_path(__FILE__) . "steps/step4.php"; ?>
                <?php include plugin_dir_path(__FILE__) . "steps/step5.php"; ?>
                <?php include plugin_dir_path(__FILE__) . "steps/step6.php"; ?>
            </main>

            <footer>
                <div>
                    <button id="back"><?php _e("Back", 'instalogin-me') ?></button>
                    <a href="<?php echo esc_attr(admin_url('?page=instalogin')) ?>">
                        <button><?php _e("Return to Wordpress", 'instalogin-me') ?></button>
                    </a>
                </div>

                <div>
                    <button id="next" class="primary"><?php _e("Next", 'instalogin-me') ?></button>
                    <a href="<?php echo esc_attr(admin_url('?page=instalogin')) ?>">
                        <button id="finish" class="primary hidden"><?php _e("Finish", 'instalogin-me') ?></button>
                    </a>
                </div>
            </footer>
        </div>
<?php
    }
}


?>