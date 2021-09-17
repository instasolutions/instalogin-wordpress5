<?php

class InstaloginProfilePage
{
    // Allow users to enable instalogin authentication in their profile page.
    public function __construct()
    {
        add_action('personal_options', function () {
            // current user being edited
            global $user_id;
            $user = get_user_by('id', $user_id);
            $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $query_params = parse_url($url, PHP_URL_QUERY);

            require_once(dirname(__FILE__) . '/../settings/header.php');

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

                <?= settings_header() ?>

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

                <!-- <p><?= __('Ready to join the no-password revolution?', 'instalogin') ?></p> -->

                <!-- <button class="instalogin-activate button instalogin-send-mail"><?= __('Send activation mail', 'instalogin') ?></button> -->

                <?php if ($user_id == get_current_user_id()) { ?>

                    <style>
                        summary {
                            cursor: pointer;
                        }

                        details.instalogin-devices-details {
                            /* transition: 0.15s background linear; */
                        }

                        .instalogin-devices-details table {
                            margin-top: 1.5rem;
                        }

                        .instalogin-devices-details tfoot th {
                            padding: 0;
                            width: 100%;
                        }

                        .instalogin-devices-details table,
                        .instalogin-devices-details td,
                        .instalogin-devices-details th {
                            border: none !important;
                            border-collapse: collapse;
                        }

                        .instalogin-devices-details tbody tr {
                            background: #eee;
                            border-bottom: .5rem solid white;
                        }

                        details.instalogin-devices-details .instalogin-devices-admin {
                            cursor: auto;
                            /* border-radius: 4px; */
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


                    <style>
                        .insta-box {
                            background: white;
                            padding: 1rem 1.4rem 2.3rem;
                            margin-bottom: 1rem;
                        }

                        .insta-title {
                            color: var(--insta-red);
                            font-size: 20px;
                        }

                        #instalogin-reset-password {
                            background: var(--insta-red) !important;
                            color: white !important;

                            font-size: 16px !important;
                            border: none !important;
                            border-radius: 100px !important;
                            margin-top: 1.6rem;
                            font-weight: bold !important;

                            padding: 8px 24px !important;

                            text-decoration: none !important;
                            transition: transform 0.15s ease-out;
                            box-shadow: none;

                        }

                        #instalogin-reset-password:hover {
                            transform: scale(1.05);
                            box-shadow: none;
                        }

                        .insta-confirm {
                            color: var(--insta-red);
                        }

                        summary.insta {
                            color: var(--insta-red);
                            font-size: 20px;
                            font-weight: bold;
                        }

                        .insta-delete {
                            color: var(--insta-red);
                            font-weight: bold;
                        }

                        .insta-delete:hover {
                            color: red;
                        }

                        .insta-button {
                            cursor: pointer;
                            font-size: 14px !important;
                            color: white !important;
                            background: var(--insta-blue-light) !important;
                            border: none;
                            border-radius: 100px;
                            margin-top: 1rem;
                            font-weight: bold;

                            padding: 8px 24px;

                            float: none;

                            text-decoration: none;
                            transition: transform 0.15s ease-out;
                            box-shadow: none;
                        }

                        .insta-button+.insta-button {
                            margin-left: .5rem;

                        }

                        .insta-button:hover {
                            transform: scale(1.05);
                            box-shadow: none;
                        }
                    </style>

                    <details class="instalogin-devices-details insta-box">
                        <summary class="insta" style="margin-bottom: .5rem; margin-top: .5rem;"><?= __('Manage Instalogin Devices', 'instalogin') ?></summary>
                        <div class="instalogin-devices-admin">
                            <!-- <ul class="instalogin-device-list"></ul> -->
                        </div>
                    </details>

                    <div class="insta-box">
                        <details>
                            <summary class="insta"><?= __('Maximum Security: Randomize your Password', 'instalogin') ?></summary>
                            <p><?= __('Instalogin enables effortless authentication by freeing you of the burden of having to remember a password.<br>
                                If you created your account with a password we suggest that you replace it with a strong, random and secret password.<br>
                                This will ensure that your password is unguessable and increase your account\'s security even further.<br>
                                Should you at any point decide that you do not wish to use Instalogin for authentication anymore you may set a new password by requesting a 
                                password reset email.', 'instalogin') ?></p>

                            <label class="insta-confirm">
                                <input type="checkbox" name="instalogin-accept-reset" id="instalogin-accept-reset">
                                <?= __("Yes, replace my password with a secure random password.<br>I know that login will only be possible via the InstaApp unless I reset my password. ", 'instalogin') ?>
                            </label>
                            <br>

                            <button id="instalogin-reset-password" disabled class=""><?= __("Randomize my Password", 'instalogin') ?></button>
                        </details>
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
                            <br>
                            <button class="instalogin-activate button instalogin-send-mail"><?= __('Send activation mail', 'instalogin') ?></button>

                        </p>
                    </div>

                <?php } ?>
            </div>
<?php
        });
    }
}
