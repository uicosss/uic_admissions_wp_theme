<?php
// ACF Blocks

function acf_blocks_init()
{
    // Add Blocks Here
    // acf_register_block_type(array(
    // 'name'              => 'block-name',
    // 'title'             => __('Block Name'),
    //  'description'       => __("A description about the block"),
    //  'render_template'   => '/template-parts/blocks/',
    // 'category'          => category,
    // ));
    acf_register_block_type(array(
        'name'              => 'facts-slider',
        'title'             => __('Facts Slider'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/facts-slider.php',
        'category'          => 'blocks',
        'icon'              => 'slides',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'alert',
        'title'             => __('Alert'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/alert.php',
        'category'          => 'blocks',
        'icon'              => 'info-outline',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'back-to-top',
        'title'             => __('Back To Top'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/back-to-top.php',
        'category'          => 'blocks',
        'icon'              => 'arrow-up-alt2',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'image-card-slider',
        'title'             => __('Image Card Slider'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/image-card-slider.php',
        'category'          => 'blocks',
        'icon'              => 'embed-photo',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'hero',
        'title'             => __('Hero'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/hero.php',
        'category'          => 'blocks',
        'icon'              => 'images-alt2',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'video-hero',
        'title'             => __('Video Hero'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/video-hero.php',
        'category'          => 'blocks',
        'icon'              => 'format-video',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'calculator',
        'title'             => __('Calculator'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/calculator.php',
        'category'          => 'blocks',
        'icon'              => 'calculator',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'tour-ribbon',
        'title'             => __('Tour Ribbon'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/tour-ribbon.php',
        'category'          => 'blocks',
        'icon'              => 'editor-insertmore',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'icon-cards',
        'title'             => __('Icon Cards'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/icon-cards.php',
        'category'          => 'blocks',
        'icon'              => 'ellipsis',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'video-card-slider',
        'title'             => __('Video Card Slider'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/video-card-slider.php',
        'category'          => 'blocks',
        'icon'              => 'embed-video',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'events',
        'title'             => __('Events'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/events.php',
        'category'          => 'blocks',
        'icon'              => 'calendar-alt',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'social-slider',
        'title'             => __('Social Slider'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/social-slider.php',
        'category'          => 'blocks',
        'icon'              => 'twitter',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'resource-links',
        'title'             => __('Resource Links'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/resource-links.php',
        'category'          => 'blocks',
        'icon'              => 'insert',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'cta-footer',
        'title'             => __('CTA Footer'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/cta-footer.php',
        'category'          => 'blocks',
        'icon'              => 'insert',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    // Landing Page Blocks
    acf_register_block_type(array(
        'name'              => 'landing-hero',
        'title'             => __('Landing Hero'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/landing-hero.php',
        'category'          => 'blocks',
        'icon'              => 'images-alt2',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'landing-wysiwyg',
        'title'             => __('Landing Wysiwyg'),
        'description'       => __(''),
        'render_template'   => 'template-parts/blocks/landing-wysiwyg.php',
        'category'          => 'blocks',
        'icon'              => 'insert',
        'mode'              => 'edit',
        'keywords'          => []
    ));
    acf_register_block_type(array(
        'name'              => 'landing-fab',
        'title'             => __('Landing FAB'),
        'description'       => __(''),
        'render_template'   => 'template-parts/components/landing-fab.php',
        'category'          => 'blocks',
        'icon'              => 'marker',
        'mode'              => 'edit',
        'keywords'          => []
    ));
}

// Check if function exists and hook into setup.
if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'acf_blocks_init');
}

define('VIMEO_REGEX_ID', '/^\s*(\d+)\s*$/');
define('VIMEO_REGEX_LINK', '/vimeo\.com\/(\d+)/');
define('VIMEO_REGEX_EMBED', '/player\.vimeo\.com\/video\/(\d+)/');

function update_vimeo_id($value, $post_id, $field, $original)
{
    $matches = null;
    preg_match(VIMEO_REGEX_ID, $value, $matches);
    if ($matches) return $matches[1];
    preg_match(VIMEO_REGEX_LINK, $value, $matches);
    if ($matches) return $matches[1];
    preg_match(VIMEO_REGEX_EMBED, $value, $matches);
    if ($matches) return $matches[1];
    else return __('Invalid vimeo id given. Use one of the formats shown below.');
}
add_filter('acf/update_value/name=vimeo_id', 'update_vimeo_id', 10, 4);

function validate_vimeo_id($valid, $value, $field, $input_name)
{
    if ($valid !== true) return $valid;
    $matches = null;
    if (preg_match(VIMEO_REGEX_ID, $value, $matches)) return true;
    else if (preg_match(VIMEO_REGEX_LINK, $value, $matches)) return true;
    else if (preg_match(VIMEO_REGEX_EMBED, $value, $matches)) return true;
    else return __('Invalid vimeo id given. Use one of the formats shown below.');
}
add_action('acf/validate_value/name=vimeo_id', 'validate_vimeo_id', 10, 4);

/**
 * Unless we manually trigger the validation, ACF will NOT validate fields in guttenberg blocks.
 * Without this, fields that are required can be empty, numbers and string length can exceed min/max.
 * 
 * This is an issue which has been since guttenberg was introduced (like 5+ years ago) an is unresolved.
 */
function actually_validate_acf_fields()
{
    foreach ($_POST as $key => $value) {
        if (str_starts_with($key, 'acf-') && !empty($value)) {
            acf_validate_values($value, $key);
        }
    }
}
add_action('acf/validate_save_post', 'actually_validate_acf_fields', 5);
