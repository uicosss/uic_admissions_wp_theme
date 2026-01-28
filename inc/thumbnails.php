<?php


function register_image_card_imagesizes() {
    add_theme_support('post-thumbnails');
    add_image_size('image-card_thumbnail', 365, 225);
    add_image_size('image-card_thumbnail_cropped', 365, 225, true);
}
add_action('after_setup_theme', 'register_image_card_imagesizes');

function register_image_card_thumbnail_option($defaultSizes) {
    return array_merge($defaultSizes, array(
        'image-card_thumbnail' => 'Image Card Thumbnail',
        'image-card_thumbnail_cropped' => 'image-card_thumbnail_cropped'
    ));
}
add_filter('image_size_names_choose', 'register_image_card_thumbnail_option');