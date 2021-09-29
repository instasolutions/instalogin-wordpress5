<style>
    .step1 {
        background: var(--gray-light);

        box-shadow: 3px 3px 9px #00000029;

        display: grid;
        grid-template-columns: 1fr 2fr;
        align-items: center;
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
    }

    .step1 p {
        color: var(--blue);
        font-size: 16px;
        margin: 0;
        margin-top: 1rem;
    }
</style>

<section class="step1">
    <div>
        <img src="./images/monitor-welcome.png" alt="">
    </div>
    <div>
        <h1><?= __("Welcome to Instalogin", 'instalogin') ?></h1>
        <h2><?= __("Password free login and two-factor-authentication in one step!", 'instalogin') ?></h2>
        <p><?= __("Instalogin works for you and your team in the backend as well as your customers in the frontend.<br>Our password free login in two steps; ", 'instalogin') ?></p>
    </div>
</section>