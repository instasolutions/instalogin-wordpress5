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
            <img width="70px" src="./images/man.svg" alt="">
            <h1 class="red"><?= __("Install Instalogin on<br> your website", 'instalogin') ?></h1>
        </div>
        <div class="letter">A</div>
    </div>

    <div class="boxes">
        <div>
            <h2><?= __("License", 'instalogin') ?></h2>
            <div class="box">
                <p class="gray-dark"><?= __("Key and secret are required to secure the communication during the login procedure.<br>They are simultaneously needed to connect to the Instalogin Servers and act as licence.", 'instalogin') ?></p>

                <div class="license-settings">
                    <label for="api_key">
                        <span><?= __("API Key", 'instalogin') ?></span>
                    </label>
                    <input id="api_key" type="text">

                    <label for="api_secret">
                        <span><?= __("API Secret", 'instalogin') ?></span>
                    </label>
                    <input id="api_secret" type="text">

                    <div class="activate-container">
                        <button class="activate">Activate License</button>
                    </div>

                </div>

                <p class="gray-dark"><?= __("If you do not have a license you may place your order here:", 'instalogin') ?></p>
                <div>
                    <a href="https://instalogin.me/product-category/licence/" rel="noopener" target="_blank">
                        <button class="order"><?= __("Order License", 'instalogin') ?></button>
                    </a>
                </div>
            </div>


        </div>

        <div>
            <h2><?= __("Features", 'instalogin') ?></h2>
            <div class="box settings">
                <label for="enable_instalogin">
                    <?= __('Enable login via Instalogin', 'instalogin') ?>
                </label>
                <input type="checkbox" id="enable_instalogin" checked>
                <p><?= __("Enable or disable all Instalogin methods. Users may not use Instalogin to sign in to or to create new accounts.", 'instalogin') ?></p>

                <label for="enable_registration">
                    <?= __('Activation on Register', 'instalogin') ?>
                </label>
                <input type="checkbox" id="enable_registration" checked>
                <p><?= __("An Instalogin mail will be sent to every new user upon registration.", 'instalogin') ?></p>

                <label for="redirect">
                    <?= __("Redirect", 'instalogin') ?>
                </label>
                <input style="max-width: 10ch;" placeholder="/wp-admin" id="redirect" type="text" value="/wp-admin">
                <p><?= __("Decide to which page users should be redirected to after successfully login in. '/wp-admin' is the common directory.", 'instalogin') ?></p>

                <label for="code_type">
                    <div><?= __('Code Type', 'instalogin') ?></div>
                </label>
                <select id="code_type" id="code_type">
                    <option value="qr"><?= __('InstaCode', 'instalogin') ?></option>
                    <option value="si"><?= __('Smart Image', 'instalogin') ?></option>
                </select>
                <p><?= __("You may use a custom image instead of the InstaCode. More info can be found in the settings later on.", 'instalogin') ?></p>


            </div>
        </div>
    </div>
</section>