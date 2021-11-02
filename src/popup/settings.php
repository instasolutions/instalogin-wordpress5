<?php

class InstaloginPopupSettings
{
    public function __construct($page)
    {
        $section = 'instalogin-popup';

        // Add to wp
        add_settings_section($section, __('Smartcode', 'instalogin-me'), function () {
            // Settings Section Title
        }, $page);



        // Use QR Code or Smart Image for login
        $setting_name = 'instalogin-popup-style';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", '', function () {
            $setting_name = 'instalogin-popup-style';

            require_once(dirname(__FILE__) . '/default_settings.php');
            $setting = get_option($setting_name, $default_popup_settings); ?>

            <style>
                .insta-popup-settings {
                    grid-column: span 2;
                }

                .insta-popup-settings label {
                    display: grid;
                    grid-template-columns: 300px 200px;
                    margin-bottom: .5rem;
                }

                .insta-popup-settings h4::after,
                .insta-popup-usage h4::after {
                    display: block;
                    content: "";
                    height: 2px;
                    margin-top: .5rem;
                    width: 200px;
                    background: var(--insta-blue-light);
                }

                .insta-popup-usage b {
                    white-space: nowrap;
                }
            </style>

            <div class="insta-popup-settings">
                <div>
                    <h4><?php _e("Container Design", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Background", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[box-bg]" value="<?php echo esc_attr($setting['box-bg']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Font Family", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. Verdana" name="<?php echo esc_attr($setting_name) ?>[font]" value="<?php echo esc_attr($setting['font']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Popup Trigger", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[trigger]">
                            <option <?php selected($setting['trigger'], 'click') ?> value="click">Click Button</option>
                            <option <?php selected($setting['trigger'], 'hover') ?> value="hover">Hover Button</option>
                        </select>
                    </label>
                    <label>
                        <span><?php _e("Shadow", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[box-shadow]">
                            <option <?php selected($setting['box-shadow'], 'on') ?> value="on">On</option>
                            <option <?php selected($setting['box-shadow'], 'off') ?> value="off">Off</option>
                        </select>
                    </label>

                    <label>
                        <span><?php _e("Position", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[position]">
                            <option <?php selected($setting['position'], 'top') ?> value="top">Below</option>
                            <option <?php selected($setting['position'], 'bottom') ?> value="bottom">Above</option>
                        </select>
                    </label>

                    <label>
                        <span><?php _e("Vertical Offset", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 0px" name="<?php echo esc_attr($setting_name) ?>[vertical]" value="<?php echo esc_attr($setting['vertical']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Horizontal Offset", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 0px" name="<?php echo esc_attr($setting_name) ?>[horizontal]" value="<?php echo esc_attr($setting['horizontal']) ?>">
                    </label>
                </div>

                <div>
                    <h4><?php _e("Login(-out) Button", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Button Type", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[login-type]">
                            <option <?php selected($setting['login-type'], 'text') ?> value="text">Text</option>
                            <option <?php selected($setting['login-type'], 'icon') ?> value="icon">Icon</option>
                        </select>
                    </label>

                    <label>
                        <span><?php _e("Padding", 'instalogin') ?></span>
                        <input type="text" palceholder="top right bottom left" name="<?php echo esc_attr($setting_name) ?>[login-padding]" value="<?php echo esc_attr($setting['login-padding']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Login Text", 'instalogin') ?></span>
                        <input type="text" palceholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[login-text]" value="<?php echo esc_attr($setting['login-text']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Logout Text", 'instalogin') ?></span>
                        <input type="text" palceholder="Sign Out" name="<?php echo esc_attr($setting_name) ?>[login-out-text]" value="<?php echo esc_attr($setting['login-out-text']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Font Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[login-font-size]" value="<?php echo esc_attr($setting['login-font-size']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Background", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[login-bg]" value="<?php echo esc_attr($setting['login-bg']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Color", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[login-color]" value="<?php echo esc_attr($setting['login-color']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Border Radius", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 30px" name="<?php echo esc_attr($setting_name) ?>[login-radius]" value="<?php echo esc_attr($setting['login-radius']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Weight", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[login-weight]">
                            <option <?php selected($setting['login-weight'], '100') ?> value="100">100</option>
                            <option <?php selected($setting['login-weight'], '200') ?> value="200">200</option>
                            <option <?php selected($setting['login-weight'], '300') ?> value="300">300</option>
                            <option <?php selected($setting['login-weight'], '400') ?> value="400">400</option>
                            <option <?php selected($setting['login-weight'], '500') ?> value="500">500</option>
                            <option <?php selected($setting['login-weight'], '600') ?> value="600">600</option>
                            <option <?php selected($setting['login-weight'], '700') ?> value="700">700</option>
                            <option <?php selected($setting['login-weight'], '800') ?> value="800">800</option>
                            <option <?php selected($setting['login-weight'], '900') ?> value="900">900</option>
                        </select>
                    </label>

                    <label>
                        <span><?php _e("Size (Icon Only)", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 4px" name="<?php echo esc_attr($setting_name) ?>[login-size]" value="<?php echo esc_attr($setting['login-size']) ?>">
                    </label>

                    <label>
                        <span><?php _e("Login Icon", 'instalogin') ?></span>
                        <div class="media-selector" style="display: flex; align-items: center; gap: .7rem;">
                            <button class="button"><?php _e("Select Icon", 'instalogin') ?></button>
                            <input type="hidden" name="<?php echo esc_attr($setting_name) ?>[login-icon]" value="<?php echo esc_attr($setting['login-icon']) ?>">
                            <img width="32px" height="32px" src="<?php echo esc_attr($setting['login-icon']) ?>" alt="">
                        </div>
                    </label>

                    <label>
                        <span><?php _e("Logout Icon", 'instalogin') ?></span>
                        <div class="media-selector" style="display: flex; align-items: center; gap: .7rem;">
                            <button class="button"><?php _e("Select Icon", 'instalogin') ?></button>
                            <input type="hidden" name="<?php echo esc_attr($setting_name) ?>[login-icon-out]" value="<?php echo esc_attr($setting['login-icon-out']) ?>">
                            <img width="32px" height="32px" src="<?php echo esc_attr($setting['login-icon-out']) ?>" alt="">
                        </div>
                    </label>
                </div>

                <div>
                    <h4><?php _e("Border", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Style", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[box-border-style]">
                            <option <?php selected($setting['box-border-style'], 'none') ?> value="none">None</option>
                            <option <?php selected($setting['box-border-style'], 'solid') ?> value="solid">Solid</option>
                        </select>
                    </label>
                    <label>
                        <span><?php _e("Color", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[box-border-color]" value="<?php echo esc_attr($setting['box-border-color']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Width", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 4px" name="<?php echo esc_attr($setting_name) ?>[box-border-width]" value="<?php echo esc_attr($setting['box-border-width']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Radius", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 30px" name="<?php echo esc_attr($setting_name) ?>[box-border-radius]" value="<?php echo esc_attr($setting['box-border-radius']) ?>">
                    </label>
                </div>

                <div>
                    <h4><?php _e("QR Code", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Padding", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 16px" name="<?php echo esc_attr($setting_name) ?>[qr-padding]" value="<?php echo esc_attr($setting['qr-padding']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Background", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[qr-bg]" value="<?php echo esc_attr($setting['qr-bg']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Shadow", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[qr-shadow]">
                            <option <?php selected($setting['qr-shadow'], 'on') ?> value="on">On</option>
                            <option <?php selected($setting['qr-shadow'], 'off') ?> value="off">Off</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4><?php _e("Title", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Text", 'instalogin') ?></span>
                        <input type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[title-text]" value="<?php echo esc_attr($setting['title-text']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[title-size]" value="<?php echo esc_attr($setting['title-size']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Color", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[title-color]" value="<?php echo esc_attr($setting['title-color']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Weight", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[title-weight]">
                            <option <?php selected($setting['title-weight'], '100') ?> value="100">100</option>
                            <option <?php selected($setting['title-weight'], '200') ?> value="200">200</option>
                            <option <?php selected($setting['title-weight'], '300') ?> value="300">300</option>
                            <option <?php selected($setting['title-weight'], '400') ?> value="400">400</option>
                            <option <?php selected($setting['title-weight'], '500') ?> value="500">500</option>
                            <option <?php selected($setting['title-weight'], '600') ?> value="600">600</option>
                            <option <?php selected($setting['title-weight'], '700') ?> value="700">700</option>
                            <option <?php selected($setting['title-weight'], '800') ?> value="800">800</option>
                            <option <?php selected($setting['title-weight'], '900') ?> value="900">900</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4><?php _e("Sub Title", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Text", 'instalogin') ?></span>
                        <textarea type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[sub-title-text]">
<?php echo esc_attr($setting['sub-title-text']) ?>
                        </textarea>
                    </label>
                    <label>
                        <span><?php _e("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[sub-title-size]" value="<?php echo esc_attr($setting['sub-title-size']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Color", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[sub-title-color]" value="<?php echo esc_attr($setting['sub-title-color']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Weight", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[sub-title-weight]">
                            <option <?php selected($setting['sub-title-weight'], '100') ?> value="100">100</option>
                            <option <?php selected($setting['sub-title-weight'], '200') ?> value="200">200</option>
                            <option <?php selected($setting['sub-title-weight'], '300') ?> value="300">300</option>
                            <option <?php selected($setting['sub-title-weight'], '400') ?> value="400">400</option>
                            <option <?php selected($setting['sub-title-weight'], '500') ?> value="500">500</option>
                            <option <?php selected($setting['sub-title-weight'], '600') ?> value="600">600</option>
                            <option <?php selected($setting['sub-title-weight'], '700') ?> value="700">700</option>
                            <option <?php selected($setting['sub-title-weight'], '800') ?> value="800">800</option>
                            <option <?php selected($setting['sub-title-weight'], '900') ?> value="900">900</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4><?php _e("Text", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Content", 'instalogin') ?></span>
                        <textarea type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[text-text]">
<?php echo esc_attr($setting['text-text']) ?>
                        </textarea>
                    </label>
                    <label>
                        <span><?php _e("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[text-size]" value="<?php echo esc_attr($setting['text-size']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Color", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[text-color]" value="<?php echo esc_attr($setting['text-color']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Weight", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[text-weight]">
                            <option <?php selected($setting['text-weight'], '100') ?> value="100">100</option>
                            <option <?php selected($setting['text-weight'], '200') ?> value="200">200</option>
                            <option <?php selected($setting['text-weight'], '300') ?> value="300">300</option>
                            <option <?php selected($setting['text-weight'], '400') ?> value="400">400</option>
                            <option <?php selected($setting['text-weight'], '500') ?> value="500">500</option>
                            <option <?php selected($setting['text-weight'], '600') ?> value="600">600</option>
                            <option <?php selected($setting['text-weight'], '700') ?> value="700">700</option>
                            <option <?php selected($setting['text-weight'], '800') ?> value="800">800</option>
                            <option <?php selected($setting['text-weight'], '900') ?> value="900">900</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4><?php _e("Button", 'instalogin') ?></h4>
                    <label>
                        <span><?php _e("Text", 'instalogin') ?></span>
                        <input type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[button-text]" value="<?php echo esc_attr($setting['button-text']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Font Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[button-size]" value="<?php echo esc_attr($setting['button-size']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Border Radius", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 30px" name="<?php echo esc_attr($setting_name) ?>[button-radius]" value="<?php echo esc_attr($setting['button-radius']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Color", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[button-color]" value="<?php echo esc_attr($setting['button-color']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Background", 'instalogin') ?></span>
                        <input type="color" name="<?php echo esc_attr($setting_name) ?>[button-bg]" value="<?php echo esc_attr($setting['button-bg']) ?>">
                    </label>
                    <label>
                        <span><?php _e("Weight", 'instalogin') ?></span>
                        <select name="<?php echo esc_attr($setting_name) ?>[button-weight]">
                            <option <?php selected($setting['button-weight'], '100') ?> value="100">100</option>
                            <option <?php selected($setting['button-weight'], '200') ?> value="200">200</option>
                            <option <?php selected($setting['button-weight'], '300') ?> value="300">300</option>
                            <option <?php selected($setting['button-weight'], '400') ?> value="400">400</option>
                            <option <?php selected($setting['button-weight'], '500') ?> value="500">500</option>
                            <option <?php selected($setting['button-weight'], '600') ?> value="600">600</option>
                            <option <?php selected($setting['button-weight'], '700') ?> value="700">700</option>
                            <option <?php selected($setting['button-weight'], '800') ?> value="800">800</option>
                            <option <?php selected($setting['button-weight'], '900') ?> value="900">900</option>
                        </select>
                    </label>
                </div>
            </div>

            <div>
                <h4>Preview</h4>
                <button id="refresh" style="margin-bottom: 4px;" class="button"><?php _e("Save & Refresh", 'instalogin') ?></button>
                <iframe id="preview" src="<?php echo esc_attr(admin_url('?page=popup-preview')) ?>" title="Popup Preview" width="100%" height="650px" frameborder="0"></iframe>


                <script>
                    {
                        const refresh = document.querySelector('#refresh');
                        const form = document.querySelector('form');
                        const preview = document.querySelector('#preview');

                        refresh.addEventListener('click', async (event) => {
                            event.preventDefault();

                            const response = await fetch('options.php', {
                                method: 'post',
                                body: new FormData(form)
                            });

                            let old = preview.src;
                            preview.src = '';
                            preview.src = old;

                        });
                    }
                </script>
            </div>

<?php   }, $page, $section);
    }
}
