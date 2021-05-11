<?php

/**
 * Plugin Name: Instalog.in Integration
 */

class InstalogIn
{
    public function __construct()
    {
        // wp_enqueue_style('instalog-in-global', plugin_dir_url(__FILE__) . 'style/style.css?v=1');
        // TODO: style on admin pages
        $this->login_page();
    }

    private function login_page()
    {
        add_action('login_head', function () {
            wp_enqueue_style('instalog-in-login', plugin_dir_url(__FILE__) . 'style/login.css?v=1');
        });

        // TODO login_footer fuer js

        add_action('login_footer', function () {
            ?> <script async id="instalogin-js" src="https://cdn.instalog.in/js/instalogin.js"></script> <?php
            wp_enqueue_script('instalog-in-qr-widget', plugin_dir_url(__FILE__) . 'scripts/login.js?v=1');
        });
    }
}

$instalog_in = new InstalogIn();
