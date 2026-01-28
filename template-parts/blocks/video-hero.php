<?php

$section_label = get_field('section_label');
$header_text = get_field('header_text');
$hero_text = get_field('hero_text');
$vimeo_id = get_field('vimeo_id');
$thumbnail = get_field('thumbnail');

$vimeo_meta = get_vimeo_meta($vimeo_id);
$video_dims = null;

if ($vimeo_meta) {
    $video_dims = [
        'height' => $vimeo_meta->height,
        'width' => $vimeo_meta->width
    ];
}

$display_mapping = [
    'discover' => 'Discover',
    'explore' => 'Explore',
    'connect' => 'Connect',
    'become' => 'Become'
];


$preloaded_video_dims = '';
if ($video_dims) {
    $preloaded_video_dims = ' style="--uic-video-hero--video-width: ' . $video_dims['width'] . '; --uic-video-hero--video-height: ' . $video_dims['height'] . ';"';
}

?>

<section class="uic-video-hero" data-video-id="<?= $vimeo_id ?>"<?=$preloaded_video_dims?>>
    <div class="uic-video-hero__content">
        <div class="uic-section">
            <div class="uic-section__container">
                <div class="uic-section__inner">
                    <?php if (!empty($section_label)) { ?>
                        <div class="uic-section__label">
                            <h2 class="uic-h2" id="<?= sanitize_title($section_label) ?>"><?= $section_label ?></h2>
                        </div>
                    <?php } ?>
                    <div class="uic-video-hero__content__inner">
                        <svg 
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="uic-video-hero__header"
                            aria-label="<?= esc_attr($display_mapping[$hero_text])?>"
                            viewBox="<?= esc_attr(HERO_TEXT_VIEWBOX[$hero_text]) ?>">
                            <use xlink:href="<?= THEME_ASSET_BASE ?>/images/hero/text/hero-text-<?= $hero_text ?>.svg#uic-hero__slider__text" />
                        </svg>
                        <?php if (!empty($header_text)) { ?>
                            <div class="uic-video-hero__subheader"><?= $header_text ?></div>
                        <?php } ?>
                        <button class="uic-video-hero__control uic-video-hero__control--play" title="Play Video">Play Video</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="uic-video-hero__thumbnail" style="background-image: url('<?= esc_attr($thumbnail['url']) ?>');"></div>
    <div class="uic-video-hero__video">

    </div>
</section>
