<?php

class InstaloginPopupPreviewPage
{
    public function __construct()
    {
        add_action('admin_menu', function () {
            add_submenu_page(
                null,
                __('PopUp Preview', 'instalogin'),
                __('PopUp Preview', 'instalogin'),
                'manage_options',
                'popup-preview',
                function () {
?>

                <style>
                    .notice {
                        display: none !important;
                    }

                    #wpadminbar,
                    #adminmenumain {
                        display: none;
                    }
                </style>

                <div class="wrap">
                    <div style="width: 100%; min-height: 400px; display: flex; justify-content: center; align-items: center;">
                        <?= do_shortcode('[insta-popup preview="true"]') ?>
                    </div>
                </div>
<?php
                }
            );
        });
    }
}
