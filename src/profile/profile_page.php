<?php

class InstaloginProfilePage
{
    // Allow users to enable instalog.in authentication in their profile page.
    public function __construct()
    {
        add_action('personal_options', function () {
            // current user being edited
            global $user_id;
            $user = get_user_by('id', $user_id);
            $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $query_params = parse_url($url, PHP_URL_QUERY);


            wp_enqueue_script('instalogin-devices', plugin_dir_url(__FILE__) . "../../scripts/devices.js", ['wp-i18n'], '1', true);
            wp_localize_script('instalogin-devices', 'wpv', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'show_activation' => $user_id != get_current_user_id(),
            ]);

            wp_enqueue_script('instalogin-send-mail', plugin_dir_url(__FILE__) . "../../scripts/device-send-mail.js", ['wp-i18n'], '1', true);
            wp_localize_script('instalogin-send-mail', 'wpv_mail', [
                'insta_nonce' => wp_create_nonce('wp_rest'),
                'user_id' => $user_id,
            ]); ?>
            <div>
                <h3><a href="https://instalogin.me" target="_black" rel="noreferrer">Instalogin</a></h3>

                <div class="instalogin-info-area">
                    <?php
                    if (isset($_GET['reset_password']) && $_GET['reset_password'] == 'true') {
                        wp_set_password(wp_generate_password(64), get_current_user_id()); ?>
                        <script>
                            window.location = "/wp-login.php";
                        </script>
                    <?php
                    } ?>
                </div>

                <p><?= __('Ready to join the no-password revolution?', 'instalogin') ?></p>

                <button class="instalogin-activate button instalogin-send-mail"><?= __('Send activation mail', 'instalogin') ?></button>

                <?php if ($user_id == get_current_user_id()) { ?>

                    <style>
                        details.instalogin-devices-details {
                            cursor: pointer;
                            border-radius: 3px;
                            transition: 0.15s background linear;
                        }

                        details.instalogin-devices-details .instalogin-devices-admin {
                            cursor: auto;
                            background: #eee;
                            padding: 15px;
                            border-radius: 4px;
                        }

                        details.instalogin-devices-details .instalogin-devices-admin:before {
                            content: "";
                            height: 0;
                        }

                        details.instalogin-devices-details[open] .instalogin-devices-admin {
                            animation: animateDown 0.2s linear forwards;
                        }

                        @keyframes animateDown {
                            0% {
                                opacity: 0;
                                transform: translatey(-15px);
                            }

                            100% {
                                opacity: 1;
                                transform: translatey(0);
                            }
                        }
                    </style>

                    <details class="instalogin-devices-details">
                        <summary class="button" style="margin-bottom: .5rem; margin-top: .5rem;"><?= __('Manage Devices', 'instalogin') ?></summary>
                        <div class="instalogin-devices-admin">
                            <!-- <ul class="instalogin-device-list"></ul> -->
                        </div>
                    </details>

                    <div class="card">

                        <h2 class="title"><?= __('Randomize Password', 'instalogin') ?></h2>
                        <p><?= __('Instalogin enables effortless authentication by freeing you of the burden of having to remember a password.<br><br>
                            If you created your account with a password we suggest that you replace it with a strong, random and secret password.<br>
                            This will ensure that your password is unguessable and increase your account\'s security even further.<br><br>
                            Should you at any point decide that you do not wish to use Instalogin for authentication anymore you may set a new password by requesting a 
                            password reset email.', 'instalogin') ?></p>

                        <label>
                            <input type="checkbox" name="instalogin-accept-reset" id="instalogin-accept-reset">
                            <?= __("Replace my password with a secure random password.", 'instalogin') ?>
                        </label>
                        <br>

                        <button id="instalogin-reset-password" disabled style="margin-top: 1rem;" class="button"><?= __("Save", 'instalogin') ?></button>
                    </div>

                    <script>
                        {

                            history.pushState(null, null, '/wp-admin/profile.php');

                            const reset_button = document.querySelector('#instalogin-reset-password');
                            const checkbox = document.querySelector('#instalogin-accept-reset');

                            if (checkbox) {
                                checkbox.addEventListener('change', () => {
                                    if (checkbox.checked) reset_button.disabled = false;
                                    else reset_button.disabled = true;
                                })
                            }

                            if (reset_button && checkbox) {
                                reset_button.addEventListener('click', (event) => {
                                    event.preventDefault();

                                    if (checkbox.checked) {
                                        window.location = '/wp-admin/profile.php?reset_password=true'
                                    }
                                })
                            }
                        }
                    </script>

                <?php } else { ?>

                    <div class="notice notice-warning is-dismissible inline">
                        <p>
                            <?= __("You can not manage another user's devices.", 'instalogin') ?>
                        </p>
                    </div>

                <?php } ?>
            </div>
<?php
        });
    }
}
