<?php

class InstaloginLoginAPI
{

    private $client = null;

    public function __construct($client)
    {
        $this->client = $client;
        $this->login_controller();
    }

    // Login REST API endpoint for the instalog.in js SDK
    private function login_controller()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/login-controller', [
                'methods' => 'GET',
                'callback' => function ($request) {
                    // API Has been disabled in backend
                    $api_enabled = get_option('instalogin-api-enabled');
                    if ($api_enabled != 1) {
                        return new WP_Error('disabled', __('Login via Instalogin has been disabled by an administrator.', 'instalogin'));
                    }

                    $is_desktop = false;
                    $x_requested_with = $request->get_headers()['x_requested_with'];
                    if ($x_requested_with != null && $x_requested_with[0] == 'XMLHttpRequest') {
                        $is_desktop = true;
                    }

                    $auth_header = $request->get_header('x_instalogin_auth');
                    if ($auth_header == null) {

                        $auth_header = $request->get_header('authorization');
                        if ($auth_header == null) {
                            return new WP_REST_Response(__(print_r("Authorization header missing.", true), 'instalogin'), 403);
                        }
                        $auth_header = [mb_substr($auth_header, 7)][0];
                    }

                    $jwt = $auth_header;
                    $token = $this->client->decodeJwt($jwt);

                    $email = $token->getIdentifier();
                    $user = get_user_by('email', $email);

                    if ($user == false) {
                        return new WP_REST_Response(__('Could not find user presented by token.', 'instalogin'), 403);
                    }

                    if ($this->client->verifyToken($token)) {
                        wp_set_auth_cookie($user->id, true, is_ssl());

                        // TODO: Add option redirect to current page?
                        // $redirect = $request->get_header('referer');
                        // if ($redirect == null || strpos($redirect, 'wp-login.php') !== false) {
                        //     $redirect = '/wp-admin';
                        // }

                        $redirect = get_option('instalogin-api-redirect', '/wp-admin');
                        if ($redirect == '') {
                            $redirect = '/wp-admin';
                        }

                        if ($is_desktop) {
                            return ['location' => $redirect];
                        }

                        wp_redirect($redirect, 307);
                        exit;
                    }

                    return new WP_REST_Response(__('Could not verify token.', 'instalogin'), 403);
                }
            ]);
        });
    }
}
