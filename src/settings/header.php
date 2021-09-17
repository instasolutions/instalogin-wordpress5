<?php
function settings_header()
{
    ob_start();
?>

    <style>
        .insta-container {
            background: white;
            padding: 1rem 1.5rem;
            padding-right: 3rem;
            margin-bottom: 0.8rem;
        }

        .insta-container>p {
            color: #1d4264;
            font-size: 16px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .insta-container .insta-header {
            display: flex;
            justify-content: space-between;
        }

        .insta-logo {
            height: 50px;
        }

        .insta-button {
            font-size: 14px !important;
            color: white !important;
            background: var(--insta-blue-light) !important;
            border: none;
            border-radius: 100px;
            float: right;
            margin-top: 1rem;
            font-weight: bold;

            padding: 8px 24px;

            align-self: end;
            text-decoration: none;
            transition: transform 0.15s ease-out;
            box-shadow: none;
        }

        .insta-button:hover {
            transform: scale(1.05);
            box-shadow: none;
        }

        img.logo {
            width: 350px;
            margin-left: -20px;
        }
    </style>

    <div class="insta-container">
        <div class="insta-header">
            <img class="logo" src="<?= plugin_dir_url(__FILE__) . "../../img/logo.svg" ?> " alt="Instalogin Logo">
            <div>
                <a href="<?= __("https://instalogin.me/de/kontakt/", 'instalogin') ?>" rel="noopener" target="_blank" class="insta-button"><?= __('Contact', 'instalogin') ?></a>
            </div>
        </div>
        <p><?= __('Instalogin enables secure authentication by scanning of the InstaCode instead of using a combination of password and username.<br>Forgetting your password is a relic of the past - there are none!', 'instalogin') ?></p>
    </div>

<?php
    return ob_get_clean();
}
