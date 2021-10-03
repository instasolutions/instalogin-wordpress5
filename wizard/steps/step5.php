<?php
$user = wp_get_current_user();
$email = $user->user_email;
?>

<style>
    .step5 {
        width: 100%;
        height: 100%;
    }

    .step5 .boxes {

        margin-top: 5rem;
        display: grid;
        grid-template-columns: 500px 1fr;
        align-items: center;
    }

    .step5 .boxes .box {
        padding: 2rem;
        height: 100%;

        display: flex;
        flex-flow: column;
        justify-content: space-between;
    }

    .step5 .boxes .box+.box {
        padding-left: 2rem;
        display: flex;
        align-items: center;
        flex-flow: row;
        justify-content: initial;
    }

    .step5 .head-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2.2rem;
    }

    .step5 .head-row>div {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .step5 .row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.2rem;

    }

    .step5 .ok {
        display: flex;
        gap: 1rem;
    }

    .step5 h1 {
        font-size: 32px !important;
        margin: 0;
    }

    .step5 h2 {
        font-size: 29px;
        margin: 0;
    }

    .step5 h3 {
        font-size: 20px;
        margin: 0;
    }

    .step5 p {
        font-size: 14px;
        margin: 0;
    }

    .step5 .letter {
        color: #1D2327;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bolder;
        font-size: 106px;
        opacity: 17%;
        display: flex;
        justify-content: flex-end;
    }

    .step5 .img-border {
        display: flex;
        gap: 1.2rem;
        padding: 1rem 1.5rem;
        border: 1px solid white;
    }

    .step5 .email {
        background: white;
        padding: .4rem 1rem;
        color: var(--blue);
        border-radius: 1px;
        margin-left: 1rem;
    }
</style>

<section class="step5">

    <div class="head-row">
        <div>
            <img width="70px" src="./images/man.svg" alt="">
            <img width="70px" src="./images/teamwork.svg" alt="">

            <h1 class="blue"><?= __("As user or admin:<br>Connect your smartphone with Instalogin.", 'instalogin') ?></h1>
        </div>
        <div class="letter">B</div>
    </div>


    <h2 style="text-align: center;" class="green">
        <?= __("Connecting your admin account to Instalogin", 'instalogin') ?>
    </h2>

    <div class="boxes bg-gray-light">
        <div class="bg-gray box">
            <p class="gray-dark">
                <?= __("We have sent you an email containing an InstaCode.<br>Download the InstaApp down below and use it to<br>scan the code you received.<br>And that's it!", 'instalogin') ?>
            </p>
            <div>
                <span><?= __("Email was sent to:", 'instalogin') ?></span>
                <span class="email"><?= $email ?></span>
            </div>

            <div class="img-border">
                <img src="./images/App Icon.svg" alt="">
                <img src="./images/Icon awesome-arrow-right.svg" alt="">
                <a rel="noopener" target="_blank" href="https://apps.apple.com/app/instalog-in/id1097751906">
                    <img src="./images/Batch-AppStore.svg" alt="">
                </a>
                <a rel="noopener" target="_blank" href="https://play.google.com/store/apps/details?id=com.instaholding.instalog">
                    <img src="./images/Batch-PlayStore.svg" alt="">
                </a>
            </div>
            <!-- <img class="img-border" src="./images/icon_donwload-appstore.svg" alt=""> -->
        </div>
        <div class="box bg-gray-light">
            <img src="./images/icon_wizard-email.svg" alt="">
        </div>
    </div>
</section>