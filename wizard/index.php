<?php
include $_SERVER['DOCUMENT_ROOT'] . "/wp-blog-header.php";

if (!current_user_can('manage_options')) {
    echo __("You must be an administrator to use the wizard.", "instalogin");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Document</title>
</head>

<body>
    <div class="wizard">
        <header>
            <nav>
                <div class="step-label done">1. <?= __("Welcome", 'instalogin') ?></div>
                <div class="line"></div>
                <div class="step-label active">2. <?= __("Setup", 'instalogin') ?></div>
                <div class="line"></div>
                <div class="step-label">3. <?= __("License", 'instalogin') ?></div>
                <div class="line"></div>
                <div class="step-label">4. <?= __("Finalize", 'instalogin') ?></div>
                <div class="line"></div>
                <div class="step-label">5. <?= __("Connect Devices", 'instalogin') ?></div>
            </nav>
            <div>
                <img src="./images/Instalogin-horiz_Blue-Grey.svg" alt="">
            </div>
        </header>

        <main>
            <?php include "./steps/step1.php"; ?>
            <?php include "./steps/step2.php"; ?>
            <?php include "./steps/step3.php"; ?>
            <?php include "./steps/step4.php"; ?>
            <?php include "./steps/step5.php"; ?>
            <?php include "./steps/step6.php"; ?>
        </main>

        <footer>
            <div>
                <button id="back"><?= __("Back", 'instalogin') ?></button>
                <a href="/wp-admin/admin.php?page=instalogin">
                    <button><?= __("Return to Wordpress", 'instalogin') ?></button>
                </a>
            </div>

            <div>
                <button id="next" class="primary"><?= __("Next", 'instalogin') ?></button>
                <a href="/wp-admin/admin.php?page=instalogin">
                    <button id="finish" class="primary hidden"><?= __("Finish", 'instalogin') ?></button>
                </a>
            </div>
        </footer>
    </div>

    <script>
        const user_id = "<?= get_current_user_id() ?>";
        const nonce = "<?= wp_create_nonce('wp_rest') ?>";

        const licence_active_text = "<?= __("LICENSE ACTIVE!", 'instalogin') ?>";
        const licence_bad_text = "<?= __("Activation Failed! Try again.", 'instalogin') ?>";
    </script>

    <script src="./settings.js"></script>
    <script src="./wizard.js"></script>
</body>

</html>