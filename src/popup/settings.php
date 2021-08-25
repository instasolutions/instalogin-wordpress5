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
                    grid-template-columns: 1fr 1fr;
                    margin-bottom: .5rem;
                }
            </style>

            <div class="insta-popup-settings">
                <div>
                    <h4>Box Style</h4>
                    <label>
                        <span>Background</span>
                        <input type="color" name="<?= $setting_name ?>[box-bg]" value="<?= $setting['box-bg'] ?>">
                    </label>
                    <label>
                        <span>Font Family</span>
                        <input type="text" placeholder="e.g. Verdana" name="<?= $setting_name ?>[font]" value="<?= $setting['font'] ?>">
                    </label>
                    <label>
                        <span>Popup Trigger</span>
                        <select name="<?= $setting_name ?>[trigger]">
                            <option <?php selected($setting['trigger'], 'click') ?> value="click">Click Button</option>
                            <option <?php selected($setting['trigger'], 'hover') ?> value="hover">Hover Button</option>
                        </select>
                    </label>
                    <label>
                        <span>Shadow</span>
                        <select name="<?= $setting_name ?>[box-shadow]">
                            <option <?php selected($setting['box-shadow'], 'on') ?> value="on">On</option>
                            <option <?php selected($setting['box-shadow'], 'off') ?> value="off">Off</option>
                        </select>
                    </label>

                    <label>
                        <span>Position</span>
                        <select name="<?= $setting_name ?>[position]">
                            <option <?php selected($setting['position'], 'top') ?> value="top">Below</option>
                            <option <?php selected($setting['position'], 'bottom') ?> value="bottom">Above</option>
                        </select>
                    </label>

                    <label>
                        <span>Vertical Offset</span>
                        <input type="text" palceholder="e.g. 0px" name="<?= $setting_name ?>[vertical]" value="<?= $setting['vertical'] ?>">
                    </label>

                    <label>
                        <span>Horizontal Offset</span>
                        <input type="text" palceholder="e.g. 0px" name="<?= $setting_name ?>[horizontal]" value="<?= $setting['horizontal'] ?>">
                    </label>
                </div>

                <div>
                    <h4>Border</h4>
                    <label>
                        <span>Style</span>
                        <select name="<?= $setting_name ?>[box-border-style]">
                            <option <?php selected($setting['box-border-style'], 'none') ?> value="none">None</option>
                            <option <?php selected($setting['box-border-style'], 'solid') ?> value="solid">Solid</option>
                        </select>
                    </label>
                    <label>
                        <span>Color</span>
                        <input type="color" name="<?= $setting_name ?>[box-border-color]" value="<?= $setting['box-border-color'] ?>">
                    </label>
                    <label>
                        <span>Width</span>
                        <input type="text" palceholder="e.g. 4px" name="<?= $setting_name ?>[box-border-width]" value="<?= $setting['box-border-width'] ?>">
                    </label>
                    <label>
                        <span>Radius</span>
                        <input type="text" placeholder="e.g. 30px" name="<?= $setting_name ?>[box-border-radius]" value="<?= $setting['box-border-radius'] ?>">
                    </label>
                </div>

                <div>
                    <h4>QR Code</h4>
                    <label>
                        <span>Padding</span>
                        <input type="text" placeholder="e.g. 16px" name="<?= $setting_name ?>[qr-padding]" value="<?= $setting['qr-padding'] ?>">
                    </label>
                    <label>
                        <span>Background</span>
                        <input type="color" name="<?= $setting_name ?>[qr-bg]" value="<?= $setting['qr-bg'] ?>">
                    </label>
                    <label>
                        <span>Shadow</span>
                        <select name="<?= $setting_name ?>[qr-shadow]">
                            <option <?php selected($setting['qr-shadow'], 'on') ?> value="on">On</option>
                            <option <?php selected($setting['qr-shadow'], 'off') ?> value="off">Off</option>
                        </select>
                    </label>
                </div>

                <div>
                    <h4>Title</h4>
                    <label>
                        <span>Text</span>
                        <input type="text" placeholder="Sign In" name="<?= $setting_name ?>[title-text]" value="<?= $setting['title-text'] ?>">
                    </label>
                    <label>
                        <span>Size</span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[title-size]" value="<?= $setting['title-size'] ?>">
                    </label>
                    <label>
                        <span>Color</span>
                        <input type="color" name="<?= $setting_name ?>[title-color]" value="<?= $setting['title-color'] ?>">
                    </label>
                    <label>
                        <span>Weight</span>
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
                    <h4>Sub Title</h4>
                    <label>
                        <span>Text</span>
                        <textarea type="text" placeholder="Sign In" name="<?= $setting_name ?>[sub-title-text]">
<?= $setting['sub-title-text'] ?>
                        </textarea>
                    </label>
                    <label>
                        <span>Size</span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[sub-title-size]" value="<?= $setting['sub-title-size'] ?>">
                    </label>
                    <label>
                        <span>Color</span>
                        <input type="color" name="<?= $setting_name ?>[sub-title-color]" value="<?= $setting['sub-title-color'] ?>">
                    </label>
                    <label>
                        <span>Weight</span>
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
                    <h4>Text</h4>
                    <label>
                        <span>Text</span>
                        <textarea type="text" placeholder="Sign In" name="<?= $setting_name ?>[text-text]">
<?= $setting['text-text'] ?>
                        </textarea>
                    </label>
                    <label>
                        <span>Size</span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[text-size]" value="<?= $setting['text-size'] ?>">
                    </label>
                    <label>
                        <span>Color</span>
                        <input type="color" name="<?= $setting_name ?>[text-color]" value="<?= $setting['text-color'] ?>">
                    </label>
                    <label>
                        <span>Weight</span>
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
                    <h4>Button</h4>
                    <label>
                        <span>Text</span>
                        <input type="text" placeholder="Sign In" name="<?= $setting_name ?>[button-text]" value="<?= $setting['button-text'] ?>">
                    </label>
                    <label>
                        <span>Size</span>
                        <input type="text" placeholder="e.g. 14pt" name="<?= $setting_name ?>[button-size]" value="<?= $setting['button-size'] ?>">
                    </label>
                    <label>
                        <span>Border Radius</span>
                        <input type="text" placeholder="e.g. 30px" name="<?= $setting_name ?>[button-radius]" value="<?= $setting['button-radius'] ?>">
                    </label>
                    <label>
                        <span>Color</span>
                        <input type="color" name="<?= $setting_name ?>[button-color]" value="<?= $setting['button-color'] ?>">
                    </label>
                    <label>
                        <span>Background</span>
                        <input type="color" name="<?= $setting_name ?>[button-bg]" value="<?= $setting['button-bg'] ?>">
                    </label>
                    <label>
                        <span>Weight</span>
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

 
// - hover instead of click
// - box position top, bottom, right, left
// - font
// - qr
// - - shadow
// - - bg
// - - padding
// - box style
// - - bg
// - - border
// - - border radius
// - - border color
// - - shadow
// - title
// - - text
// - - size
// - - color
// - - weight
// - - underline
// - Sub title
// - - size
// - - color
// - - font
// - - weight
// - Text
// - - size
// - - color
// - - weight
// - Button
// - - bg
// - - color
// - - shadow
// - - size
// - - padding
