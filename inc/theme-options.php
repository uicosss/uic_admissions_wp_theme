<?php


function register_theme_options() {

    // Check function exists.
    if( function_exists('acf_add_options_sub_page') ) {

        // Add parent.
        $parent = acf_add_options_page([
            'page_title'  => __('Theme Config'),
            'menu_title'  => __('Theme Config'),
            'redirect'    => false
        ]);

        if (defined('UIC_SITE_ROLE') && UIC_SITE_ROLE === 'provider') {
            acf_add_options_sub_page([
                'page_title'  => __('Calculator Config'),
                'menu_title'  => __('Calculator Config'),
                'parent_slug' => $parent['menu_slug']
            ]);
        }
    }
}
add_action('acf/init', 'register_theme_options');
