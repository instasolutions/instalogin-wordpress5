<?php

class InstaloginPopupMenuItem
{
    public function __construct()
    {


        // Add category to menu builder
        add_filter('customize_nav_menu_available_item_types', function ($item_types) {
            $item_types[] = array(
                'title'      => __('Instalogin', 'instalogin-me'),
                'type_label' => __('Instalogin', 'instalogin-me'),
                'type'       => 'instalogin_nav',
                'object'     => 'instalogin',
            );

            return $item_types;
        });

        // Add item to category
        add_filter('customize_nav_menu_available_items', function ($items = [], $type = '', $object = '', $page = 0) {

            if ('instalogin' !== $object) {
                return $items;
            }

            $items[] = [
                'id'         => 'insta-popup',
                'title'      => __('Instalogin PopUp', 'instalogin-me'),
                'type_label' => __('', 'instalogin-me'),
                'url'        => '#',
            ];


            return $items;
        }, 10, 4);

        // Render popup instead of text
        add_filter('nav_menu_item_title', function ($title, $item) {
            if (is_object($item)) {
                if ($item->post_name == 'instalogin-popup') {
                    require_once(dirname(__FILE__) . '/shortcode.php');
                    return InstaloginPopupShortcode::render_popup();
                }
                return $title;
            }
        }, 10, 2);
    }
}
