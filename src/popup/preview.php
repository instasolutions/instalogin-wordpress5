<?php

class InstaloginPopupPreviewPage
{
    public function __construct()
    {
        add_action('admin_menu', function () {
            add_submenu_page(
                null,
                __('PopUp Preview', 'instalogin-me'),
                __('PopUp Preview', 'instalogin-me'),
                'manage_options',
                'popup-preview',
                function () {

                    $setting_name = 'instalogin-popup-style';
                    require(dirname(__FILE__) . '/default_settings.php');
                    $setting = get_option($setting_name, $default_popup_settings);

?>

                <style>
                    .notice {
                        display: none !important;
                    }

                    #wpadminbar,
                    #adminmenumain,
                    #wpfooter {
                        display: none;
                    }

                    #wpbody-content>div:not(.insta-preview) {
                        display: none !important;
                    }

                    .insta-popup-container {
                        <?php
                        if ($setting['horizontal'] == "left") {
                            echo "float: left;";
                        } else {
                            echo "float: right;";
                        }
                        ?>
                        margin-right: 1rem;
                    }
                </style>

                <div class="insta-preview">
                    <div style="">
                        <?php echo do_shortcode('[insta-popup preview="true"]') ?>
                    </div>
                </div>
<?php
                }
            );
        });
    }
}
