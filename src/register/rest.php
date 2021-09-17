<?php

class InstaloginRegisterAPI
{

    private $client = null;

    public function __construct($client)
    {
        $this->client = $client;
        $this->api();
    }

    private function api()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/register', [
                'methods' => 'POST',
                'callback' => function ($request) {
                    // API Has been disabled in backend
                    $api_enabled = get_option('instalogin-api-enabled') == 1;
                    $registration_enabled = get_option('instalogin-api-registration') == 1;
                    if (!$api_enabled || !$registration_enabled) {
                        return new WP_Error('disabled', __('Registration via Instalogin has been disabled by an administrator.', 'instalogin'));
                    }

                    $client = $this->client;
                    // Check if API set up correctly
                    if ($client == null) {
                        return new WP_REST_Response(__('Sorry, the instalogin API data is incorrect!', 'instalogin'), 500);
                    }

                    // body params
                    $username = $request['username'];
                    $email = $request['email'];

                    if ($email == null || !is_email($email)) {
                        return new WP_REST_Response(__('Sorry, email missing or invalid!', 'instalogin'), 400);
                    }

                    if ($username == null) {
                        $username = $email;
                    }

                    // Create Wordpress user if not exists
                    $result = wp_create_user($username, wp_generate_password(64), $email);
                    if (is_wp_error($result)) {
                        return new WP_Error($result->get_error_code(), $result->get_error_message());
                    }

                    // try {
                    //     $client->provisionIdentity($email, [
                    //         'sendEmail' => true // Let Instalogin handle the mail sending
                    //     ]);
                    // } catch (\Throwable $th) {
                    //     // Delete user if we could not deliver the activation email.
                    //     wp_delete_user($result);

                    //     return new WP_REST_Response(__('Sorry, could not connect to instalogin servers! Account could not be created.', 'instalogin'), 500);
                    // }

                    return;
                }
            ]);
        });
    }
}
