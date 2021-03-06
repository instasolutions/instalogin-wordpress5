<style>
    .step1 {
        background: var(--gray-light);

        box-shadow: 3px 3px 9px #00000029;

        display: grid;
        grid-template-columns: 1fr 2fr;
        align-items: center;
        padding: 0 3rem;
        box-sizing: border-box;
        gap: 2.5rem;
        display: flex;
        justify-content: center;
    }

    .step1 h1 {
        color: var(--blue);
        font-size: 40px !important;
        margin: 0;
    }

    .step1 h2 {
        color: var(--red);
        font-size: 29px;
        margin: 0;
        line-height: .9;
    }

    .step1 p {
        color: var(--blue);
        font-size: 16px;
        margin: 0;
        margin-top: 1rem;
    }

    .step1 .max-width {
        max-width: 60ch;
    }
</style>

<section class="step1">
    <div>
        <img src="<?php echo esc_attr(plugin_dir_url(__FILE__)) ?>../images/monitor-welcome.png" alt="">
    </div>
    <div class="max-width">
        <h1><?php _e("Welcome to Instalogin", 'instalogin-me') ?></h1>
        <h2><?php _e("Password free login and two-factor-authentication in one step!", 'instalogin-me') ?></h2>
        <p><?php _e("Instalogin works for you and your team in the backend as well as your customers in the frontend.<br>Our password free login in two steps; ", 'instalogin-me') ?></p>
    </div>
</section>