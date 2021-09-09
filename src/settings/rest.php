<?php

class InstaloginSettingsAPI
{
    public function __construct()
    {
        $this->api();
    }

    private function api()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/verify_credentials', [
                'methods' => 'POST',
                'callback' => function ($request) {

                    if (!is_user_logged_in() || !current_user_can('manage_options')) {
                        return new WP_REST_Response(__('You do not have the required permissions to do this!', 'instalogin'), 400);
                    }

                    // body params
                    $key = $request['key'];
                    $secret = $request['secret'];
                    $client = null;
                    try {
                        $client = new \Instalogin\Client($key, $secret);
                        $client->check();

                        return new WP_REST_Response('ok', 200);
                    } catch (\Throwable $th) {
                        return new WP_REST_Response($th->getMessage(), 400);
                    }
                }
            ]);
        });
    }
}
