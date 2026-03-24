<?php

// Autoload Guzzle
require_once(__DIR__ . '/vendor/autoload.php');

// Theme Setup
require_once(__DIR__ . '/inc/theme-setup.php');

// Custom Option Pages
require_once(__DIR__ . '/inc/theme-options.php');

// Theme Constants
require_once(__DIR__ . '/inc/theme-constants.php');

// Theme Utilities
require_once(__DIR__ . '/inc/theme-utilities.php');

// Theme Forms
require_once(__DIR__ . '/inc/theme-forms.php');

// Enqueue Styles
require_once(__DIR__ . '/inc/theme-styles.php');

// Enqueue Scripts
require_once(__DIR__ . '/inc/theme-scripts.php');

// Theme Navigation
require_once(__DIR__ . '/inc/theme-navigation.php');

// ACF Blocks
require_once(__DIR__ . '/inc/acf-blocks.php');

// Custom Thumbnails
require_once(__DIR__ . '/inc/thumbnails.php');

// Load Tuition Calculator API
require_once(__DIR__ . '/inc/api-provider.php');
require_once(__DIR__ . '/inc/api-client.php');

// DELETE when complete
add_filter('template_include', 'var_template_include', 1000);
function var_template_include($t)
{
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}

function get_current_template($echo = false)
{
    if (!isset($GLOBALS['current_theme_template']))
        return false;
    if ($echo)
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}
