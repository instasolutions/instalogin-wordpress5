<?php

if (!defined('ABSPATH')) {
    exit;
}

class InstalogWizardSettingsAPI
{
    public function __construct()
    {
        $this->api_set_options();
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
                            return new WP_REST_Response(__('You must be logged in to perform this action.', 'instalogin'), 403);
                        }

                        // user is admin
                        if (!current_user_can('manage_options')) {
                            return new WP_REST_Response(__('You must be an administrator to perform this action.', 'instalogin'), 403);
                        }

                        // body params
                        $key = $request['api_key'];
                        $secret = $request['api_secret'];
                        $enable_registration = $request['enable_registration'];
                        $code_type = $request['code_type'];

                        // if ($code_type != "qr" && $code_type != "si") {
                        //     $code_type = "qr";
                        // }


                        // Update Wordpress settings
                        update_option('instalogin-api-enabled', 1);
                        update_option('instalogin-api-key', $key);
                        update_option('instalogin-api-secret', $secret);
                        update_option('instalogin-api-registration', $enable_registration);
                        update_option('instalogin-api-type', $code_type); //qr, si
                    }
                ]
            );
        });
    }
}
