<style>
    .step2 {
        width: 100%;
        height: 100%;
    }

    .step2 .boxes {

        margin-top: 3rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 2rem;
    }

    .step2 .boxes .box {
        box-shadow: 3px 3px 9px #00000029;
        background: var(--gray-light);
        height: 100%;
        padding: 2rem;
        display: flex;
        flex-flow: column;
        justify-content: space-between;
    }

    .step2 .row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2.2rem;

    }

    .red {
        color: var(--red);
    }

    .step2 h1 {
        color: var(--blue-dark);
        font-size: 32px !important;
        margin: 0;
    }

    .step2 h2 {
        color: var(--blue-dark);
        font-size: 29px;
        margin: 0;
    }

    .step2 h3 {
        font-size: 20px;
        margin: 0;
        color: var(--blue);
    }

    .step2 p {
        color: var(--blue);
        font-size: 16px;
        margin: 0;
        margin-top: 1rem;
    }

    .step2 .letter {
        color: #1D2327;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bolder;
        font-size: 106px;
        opacity: 17%;
        display: flex;
        justify-content: flex-end;
    }
</style>

<section class="step2">

    <h1><?php _e("This setup consists of 2 parts:", 'instalogin') ?></h1>

    <div class="boxes">
        <div class="box">
            <div>
                <div class="row">
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>../images/man.svg" alt="">
                    <h3 class="red"><?php _e("Website Owners & Admins", 'instalogin') ?></h3>
                </div>

                <h2><?php _e("Install the Instalogin plugin in Wordpress.", 'instalogin') ?></h2>
            </div>
            <div class="letter">A</div>
        </div>

        <div class="box">
            <div>
                <div class="row">
                    <img src="<?php echo plugin_dir_url(__FILE__) ?>../images/teamwork.svg" alt="">
                    <h3><?php _e("Website Users", 'instalogin') ?></h3>
                </div>
                <h2><?php _e("As admin or customer:<br>Connect your smartphone to enable login.", 'instalogin') ?></h2>
            </div>
            <div class="letter">B</div>
        </div>
    </div>
</section>