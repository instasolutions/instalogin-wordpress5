<?php
include_once '../../../wp-blog-header.php';
require __DIR__ . '/vendor/autoload.php';

class InstalogInSendMail
{
    public function __construct()
    {

        // allow sending user back to previous page
        $url = $_GET['redirect'];

        $api_enabled = get_option('instalog-in-api-enabled');
        if ($api_enabled != 1) {
            echo "Instalog.in API has been disabled by an administrator.";
            echo "<br><a href='$url'>Go back.</a>";
            return;
        }

        $api_key = get_option('instalog-in-api-key');
        $api_secret = get_option('instalog-in-api-secret');
        if ($api_key == false || $api_secret == false) {
            echo "Instalog.in API key or secret missing. Please contact a site administrator for help.";
            return;
        }

        // TODO: does not throw on error
        $client = new \Instalogin\Client($api_key, $api_secret);
        
        // Fails if user is not logged in
        $user = wp_get_current_user();

        
        try {
            $client->provisionIdentity($user->user_email, array(
                'sendEmail' => true // Let Instalogin handle the mail sending
            ));
        } catch (\Instalogin\Exception\TransportException $e) {
            echo 'Could not connect to Instalogin service: '.$e->getMessage();
            echo "<br><a href='$url'>Go back.</a>";
            return;
        } ?>
            
        <div>
            Sending activation email... You should be redirected automatically in a few seconds.
            <br>
            If not click <a href="<?=$url?>?sent=true">here</a>.
        </div>
        <script> 
            window.location.href="<?=$url?>?sent=true";
        </script>
        <?php
    }
}

$instalog_in_send_mail = new InstalogInSendMail();
