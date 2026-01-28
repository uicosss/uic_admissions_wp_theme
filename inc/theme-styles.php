<?php

function register_theme_styles() {
    $manifest = json_decode(file_get_contents(__DIR__ . '/../dist/manifest.json'), true);

    wp_enqueue_style('vendor', THEME_ASSET_BASE . '/' . $manifest['vendor.css']);
    wp_enqueue_style('client', THEME_ASSET_BASE . '/' . $manifest['client.css']);
}

add_action('wp_enqueue_scripts', 'register_theme_styles');
