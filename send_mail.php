<?php


require __DIR__ . '/vendor/autoload.php';
$client = new \Instalogin\Client('VluObzy1BoNiFcgm5OXSQun42pF9pFNx', '7e2672af946831902319d3a17573bac3ed3897be1eb3c962b074bd62e75293cb');

// wp_remote_get https://developer.wordpress.org/reference/functions/wp_remote_get/

include_once '../../../wp-blog-header.php';

try {
    // TODO error handling:
    $user = wp_get_current_user();
    $client->provisionIdentity($user->user_email, array(
        'sendEmail' => true // Let Instalogin handle the mail sending
    ));
    
    $url = $_GET['redirect']; ?> 
    <div>
        Sending activation email... You should be redirected automatically in a few seconds.
        <br>
        If not click <a href="<?=$url?>">here</a>.
    </div>
    <script> 
        window.location.href="<?=$url?>?insta-sent=true";
    </script>
    <?php
} catch (\Instalogin\Exception\TransportException $e) {
        echo 'Could not connect to Instalogin service: '.$e->getMessage();
        // TODO: return with error message
    }
