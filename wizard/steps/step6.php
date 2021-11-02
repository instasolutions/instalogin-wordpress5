<?php
$user = wp_get_current_user();
$email = $user->user_email;
?>

<style>
    .step6 {
        width: 100%;
        height: 100%;
    }

    .step6 .boxes {

        margin-top: 5rem;
        display: grid;
        grid-template-columns: 1fr 500px;
        align-items: center;
    }

    .step6 .boxes .box {
        padding: 2rem;
        height: 100%;

        display: flex;
        flex-flow: column;
        /* justify-content: space-between; */
    }

    .step6 .boxes .box+.box {
        padding-left: 2rem;
        display: flex;
        /* align-items: center; */
        justify-content: center;
        gap: 1rem;
        flex-flow: column;
    }

    .step6 .head-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2.2rem;
    }

    .step6 .head-row>div {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .step6 .row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.2rem;

    }

    .step6 .ok {
        display: flex;
        gap: 1rem;
    }

    .step6 h1 {
        font-size: 32px !important;
        margin: 0;
    }

    .step6 h2 {
        font-size: 29px;
        margin: 0;
    }

    .step6 h3 {
        font-size: 20px;
        margin: 0;
    }

    .step6 p {
        font-size: 14px;
        margin: 0;
    }

    .step6 .letter {
        color: #1D2327;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bolder;
        font-size: 106px;
        opacity: 17%;
        display: flex;
        justify-content: flex-end;
    }

    .step6 .img {
        padding: 1.4rem;
        margin-right: -40px;
    }

    .step6 .img-border {
        display: flex;
        gap: 1.2rem;
        padding: 1rem 0;
        border: 1px solid white;
    }

    .step6 .email {
        background: white;
        padding: .4rem 1rem;
        color: var(--blue);
        border-radius: 1px;
        margin-left: 1rem;
    }

    .step6 .grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
    }

    .step6 summary {
        font-size: 18px;
        color: var(--red);

        margin: 1.2rem 0;
    }

    .step6 .title {
        margin: .3rem 0;
        margin-right: 4rem;
        font-weight: bold;
        font-size: 14px;

    }

    .step6 .right {
        display: flex;
        justify-content: flex-end;
    }

    .step6 .row-el {
        padding: .2rem .6rem;
        margin-top: .2rem;
    }

    .step6 .info-row {
        display: flex;
        gap: 1rem;
        margin-top: 3rem;

    }

    .step6 .info-row button {
        color: white;
        background: var(--green);
        font-weight: bold;
        /* padding: .8rem 1.4rem; */
        box-shadow: 0px 3px 6px #00000029;
        border: 1px solid #45CD6A;
        font-size: 16px;
    }


    .step6 .info-row p {
        color: var(--green);
        font-size: 16px;

    }
</style>

<section class="step6">

    <div class="head-row">
        <div>
            <img width="70px" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../images/man.svg" alt="">
            <img width="70px" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../images/teamwork.svg" alt="">

            <h1 class="blue"><?php _e("What do I have to do,<br> to use Instalogin as a registered user on this website?", 'instalogin') ?></h1>
        </div>
        <div class="letter">B</div>
    </div>


    <h2 style="text-align: center;" class="green">
        <?php _e("In your user profile use the Instalogin settings to add your smartphone", 'instalogin') ?>
    </h2>

    <div class="boxes bg-gray-light">
        <div class="bg-gray-light box">

            <details open>
                <summary><?php _e("Manage Instalogin Devices", 'instalogin') ?></summary>

                <div class="grid">
                    <div class="title blue-dark"><?php _e("Device", 'instalogin') ?></div>
                    <div class="title blue-dark"><?php _e("Name", 'instalogin') ?></div>
                    <div class="title gray-dark"><?php _e("Added at", 'instalogin') ?></div>
                    <div></div>

                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray right red">X</div>

                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray right red">X</div>

                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray right red">X</div>

                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray right red">X</div>

                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray"></div>
                    <div class="row-el bg-gray right red">X</div>
                </div>

                <div class="info-row">
                    <a href="<?php echo esc_attr(admin_url('/profile.php')) ?>" target="_blank">
                        <button class="add-button"><?php _e("Add login device", 'instalogin') ?></button>
                    </a>
                    <p><?php _e("This will send a confirmation mail to your email address.<br>Scan the code with the InstaApp to add the device.", 'instalogin') ?></p>
                </div>
            </details>

        </div>
        <div class="box bg-gray">
            <h3 style="text-align: center;" class="red"><?php _e("Scan the mail and it's all done!", 'instalogin') ?></h3>
            <img class="img" src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../images/icon_wizard-email-finished.svg" alt="">
        </div>
    </div>
</section>