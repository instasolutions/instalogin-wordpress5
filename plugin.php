<?php

/**
 * Plugin Name: Shortcode Template
 * Description: A Shortcode with attributes.
 * Author: Christian Schemoschek allbut.social UG
 * URL: https://allbut.social
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
