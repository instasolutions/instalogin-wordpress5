<style>
    .step4 {
        width: 100%;
        height: 100%;
    }

    .step4 .boxes {

        margin-top: 5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 2rem;
    }

    .step4 .boxes .box {
        box-shadow: 3px 3px 9px #00000029;
        background: var(--gray-light);
        height: 100%;
        padding: 2rem;
        display: flex;
        flex-flow: column;
        justify-content: space-between;
    }

    .step4 .head-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2.2rem;
    }

    .step4 .head-row>div {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .step4 .row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.2rem;

    }

    .step4 .ok {
        display: flex;
        gap: 1rem;
    }

    .step4 h1 {
        font-size: 32px !important;
        margin: 0;
    }

    .step4 h2 {
        font-size: 29px;
        margin: 0;
    }

    .step4 h3 {
        font-size: 20px;
        margin: 0;
    }

    .step4 p {
        color: var(--blue);
        font-size: 16px;
        margin: 0;
        margin-top: 1rem;
    }

    .step4 .letter {
        color: #1D2327;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bolder;
        font-size: 106px;
        opacity: 17%;
        display: flex;
        justify-content: flex-end;
    }
</style>

<section class="step4">

    <div class="head-row">
        <div>
            <img width="70px" src="<?php echo plugin_dir_url(__FILE__) ?>../images/man.svg" alt="">
            <h1 class="red-dark"><?= __("Install Instalogin on<br> your website", 'instalogin') ?></h1>
        </div>
        <!-- <div class="letter">A</div> -->
    </div>


    <div class="boxes">
        <div class="box">
            <div>
                <div class="row">
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>../images/man.svg" alt="">
                    <h3 class="red-dark"><?= __("Website Owners & Admins", 'instalogin') ?></h3>
                </div>

                <div class="ok">
                    <img style="margin-top: -1.6rem;" width="90px green" src="<?php echo plugin_dir_url(__FILE__) ?>../images/ok.svg" alt="">
                    <h2 class="green"><?= __("Install the Instalogin plugin in Wordpress.", 'instalogin') ?></h2>
                </div>
            </div>
            <div class="letter">A</div>
        </div>

        <div class="box">
            <div>
                <div class="row">
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>../images/teamwork.svg" alt="">
                    <h3 class="blue-dark"><?= __("Website Users", 'instalogin') ?></h3>
                </div>
                <h2 class="red-dark"><?= __("Next:", 'instalogin') ?></h2>
                <h2 class="blue"><?= __("As admin or customer:<br>Connect your smartphone to enable login.", 'instalogin') ?></h2>
            </div>
            <div class="letter">B</div>
        </div>
    </div>
</section>