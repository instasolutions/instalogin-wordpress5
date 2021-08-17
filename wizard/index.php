<?php
include $_SERVER['DOCUMENT_ROOT'] . "/wp-blog-header.php";

// TODO: translation

if (!current_user_can('manage_options')) {
    echo __("You must be an administrator to use the wizard.", "instalogin");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instalogin - Installation</title>
    <!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'> -->
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!-- Container for stepper and buttons. This is just for design in Codepen -->
    <div class="container">
        <div>
            <center>
                <img src="images/logo.png" alt="Instalogin">
            </center>
            <!--  Stepper container. This is the main element that is passed to the stepper class.  -->
            <div class="stepper">

                <!--  Steps must be formatted in this way. The icon will update with it's index.  -->
                <!--  On complete the icon will switch to a material design checkmark  -->
                <div class="steps">
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label">Willkommen</span>
                    </a>
                    <div class="step-divider"></div>
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label">Setup</span>
                    </a>
                    <div class="step-divider"></div>
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label">Aktivieren</span>
                    </a>
                    <div class="step-divider"></div>
                    <a class="step">
                        <span class="icon"></span>
                        <span class="label">Abschließen</span>
                    </a>
                </div>

                <div class="step-container">

                    <!-- Welcome -->
                    <div class="step-content">
                        <h1>Willkommen bei Instalogin</h1>
                        <h2>Der Zwei-Faktor-Authentifizierung in nur einem Schritt!</h2>
                        <p> Instalogin funktioniert sowohl für dich und dein Team im Backend als auch für deine Kunden im Frontend.</p>

                        <p> <a href="https://www.instalog.in/en/test-drive/index.html" target="_blank" rel="noopener"><button class="btn">Registrieren</button></a></p>

                        <p>Du hast bereits einen Account? Dann leg direkt los!</p>
                    </div>

                    <!-- Settings -->
                    <div class="step-content">
                        <h1>Einstellungen</h1>

                        <div class="form">

                            <p>Den Key und das Secret findest du <a href="https://www.instalog.in/en/test-drive/index.html">hier</a>!</p>

                            <label class="">
                                <div>
                                    API Key
                                </div>
                                <input type="text" id="api_key" required>
                                <div class="info info-key">Pflichtfeld. Muss exakt 32 Zeichen lang sein.</div>
                            </label>

                            <label>
                                <div>
                                    API Secret
                                </div>
                                <input type="text" id="api_secret">
                                <div class="info info-secret">Pflichtfeld. Muss exakt 64 Zeichen lang sein.</div>
                            </label>


                            <label style="margin-top: 1rem;">
                                <input type="checkbox" id="enable_registration">
                                <span>
                                    Registrierung per Instalogin erlauben
                                </span>
                            </label>

                            <label style="margin-top: 1rem;">
                                <div>Anzeigetyp</div>
                                <select id="code_type">
                                    <option value="qr">QR Code</option>
                                    <option value="si">Smart Image</option>
                                </select>
                            </label>

                        </div>
                        <p>
                            <button class="btn btn-save">Einstellungen Speichern</button>
                        </p>
                    </div>

                    <!-- Install App -->
                    <div class="step-content">
                        <h1>Lass uns loslegen!</h1>
                        <h2>der Zwei-Faktor-Authentifizierung in nur einem Schritt!</h2>
                        <p>Erhalte eine Aktivierungsmail und scanne anschließend in der App den Verifizierungscode:</p>
                        <p>
                            <button class="btn btn-mail">Sende Aktivierungsmail</button>
                        </p>
                        <p>Du hast die App noch nicht? Lade sie hier herunter:</p>
                        <div>
                            <a href="https://apps.apple.com/app/instalog-in/id1097751906" rel="noopener" target="_blank">
                                <img src="images/appstore.png" width="200px" alt="App Store" style="margin:5px">
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.instaholding.instalog" rel="noopener" target="_blank">
                                <img src="images/google-play.png" width="200px" alt="Google Play" style="margin:5px">
                            </a>
                        </div>
                    </div>

                    <!-- Done -->
                    <div class="step-content">
                        <h1>Das war es schon!</h1>
                        <h2>Die Installation wurde erfolgreich abgeschlossen</h2>
                        <br>
                        Du kannst entweder zum Backend zurückkehren oder den Login direkt testen:
                        <p>
                            <a href="/wp-admin"><button class="btn">Installation beenden</button></a>
                            <a href="<?= wp_logout_url('/wp-login.php') ?>"><button class="btn">Login testen</button></a>
                        </p>

                    </div>


                </div>
            </div>
        </div>

        <!-- Buttons container must be included.  -->
        <!-- Also, each `step-label` is needed  -->
        <div class="buttons">
            <button class="btn step-back" disabled>Zurück</button>
            <button class="btn step-next">Weiter</button>
            <a href="/wp-admin/admin.php?page=instalogin"><button class="btn step-complete">Zurück zum Backend</button></a>
            <button class="btn step-finish">Abschließen</button>
        </div>
    </div>

    <script>
        const nonce = "<?= wp_create_nonce('wp_rest') ?>";
        const user_id = "<?= get_current_user_id() ?>";
    </script>
    <script src="./wizard.js"></script>

</body>

</html>