<?php
include $_SERVER['DOCUMENT_ROOT'] . "/wp-blog-header.php";

// TODO: translation

if (!current_user_can('manage_options')) {
    echo __("You must be an administrator to use the wizard.", "instalogin");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= __("Instalogin - Installation", "instalogin") ?></title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!-- Container for stepper and buttons. This is just for design in Codepen -->
    <div class="container">
        <div>
            <center>
                <img src="images/logo.svg" alt="Instalogin">
            </center>
            <!--  Stepper container. This is the main element that is passed to the stepper class.  -->
            <div class="stepper">

                <!--  Steps must be formatted in this way. The icon will update with it's index.  -->
                <!--  On complete the icon will switch to a material design checkmark  -->
                <div class="steps">
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label"><?= __('Welcome', 'instalogin') ?></span>
                    </a>
                    <div class="step-divider"></div>
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label"><?= __('Setup', 'instalogin') ?></span>
                    </a>
                    <div class="step-divider"></div>
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label"><?= __('Activate', 'instalogin') ?></span>
                    </a>
                    <div class="step-divider"></div>
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label"><?= __('Finish', 'instalogin') ?></span>
                    </a>
                </div>

                <div class="step-container">

                    <!-- Welcome -->
                    <div class="step-content">
                        <h1><?= __('Welcome to Instalogin', 'instalogin') ?></h1>
                        <h2><?= __('Two factor authentication in one step!', 'instalogin') ?></h2>
                        <p><?= __('Instalogin works for your team in the backend as well as your users in the frontend.', 'instalogin') ?></p>

                        <p> <a href="<?= __("https://instalogin.me/en/demo-en/", 'instalogin') ?>" target="_blank" rel="noopener"><button class="btn"><?= __('Register', 'instalogin') ?></button></a></p>

                        <!-- <p>Du hast bereits einen Account? Dann leg direkt los!</p> -->
                    </div>

                    <!-- Settings -->
                    <div class="step-content">
                        <h1><?= __('Configuration', 'instalogin') ?></h1>

                        <div class="form">
                            <p><?= __('You may find your credentials over <a href="https://instalogin.me/en/demo-en/">here</a>!', 'instalogin') ?></p>

                            <label class="">
                                <div>
                                    <?= __('API Key', 'instalogin') ?>
                                </div>
                                <input type="text" id="api_key" required>
                                <div class="info info-key"><?= __('Required. Must be exactly 32 characters of length.', 'instalogin') ?></div>
                            </label>

                            <label>
                                <div>
                                    <?= __('API Secret', 'instalogin') ?>
                                </div>
                                <input type="text" id="api_secret">
                                <div class="info info-secret"><?= __('Required. Must be exactly 64 characters of length.', 'instalogin') ?></div>
                            </label>

                            <p>
                                <?= __("If you do not have the necessary credentials, you may request them ", 'instalogin') ?>
                                <a href="<?= __("https://instalogin.me/de/keysecret/", 'instalogin') ?>" target="_blank" rel="noopener">here!</a>
                            </p>


                            <label style="margin-top: 1rem;">
                                <input type="checkbox" id="enable_registration">
                                <span>
                                    <?= __('Enable registration via Instalogin', 'instalogin') ?>
                                </span>
                            </label>

                            <label style="margin-top: 1rem;">
                                <div><?= __('Code Type', 'instalogin') ?></div>
                                <select id="code_type">
                                    <option value="qr"><?= __('InstaCode', 'instalogin') ?></option>
                                    <option value="si"><?= __('Smart Image', 'instalogin') ?></option>
                                </select>
                            </label>

                        </div>
                        <p>
                            <button class="btn btn-save"><?= __('Save Settings', 'instalogin') ?></button>
                            <!-- TODO: check insta api for valid key/secret -->
                        </p>
                    </div>

                    <!-- Install App -->
                    <div class="step-content">
                        <h1><?= __("Let's begin!", 'instalogin') ?></h1>
                        <p>Erhalte eine Aktivierungsmail und scanne anschließend in der App den Verifizierungscode:</p>
                        <p><?= __('Request a confirmation email and use the Insta app to scan the verification code.', 'instalogin') ?></p>
                        <p>
                            <button class="btn btn-mail"><?= __('Request activation mail', 'instalogin') ?></button>
                        </p>
                        <p><?= __("Don't have the app already? Get it here:", 'instalogin') ?></p>
                        <div>
                            <a href="https://apps.apple.com/app/instalog-in/id1097751906" rel="noopener" target="_blank">
                                <img src="images/appstore.png" width="200px" alt="App Store" style="margin:5px">
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.instaholding.instalog" rel="noopener" target="_blank">
                                <img src="images/google-play.png" width="200px" alt="Google Play" style="margin:5px">
                            </a>
                        </div>
                    </div>

                    <!-- Done -->
                    <div class="step-content">
                        <h1><?= __("That's it!", 'instalogin') ?></h1>
                        <h2><?= __("The installation has been completed successfully.", 'instalogin') ?></h2>
                        <br>
                        <?= __("Go back or try loggin in right now:", 'instalogin') ?>
                        <p>
                            <a href="/wp-admin"><button class="btn"><?= __("Exit installer", 'instalogin') ?></button></a>
                            <a href="<?= wp_logout_url('/wp-login.php') ?>"><button class="btn"><?= __("Test Instalogin", 'instalogin') ?></button></a>
                        </p>

                    </div>


                </div>
            </div>
        </div>

        <!-- Buttons container must be included.  -->
        <!-- Also, each `step-label` is needed  -->
        <div class="buttons">
            <button class="btn step-back" disabled><?= __("Back", 'instalogin') ?></button>
            <button class="btn step-next"><?= __("Next", 'instalogin') ?></button>
            <a href="/wp-admin/admin.php?page=instalogin"><button class="btn step-complete"><?= __("Abort", 'instalogin') ?></button></a>
            <button class="btn step-finish"><?= __("Finish", 'instalogin') ?></button>
        </div>
    </div>

    <script>
        const nonce = "<?= wp_create_nonce('wp_rest') ?>";
        const user_id = "<?= get_current_user_id() ?>";
    </script>
    <script src="./wizard.js"></script>

</body>

</html>