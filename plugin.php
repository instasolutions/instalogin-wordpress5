<?php

/**
 * Plugin Name: Instalog.in Integration
 */

class InstalogIn
{
    private $title = "";
    private $content = "";


    public function __construct()
    {
        add_shortcode(self::shortcode_tag, array($this, 'shortcode'));
    }
}

$instalog_in = new InstalogIn();
