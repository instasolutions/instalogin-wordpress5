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

                    #wpbody-content>div:not(.preview) {
                        display: none !important;
                    }
                </style>

                <div class="wrap preview">
                    <div style="width: 100%; min-height: 400px; display: flex; justify-content: center; align-items: center;">
                        <?php echo do_shortcode('[insta-popup preview="true"]') ?>
                    </div>
                </div>
<?php
                }
            );
        });
    }
}
