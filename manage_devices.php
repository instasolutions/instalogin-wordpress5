<?php

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class InstalogInDeviceManager
{
    private $client = null;

    public function __construct()
    {

        // Check if API set up correctly
        $api_key = get_option('instalogin-api-key');
        $api_secret = get_option('instalogin-api-secret');
        try {
            $this->client = new \Instalogin\Client($api_key, $api_secret);
        } catch (\Throwable $th) {
            // users are notified via api
        }

        $this->api_add_device();
        $this->api_get_devices();
        $this->api_remove_device();
    }

    protected function api_add_device()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'instalogin/v1',
                '/device/add',
                [
                'methods' => 'POST',
                'callback' => function ($request) {
                    // send error if instalogin client could not be initialized
                    if ($this->client == null) {
                        return new WP_REST_Response(__('Sorry, the instalogin API data is incorrect!', 'instalogin'), 500);
                    }
                    
                    // get logged in user
                    if (!is_user_logged_in()) {
                        return new WP_REST_Response(__('You must be logged in to perform this action.', 'instalogin'), 403);
                    }
                    
                    $user_id = null;
                    if (isset($request['user_id']) && $request['user_id'] != '' && current_user_can('edit_users')) {
                        $user_id = $request['user_id'];
                    } else {
                        $user_id = get_current_user_id();
                    }
                    $user = get_user_by('id', $user_id);
                    $email = $user->user_email;
                    
                    // check plugin settings
                    $api_enabled = get_option('instalogin-api-enabled') == 1;
                    if (!$api_enabled) {
                        return new WP_REST_Response(__('Instalogin API has been disabled by an administrator.', 'instalogin'), 500);
                    }
                    
                    // send mail
                    try {
                        $this->client->provisionIdentity($email, array(
                            'sendEmail' => true // Let Instalogin handle the mail sending
                        ));
                        return new WP_REST_Response(['sent_to' => $email]);
                    } catch (\Instalogin\Exception\TransportException $e) {
                        return new WP_REST_Response(__('Could not connect to Instalogin service.', 'instalogin'), 500);
                    }
                }]
            );
        });
    }

    protected function api_get_devices()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'instalogin/v1',
                '/devices',
                [
                'methods' => 'GET',
                'callback' => function ($request) {
                    // send error if instalogin client could not be initialized
                    if ($this->client == null) {
                        return new WP_REST_Response(__('Sorry, the instalogin API data is incorrect!', 'instalogin'), 500);
                    }

                    // get logged in user
                    $user_id = get_current_user_id();
                    $user = get_user_by('id', $user_id);
                    $email = $user->user_email;

                    // Build readable device array
                    try {
                        $identity = $this->client->getIdentity($email);
                        $tokens = $identity->getTokens();

                        $devices = [];

                        foreach ($tokens as $token) {
                            $devices[] = [
                                'id' => $token->getId(),
                                'created_at' => $token->getCreatedAt(),
                                'name' => $token->getName(),
                                'label' => $token->getLabel(),
                                'model' => $token->getModel(),
                            ];
                        }

                        return new WP_REST_Response(__($devices, 'instalogin'));
                    } catch (\Throwable $th) {
                        // TODO: Proper error message
                        return new WP_REST_Response(__($th, 'instalogin'), 500);
                    }
                }]
            );
        });
    }

    protected function api_remove_device()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'instalogin/v1',
                '/device/remove',
                [
                'methods' => 'POST',
                'callback' => function ($request) {
                    // send error if instalogin client could not be initialized
                    if ($this->client == null) {
                        return new WP_REST_Response(__('Sorry, the instalogin API data is incorrect!', 'instalogin'), 500);
                    }

                    // get logged in user
                    $user_id = get_current_user_id();
                    $user = get_user_by('id', $user_id);
                    $email = $user->user_email;

                    // body params
                    $device_id = $request['device_id'];

                    if ($device_id == null || $device_id == '') {
                        return new WP_REST_Response(__('Missing device_id parameter in body.', 'instalogin'), 400);
                    }

                    // Build readable device array
                    try {
                        $identity = $this->client->getIdentity($email);
                        $tokens = $identity->getTokens();

                        // check if id is actually bound to this user
                        $device_found = false;
                        foreach ($tokens as $token) {
                            if ($device_id === $token->getId()) {
                                $device_found = true;
                                break;
                            }
                        }

                        if ($device_found) {
                            $this->client->deleteToken($device_id);
                        } else {
                            return new WP_REST_Response(__('Device id does not belong to this identity.', 'instalogin'), 400);
                        }

                        return new WP_REST_Response(true);
                    } catch (\Throwable $th) {
                        // TODO: Proper error message
                        return new WP_REST_Response(__($th, 'instalogin'), 500);
                    }
                }]
            );
        });
    }
}
