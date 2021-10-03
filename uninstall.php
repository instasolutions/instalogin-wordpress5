<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$options = [
    'instalogin-api-enabled',
    'instalogin-api-registration',
    'instalogin-api-redirect',
    'instalogin-api-key',
    'instalogin-api-secret',
    'instalogin-api-type',
    'instalogin-popup-style',
];

foreach ($options as $option) {
    delete_option($option);
}
