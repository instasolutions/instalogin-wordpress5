<?php

class InstaloginRegisterMail
{
    private $client = null;

    public function __construct($client)
    {
        $this->client = $client;

        // send provision mail as soon as a new users registers
        add_action('user_register', function ($user_id) {

            $api_enabled = get_option('instalogin-api-enabled', 0) == 1;
            if (!$api_enabled) {
                return false;
            }

            $user = get_user_by('id', $user_id);
            $email = $user->user_email;

            try {
                $this->client->provisionIdentity($email, array(
                    'sendEmail' => true // Let Instalogin handle the mail sending
                ));
                return new WP_REST_Response(['sent_to' => $email]);
            } catch (\Instalogin\Exception\TransportException $e) {
                return new WP_REST_Response(__('Could not connect to Instalogin service.', 'instalogin') . " " . $e->getMessage(), 500);
            }
        });
    }
}
