<style>
    .step3 {
        width: 100%;
        height: 100%;
    }

    .step3 .boxes {
        margin-top: 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 2rem;
    }

    .step3 .boxes .box {
        background: var(--gray-light);
        height: 100%;
        padding: 2rem;
        display: flex;
        flex-flow: column;
    }

    .step3 .boxes>div {
        height: 100%;
    }

    .step3 .head-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
    }

    .step3 .head-row>div {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }


    .step3 h1 {
        color: var(--red-dark);
        font-size: 32px !important;
        margin: 0;
        font-weight: bold;
    }

    .step3 h2 {
        color: var(--blue);
        font-size: 34px;
        margin: 0;
        margin-bottom: 2rem;

    }

    .step3 h3 {
        font-size: 20px;
        margin: 0;
        color: var(--blue);
    }

    .step3 p {
        font-size: 16px;
        margin: 0;
        font-weight: normal;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .step3 .letter {
        color: #1D2327;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bolder;
        font-size: 106px;
        opacity: 17%;
        display: flex;
        justify-content: flex-end;
    }

    .step3 .order {
        background: var(--blue);
        color: white;
        border: none;
        font-weight: bold;
        font-size: 16px;
        margin-top: 1rem;
    }

    .step3 .license-settings {
        display: grid;
        grid-template-columns: 6rem 1fr;
        margin: 2rem 0;
        gap: .8rem;
    }

    .step3 .license-settings label {
        font-weight: bold;
    }

    .step3 input[type="text"] {
        background: white;
        border: 2px solid #E4E4E4;
        border-radius: 4px;
        color: var(--gray-dark);
        font-size: 16px;
        padding: .2rem .4rem;
    }

    .step3 .activate-container {
        grid-column: span 2;
        margin-top: 1rem;
    }

    .step3 .activate {
        font-size: 16px;
    }

    .step3 .boxes .box.settings {
        display: grid;
        grid-template-columns: 2fr 1fr 4fr;
        align-items: center;
        gap: 1rem;
        grid-template-rows: repeat(10, min-content);
    }

    .step3 .settings label {
        color: var(--blue-dark);
        font-weight: bold;
        font-size: 14px;
    }

    .step3 .settings p {
        color: var(--gray-dark);
        font-size: 11px;
        font-weight: light;
    }
</style>

<section class="step3">

    <div class="head-row">
        <div>
            <img width="70px" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../images/man.svg" alt="">
            <h1 class="red"><?php _e("Install Instalogin on<br> your website", 'instalogin-me') ?></h1>
        </div>
        <div class="letter">A</div>
    </div>

    <div class="boxes">
        <div>
            <h2><?php _e("License", 'instalogin-me') ?></h2>
            <div class="box">
                <p class="gray-dark"><?php _e("Key and secret are required to secure the communication during the login procedure.<br>They are simultaneously needed to connect to the Instalogin Servers and act as licence.", 'instalogin-me') ?></p>

                <div class="license-settings">
                    <label for="api_key">
                        <span><?php _e("API Key", 'instalogin-me') ?></span>
                    </label>
                    <input id="api_key" type="text">

                    <label for="api_secret">
                        <span><?php _e("API Secret", 'instalogin-me') ?></span>
                    </label>
                    <input id="api_secret" type="text">

                    <div class="activate-container">
                        <button class="activate">Activate License</button>
                    </div>

                </div>

                <p class="gray-dark"><?php _e("If you do not have a license you may place your order here:", 'instalogin-me') ?></p>
                <div>
                    <a href="https://instalogin.me/keysecret/" rel="noopener" target="_blank">
                        <button class="order"><?php _e("Order License", 'instalogin-me') ?></button>
                    </a>
                </div>
            </div>


        </div>

        <div>
            <h2><?php _e("Features", 'instalogin-me') ?></h2>
            <div class="box settings">
                <label for="enable_instalogin">
                    <?php _e('Enable login via Instalogin', 'instalogin-me') ?>
                </label>
                <input type="checkbox" id="enable_instalogin" checked>
                <p><?php _e("Enable or disable all Instalogin methods. Users may not use Instalogin to sign in to or to create new accounts.", 'instalogin-me') ?></p>

                <label for="enable_registration">
                    <?php _e('Activation on Register', 'instalogin-me') ?>
                </label>
                <input type="checkbox" id="enable_registration" checked>
                <p><?php _e("An Instalogin mail will be sent to every new user upon registration.", 'instalogin-me') ?></p>

                <label for="redirect">
                    <?php _e("Redirect", 'instalogin-me') ?>
                </label>
                <input style="max-width: 10ch;" placeholder="/wp-admin" id="redirect" type="text" value="/wp-admin">
                <p><?php _e("Decide to which page users should be redirected to after successfully login in. '/wp-admin' is the common directory.", 'instalogin-me') ?></p>

                <label for="code_type">
                    <div><?php _e('Code Type', 'instalogin-me') ?></div>
                </label>
                <select id="code_type" id="code_type">
                    <option value="qr"><?php _e('InstaCode', 'instalogin-me') ?></option>
                    <option value="si"><?php _e('Smart Image', 'instalogin-me') ?></option>
                </select>
                <p><?php _e("You may use a custom image instead of the InstaCode. More info can be found in the settings later on.", 'instalogin-me') ?></p>


            </div>
        </div>
    </div>
</section>