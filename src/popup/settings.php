<?php

class InstaloginPopupSettings
{
    public function __construct($page)
    {
        $section = 'instalogin-popup';

        // Add to wp
        add_settings_section($section, __('Smartcode', 'instalogin'), function () {
            // Settings Section Title
        }, $page);



        // Use QR Code or Smart Image for login
        $setting_name = 'instalogin-popup-style';
        register_setting($page, $setting_name);
        add_settings_field($setting_name . "_field", __('', "instalogin"), function () {
            $setting_name = 'instalogin-popup-style';

            require_once(dirname(__FILE__) . '/default_settings.php');
            $setting = get_option($setting_name, $default_popup_settings); ?>

            <style>
                .insta-popup-settings {
                    grid-column: span 3;
                }

                .insta-popup-settings label {
                    display: grid;
                    grid-template-columns: 300px 200px;
                    margin-bottom: .5rem;
                }

                .insta-popup-settings h4::after {
                    display: block;
                    content: "";
                    height: 2px;
                    margin-top: .5rem;
                    width: 200px;
                    background: var(--insta-blue-light);
                }
            </style>

            <div class="insta-popup-settings">
                <div>
                    <h4><?= __("Container Design", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Background", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[box-bg]" value="<?= $setting['box-bg'] ?>">
                    </label>
                    <label>
                        <span><?= __("Font Family", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. Verdana" name="<?= $setting_name ?>[font]" value="<?= $setting['font'] ?>">
                    </label>
                    <label>
                        <span><?= __("Popup Trigger", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[trigger]">
                            <option <?php selected($setting['trigger'], 'click') ?> value="click">Click Button</option>
                            <option <?php selected($setting['trigger'], 'hover') ?> value="hover">Hover Button</option>
                        </select>
                    </label>
                    <label>
                        <span><?= __("Shadow", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[box-shadow]">
                            <option <?php selected($setting['box-shadow'], 'on') ?> value="on">On</option>
                            <option <?php selected($setting['box-shadow'], 'off') ?> value="off">Off</option>
                        </select>
                    </label>

                    <label>
                        <span><?= __("Position", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[position]">
                            <option <?php selected($setting['position'], 'top') ?> value="top">Below</option>
                            <option <?php selected($setting['position'], 'bottom') ?> value="bottom">Above</option>
                        </select>
                    </label>

                    <label>
                        <span><?= __("Vertical Offset", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 0px" name="<?= $setting_name ?>[vertical]" value="<?= $setting['vertical'] ?>">
                    </label>

                    <label>
                        <span><?= __("Horizontal Offset", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 0px" name="<?= $setting_name ?>[horizontal]" value="<?= $setting['horizontal'] ?>">
                    </label>

                    <label>
                        <span><?= __("Button Type", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[button-type]">
                            <option <?php selected($setting['button-type'], 'text') ?> value="text">Text</option>
                            <option <?php selected($setting['button-type'], 'icon') ?> value="icon">Icon</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4><?= __("Border", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Style", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[box-border-style]">
                            <option <?php selected($setting['box-border-style'], 'none') ?> value="none">None</option>
                            <option <?php selected($setting['box-border-style'], 'solid') ?> value="solid">Solid</option>
                        </select>
                    </label>
                    <label>
                        <span><?= __("Color", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[box-border-color]" value="<?= $setting['box-border-color'] ?>">
                    </label>
                    <label>
                        <span><?= __("Width", 'instalogin') ?></span>
                        <input type="text" palceholder="e.g. 4px" name="<?= $setting_name ?>[box-border-width]" value="<?= $setting['box-border-width'] ?>">
                    </label>
                    <label>
                        <span><?= __("Radius", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 30px" name="<?= $setting_name ?>[box-border-radius]" value="<?= $setting['box-border-radius'] ?>">
                    </label>
                </div>

                <div>
                    <h4><?= __("QR Code", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Padding", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 16px" name="<?= $setting_name ?>[qr-padding]" value="<?= $setting['qr-padding'] ?>">
                    </label>
                    <label>
                        <span><?= __("Background", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[qr-bg]" value="<?= $setting['qr-bg'] ?>">
                    </label>
                    <label>
                        <span><?= __("Shadow", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[qr-shadow]">
                            <option <?php selected($setting['qr-shadow'], 'on') ?> value="on">On</option>
                            <option <?php selected($setting['qr-shadow'], 'off') ?> value="off">Off</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4><?= __("Title", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Text", 'instalogin') ?></span>
                        <input type="text" placeholder="Sign In" name="<?= $setting_name ?>[title-text]" value="<?= $setting['title-text'] ?>">
                    </label>
                    <label>
                        <span><?= __("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[title-size]" value="<?= $setting['title-size'] ?>">
                    </label>
                    <label>
                        <span><?= __("Color", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[title-color]" value="<?= $setting['title-color'] ?>">
                    </label>
                    <label>
                        <span><?= __("Weight", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[title-weight]">
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
                    <h4><?= __("Sub Title", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Text", 'instalogin') ?></span>
                        <textarea type="text" placeholder="Sign In" name="<?= $setting_name ?>[sub-title-text]">
<?= $setting['sub-title-text'] ?>
                        </textarea>
                    </label>
                    <label>
                        <span><?= __("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[sub-title-size]" value="<?= $setting['sub-title-size'] ?>">
                    </label>
                    <label>
                        <span><?= __("Color", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[sub-title-color]" value="<?= $setting['sub-title-color'] ?>">
                    </label>
                    <label>
                        <span><?= __("Weight", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[sub-title-weight]">
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
                    <h4><?= __("Text", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Content", 'instalogin') ?></span>
                        <textarea type="text" placeholder="Sign In" name="<?= $setting_name ?>[text-text]">
<?= $setting['text-text'] ?>
                        </textarea>
                    </label>
                    <label>
                        <span><?= __("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[text-size]" value="<?= $setting['text-size'] ?>">
                    </label>
                    <label>
                        <span><?= __("Color", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[text-color]" value="<?= $setting['text-color'] ?>">
                    </label>
                    <label>
                        <span><?= __("Weight", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[text-weight]">
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
                    <h4><?= __("Button", 'instalogin') ?></h4>
                    <label>
                        <span><?= __("Text", 'instalogin') ?></span>
                        <input type="text" placeholder="Sign In" name="<?= $setting_name ?>[button-text]" value="<?= $setting['button-text'] ?>">
                    </label>
                    <label>
                        <span><?= __("Size", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[button-size]" value="<?= $setting['button-size'] ?>">
                    </label>
                    <label>
                        <span><?= __("Border Radius", 'instalogin') ?></span>
                        <input type="text" placeholder="e.g. 30px" name="<?= $setting_name ?>[button-radius]" value="<?= $setting['button-radius'] ?>">
                    </label>
                    <label>
                        <span><?= __("Color", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[button-color]" value="<?= $setting['button-color'] ?>">
                    </label>
                    <label>
                        <span><?= __("Background", 'instalogin') ?></span>
                        <input type="color" name="<?= $setting_name ?>[button-bg]" value="<?= $setting['button-bg'] ?>">
                    </label>
                    <label>
                        <span><?= __("Weight", 'instalogin') ?></span>
                        <select name="<?= $setting_name ?>[button-weight]">
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

<?php   }, $page, $section);
    }
}
