<?php
function settings_header()
{
    ob_start();
?>

    <style>
        .insta-container {
            background: white;
            padding: 1rem 2.5rem;
            padding-right: 3rem;
            margin-bottom: 2rem;
            box-shadow: 2px 3px 6px #00000029;
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

        .insta-button-hollow {
            display: inline-block;
            font-size: 14px !important;
            color: var(--insta-blue-light) !important;
            background: white !important;
            border: solid 2px var(--insta-blue-light);
            border-radius: 100px;
            margin-top: .2rem !important;
            font-weight: bolder !important;

            padding: 8px 24px;

            text-decoration: none;
            transition: transform 0.15s ease-out;
            box-shadow: none;
        }

        .insta-button-hollow+.insta-button-hollow {
            margin-left: 1.5rem;
        }

        .insta-button-yellow {
            border: none !important;
            background-color: var(--insta-yellow) !important;
            color: var(--insta-blue-dark);
            float: right;
            box-shadow: 0px 2px 6px #00000029;
            border: 1px solid #FFFFFF40;
        }

        .insta-button-green {
            color: var(--insta-green) !important;
            border-color: var(--insta-green) !important;
        }

        .insta-button:hover {
            box-shadow: none;
        }

        img.logo {
            width: 250px;
            margin-left: -20px;
        }

        .insta-description {
            margin-top: 0;
        }
    </style>

    <div class="insta-container">
        <div class="insta-header">
            <img class="logo" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) . "../../img/logo.svg" ?> " alt="Instalogin Logo">
        </div>
        <p class="insta-description"><?php _e('Instalogin enables secure authentication by scanning of the InstaCode instead of using a combination of password and username.<br>Forgetting your password is a relic of the past - there are none!', 'instalogin-me') ?></p>
        <a href="<?php _e("https://instalogin.me/", 'instalogin') ?>" rel="noopener" target="_blank" class="insta-button-hollow"><?php _e('Find out more!', 'instalogin-me') ?></a>
        <a href="<?php _e("https://instalogin.me/wordpress-help", 'instalogin') ?>" rel="noopener" target="_blank" class="insta-button-hollow insta-button-green"><?php _e('Help "What do I do" ?', 'instalogin-me') ?></a>

        <a href="<?php _e("https://instalogin.me/contact/", 'instalogin') ?>" rel="noopener" target="_blank" class="insta-button-hollow insta-button-yellow"><?php _e('Help us be better', 'instalogin-me') ?></a>
    </div>

<?php
    return ob_get_clean();
}
