<?php

if (!defined('ABSPATH')) {
    exit;
}

class InstaloginSettingsAPI
{
    public function __construct()
    {
        $this->api_verify_license();
        $this->api_set_options();
    }

    private function api_verify_license()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/verify_credentials', [
                'methods' => 'POST',
                'callback' => function ($request) {

                    if (!is_user_logged_in() || !current_user_can('manage_options')) {
                        return new WP_REST_Response(__('You do not have the required permissions to do this!', 'instalogin-me'), 400);
                    }

                    // body params
                    $key = $request['key'];
                    $secret = $request['secret'];
                    $client = null;
                    try {
                        $client = new \Instalogin\Client($key, $secret);
                        $client->check();

                        update_option('instalogin-api-key', $key);
                        update_option('instalogin-api-secret', $secret);

                        return new WP_REST_Response('ok', 200);
                    } catch (\Throwable $th) {
                        return new WP_REST_Response($th->getMessage(), 400);
                    }
                }
            ]);
        });
    }

    protected function api_set_options()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'instalogin/v1',
                '/wizard/settings',
                [
                    'methods' => 'POST',
                    'callback' => function ($request) {

                        // get logged in user
                        if (!is_user_logged_in()) {
                            return new WP_REST_Response(__('You must be logged in to perform this action.', 'instalogin-me'), 403);
                        }

                        // user is admin
                        if (!current_user_can('manage_options')) {
                            return new WP_REST_Response(__('You must be an administrator to perform this action.', 'instalogin-me'), 403);
                        }

                        // body params
                        $enable_instalogin = $request['enable_instalogin'];
                        $enable_registration = $request['enable_registration'];
                        $redirect = $request['redirect'];
                        $code_type = $request['code_type'];


                        // Update Wordpress settings
                        update_option('instalogin-api-enabled', $enable_instalogin);
                        update_option('instalogin-api-registration', $enable_registration);
                        update_option('instalogin-api-redirect', $redirect);
                        update_option('instalogin-api-type', $code_type); //qr, si
                    }
                ]
            );
        });
    }
}
