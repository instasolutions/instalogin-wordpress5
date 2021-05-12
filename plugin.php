<?php

/**
 * Plugin Name: Instalog.in Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class InstalogIn
{
    public function __construct()
    {
        // wp_enqueue_style('instalog-in-global', plugin_dir_url(__FILE__) . 'style/style.css?v=1');
        // TODO: style on admin pages
        $this->login_controller();
        $this->login_page();
        $this->account_page();
    }

    private function login_controller()
    {
        add_action('rest_api_init', function () {
            register_rest_route('instalogin/v1', '/login-controller', [
                'methods' => 'GET',
                'callback' => function ($request) {
                    $client = new \Instalogin\Client('VluObzy1BoNiFcgm5OXSQun42pF9pFNx', '7e2672af946831902319d3a17573bac3ed3897be1eb3c962b074bd62e75293cb');
                    // TODO: redirect query param
                    // TODO: error handling

                    $auth_header = $request->get_headers()['authorization'][0];
                    $jwt = [mb_substr($auth_header, 7)][0];
                    $token = $client->decodeJwt($jwt);
                    $email = $token->getIdentifier();
                    $user = get_user_by('email', $email);

                    if ($user == false) {
                        return false;
                    }

                    if ($client->verifyToken($token)) {
                        wp_set_auth_cookie($user->id, true, is_ssl());
                        // return ['location' => $user];
                        return ['location' => '/wp-admin'];
                    }

                    return false;
                }
            ]);
        });
    }

    private function account_page()
    {
        $client = new \Instalogin\Client('VluObzy1BoNiFcgm5OXSQun42pF9pFNx', '7e2672af946831902319d3a17573bac3ed3897be1eb3c962b074bd62e75293cb');

        add_action('personal_options', function () {
            $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
                <div>
                    <h3>Instalog.in</h3>
                    Activate instant login via Instalog.in:    
                    <a href="/wp-content/plugins/instalog-in/send_mail.php?redirect=<?=$url?>">Send activation Mail</a>
                </div>
            <?php
        });
    }

    private function login_page()
    {
        add_action('login_head', function () {
            wp_enqueue_style('instalog-in-login', plugin_dir_url(__FILE__) . 'style/login.css?v=1');
        });

        // TODO login_footer fuer js

        add_action('login_footer', function () {
            ?> <script async id="instalogin-js" src="https://cdn.instalog.in/js/instalogin-0.7.1.js"></script> <?php
            wp_enqueue_script('instalog-in-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=1');
        });
    }
}

$instalog_in = new InstalogIn();
