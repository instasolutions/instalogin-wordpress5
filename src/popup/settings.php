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
                .split-view {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
                }

                .insta-popup-settings>div {
                    background: #F5F5F5;
                    padding: 1rem 1.5rem;
                    margin-bottom: 2rem;
                }

                .insta-popup-settings label {
                    display: grid;
                    grid-template-columns: 150px 1fr;
                    margin-bottom: 1rem;
                    color: var(--insta-blue-darker);
                    align-items: center;
                }

                .insta-popup-settings label input,
                .insta-popup-settings label select {
                    max-width: 23ch;
                }

                .insta-popup-settings input[type="color"] {
                    height: 2rem;
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

                .preview-panel {

                    position: relative;
                    margin-top: 3.1rem;

                }

                .preview-panel>div {

                    position: sticky;
                    top: 3.1rem;

                    display: flex;
                    flex-flow: column nowrap;
                    align-items: center;
                    justify-content: flex-start;
                    gap: 1rem;
                }
            </style>

            <div class="split-view">
                <div class="insta-popup-settings">
                    <h3 class="insta-h3"><?php _e("Container Design", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Background", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[box-bg]" value="<?php echo esc_attr($setting['box-bg']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Font Family", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. Verdana" name="<?php echo esc_attr($setting_name) ?>[font]" value="<?php echo esc_attr($setting['font']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Popup Trigger", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[trigger]">
                                <option <?php selected($setting['trigger'], 'click') ?> value="click">Click Button</option>
                                <option <?php selected($setting['trigger'], 'hover') ?> value="hover">Hover Button</option>
                            </select>
                        </label>
                        <label>
                            <span><?php _e("Shadow", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[box-shadow]">
                                <option <?php selected($setting['box-shadow'], 'on') ?> value="on">On</option>
                                <option <?php selected($setting['box-shadow'], 'off') ?> value="off">Off</option>
                            </select>
                        </label>

                        <label>
                            <span><?php _e("Position", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[position]">
                                <option <?php selected($setting['position'], 'top') ?> value="top">Below</option>
                                <option <?php selected($setting['position'], 'bottom') ?> value="bottom">Above</option>
                            </select>
                        </label>

                        <label>
                            <span><?php _e("Vertical Offset", 'instalogin-me') ?></span>
                            <input type="text" palceholder="e.g. 0px" name="<?php echo esc_attr($setting_name) ?>[vertical]" value="<?php echo esc_attr($setting['vertical']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Horizontal Alignment", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[horizontal]">
                                <option <?php selected($setting['horizontal'], 'left') ?> value="left"><?php _e("Left", 'instalogin-me') ?></option>
                                <option <?php selected($setting['horizontal'], 'right') ?> value="right"><?php _e("Right", 'instalogin-me') ?></option>
                            </select>
                        </label>
                    </div>

                    <h3 class="insta-h3"><?php _e("Login(-out) Button", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Button Type", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[login-type]">
                                <option <?php selected($setting['login-type'], 'text') ?> value="text">Text</option>
                                <option <?php selected($setting['login-type'], 'icon') ?> value="icon">Icon</option>
                            </select>
                        </label>

                        <label>
                            <span><?php _e("Padding", 'instalogin-me') ?></span>
                            <input type="text" palceholder="top right bottom left" name="<?php echo esc_attr($setting_name) ?>[login-padding]" value="<?php echo esc_attr($setting['login-padding']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Login Text", 'instalogin-me') ?></span>
                            <input type="text" palceholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[login-text]" value="<?php echo esc_attr($setting['login-text']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Logout Text", 'instalogin-me') ?></span>
                            <input type="text" palceholder="Sign Out" name="<?php echo esc_attr($setting_name) ?>[login-out-text]" value="<?php echo esc_attr($setting['login-out-text']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Font Size", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[login-font-size]" value="<?php echo esc_attr($setting['login-font-size']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Background", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[login-bg]" value="<?php echo esc_attr($setting['login-bg']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[login-color]" value="<?php echo esc_attr($setting['login-color']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Border Radius", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 30px" name="<?php echo esc_attr($setting_name) ?>[login-radius]" value="<?php echo esc_attr($setting['login-radius']) ?>">
                        </label>

                        <label>
                            <span><?php _e("Weight", 'instalogin-me') ?></span>
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
                            <span><?php _e("Size (Icon Only)", 'instalogin-me') ?></span>
                            <input type="text" palceholder="e.g. 4px" name="<?php echo esc_attr($setting_name) ?>[login-size]" value="<?php echo esc_attr($setting['login-size']) ?>">
                        </label>

                        <!-- <label>
                            <span><?php _e("Login Icon", 'instalogin-me') ?></span>
                            <div class="media-selector" style="display: flex; align-items: center; gap: .7rem;">
                                <button class="button"><?php _e("Select Icon", 'instalogin-me') ?></button>
                                <input type="hidden" name="<?php echo esc_attr($setting_name) ?>[login-icon]" value="<?php echo esc_attr($setting['login-icon']) ?>">
                                <img width="32px" height="32px" src="<?php echo esc_attr($setting['login-icon']) ?>" alt="">
                            </div>
                        </label> -->

                        <!-- <label>
                            <span><?php _e("Logout Icon", 'instalogin-me') ?></span>
                            <div class="media-selector" style="display: flex; align-items: center; gap: .7rem;">
                                <button class="button"><?php _e("Select Icon", 'instalogin-me') ?></button>
                                <input type="hidden" name="<?php echo esc_attr($setting_name) ?>[login-icon-out]" value="<?php echo esc_attr($setting['login-icon-out']) ?>">
                                <img width="32px" height="32px" src="<?php echo esc_attr($setting['login-icon-out']) ?>" alt="">
                            </div>
                        </label> -->

                        <label>
                            <span><?php _e("Icon Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[login-icon-color]" value="<?php echo esc_attr($setting['login-icon-color']) ?>">
                        </label>
                    </div>

                    <h3 class="insta-h3"><?php _e("Border", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Style", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[box-border-style]">
                                <option <?php selected($setting['box-border-style'], 'none') ?> value="none">None</option>
                                <option <?php selected($setting['box-border-style'], 'solid') ?> value="solid">Solid</option>
                            </select>
                        </label>
                        <label>
                            <span><?php _e("Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[box-border-color]" value="<?php echo esc_attr($setting['box-border-color']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Width", 'instalogin-me') ?></span>
                            <input type="text" palceholder="e.g. 4px" name="<?php echo esc_attr($setting_name) ?>[box-border-width]" value="<?php echo esc_attr($setting['box-border-width']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Radius", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 30px" name="<?php echo esc_attr($setting_name) ?>[box-border-radius]" value="<?php echo esc_attr($setting['box-border-radius']) ?>">
                        </label>
                    </div>

                    <h3 class="insta-h3"><?php _e("QR Code", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Padding", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 16px" name="<?php echo esc_attr($setting_name) ?>[qr-padding]" value="<?php echo esc_attr($setting['qr-padding']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Background", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[qr-bg]" value="<?php echo esc_attr($setting['qr-bg']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Shadow", 'instalogin-me') ?></span>
                            <select name="<?php echo esc_attr($setting_name) ?>[qr-shadow]">
                                <option <?php selected($setting['qr-shadow'], 'on') ?> value="on">On</option>
                                <option <?php selected($setting['qr-shadow'], 'off') ?> value="off">Off</option>
                            </select>
                        </label>
                    </div>

                    <h3 class="insta-h3"><?php _e("Title", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Text", 'instalogin-me') ?></span>
                            <input type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[title-text]" value="<?php echo esc_attr($setting['title-text']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Size", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[title-size]" value="<?php echo esc_attr($setting['title-size']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[title-color]" value="<?php echo esc_attr($setting['title-color']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Weight", 'instalogin-me') ?></span>
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

                    <h3 class="insta-h3"><?php _e("Sub Title", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Text", 'instalogin-me') ?></span>
                            <textarea type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[sub-title-text]">
<?php echo esc_attr($setting['sub-title-text']) ?></textarea>
                        </label>
                        <label>
                            <span><?php _e("Size", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[sub-title-size]" value="<?php echo esc_attr($setting['sub-title-size']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[sub-title-color]" value="<?php echo esc_attr($setting['sub-title-color']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Weight", 'instalogin-me') ?></span>
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

                    <h3 class="insta-h3"><?php _e("Text", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Content", 'instalogin-me') ?></span>
                            <textarea type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[text-text]"><?php echo wp_kses_post($setting['text-text']) ?></textarea>
                        </label>
                        <label>
                            <span><?php _e("Size", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[text-size]" value="<?php echo esc_attr($setting['text-size']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[text-color]" value="<?php echo esc_attr($setting['text-color']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Weight", 'instalogin-me') ?></span>
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

                    <h3 class="insta-h3"><?php _e("Button", 'instalogin-me') ?></h3>
                    <div>
                        <label>
                            <span><?php _e("Text", 'instalogin-me') ?></span>
                            <input type="text" placeholder="Sign In" name="<?php echo esc_attr($setting_name) ?>[button-text]" value="<?php echo esc_attr($setting['button-text']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Font Size", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 14pt" name="<?php echo esc_attr($setting_name) ?>[button-size]" value="<?php echo esc_attr($setting['button-size']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Border Radius", 'instalogin-me') ?></span>
                            <input type="text" placeholder="e.g. 30px" name="<?php echo esc_attr($setting_name) ?>[button-radius]" value="<?php echo esc_attr($setting['button-radius']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Color", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[button-color]" value="<?php echo esc_attr($setting['button-color']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Background", 'instalogin-me') ?></span>
                            <input type="color" name="<?php echo esc_attr($setting_name) ?>[button-bg]" value="<?php echo esc_attr($setting['button-bg']) ?>">
                        </label>
                        <label>
                            <span><?php _e("Weight", 'instalogin-me') ?></span>
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

                <div class="preview-panel">
                    <div>
                        <iframe id="preview" src="<?php echo esc_attr(admin_url('?page=popup-preview')) ?>" title="Popup Preview" width="100%" height="400px" frameborder="0"></iframe>
                        <button id="refresh" class="insta-button insta-button-red"><?php _e("Save & Update preview", 'instalogin-me') ?></button>
                    </div>


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
            </div>

<?php   }, $page, $section);
    }
}
