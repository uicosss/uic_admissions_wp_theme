<?php

$header_text = get_field('header_text');
$slides = get_field('slides');

?>

<section class="uic-section uic-video-card-slider">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <?php if (!empty($header_text)) { ?>
                <h3 class="uic-h3"><?= $header_text ?></h3>
            <?php } ?>
            <div class="uic-slider uic-slider--inverted" data-max-page-size="3">
                <div class="uic-slider__track-container">
                    <div cla="uic-slider__controls">
                        <button class="uic-slider__prev" title="Previous Slide">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true">
                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/slider/prev-arrow.svg#uic-slider__prev-arrow" />
                            </svg>
                            <span class="sr-only">Previous Slide</span>
                        </button>
                        <button class="uic-slider__next" title="Next Slide">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true">
                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/slider/next-arrow.svg#uic-slider__next-arrow" />
                            </svg>
                            <span class="sr-only">Next Slide</span>
                        </button>
                    </div>
                    <div class="uic-slider__track" data-glide-el="track">
                        <div class="uic-slider__items">
                            <?php
                            foreach ($slides as $slide) {
                                $meta = get_vimeo_meta($slide['vimeo_id']);
                                $thumbnail = null;
                                if (!empty($slides['image'])) {
                                    $thumbnail = $slides['image']['image-card_thumbnail'];
                                }
                                $video_dims = [
                                    'width' => 560,
                                    'height' => 315
                                ];
                                if (!empty($meta)) {
                                    if (empty($thumbnail)) $thumbnail = $meta->thumbnail_large;
                                    $video_dims['width'] = $meta->width;
                                    $video_dims['height'] = $meta->height;
                                }
                                echo _tag_open('div', ['class' => 'uic-slider__item uic-slider__card']);

                                echo _tag_open('a', [
                                    'class' => 'uic-video-modal__trigger',
                                    'title' => $slide['title'],
                                    'href' => 'https://vimeo.com/' . $slide['vimeo_id'],
                                    'data-iframe' => 'https://player.vimeo.com/video/' . $slide['vimeo_id'] . '?autoplay=1',
                                    'data-thumb' => $thumbnail,
                                    'data-width' => $video_dims['width'],
                                    'data-height' => $video_dims['height']
                                ], false, ['data-iframe', 'data-thumb']);
                                if (!empty($thumbnail)) {
                                    $alt = null;
                                    if (empty($slide['image']['image-card_thumbnail'])) {
                                        if (!empty($slide['image']['image-card_thumbnail']['alt']))  $alt = $slide['image']['image-card_thumbnail']['alt'];
                                        elseif (!empty($slide['image']['image-card_thumbnail']['title'])) $alt = $slide['image']['image-card_thumbnail']['title'];
                                        elseif (!empty($slide['title'])) $alt = $slide['title'];
                                    } elseif (!empty($slide['title'])) $alt = $slide['title'];
                                    echo _tag('div', ['class' => 'uic-slider__card__media'], [
                                        _tag('img', [
                                            'src' => $thumbnail,
                                            'alt' => $alt,
                                            // 'class' => 'uic-slider__card__media'
                                        ]),
                                        _tag('div', ['class' => 'uic-video-card-slider__play-btn'])
                                    ]);
                                }
                                echo _tag('div', ['class' => 'uic-video-card-slider__card__bar']);
                                if (!empty($slide['title'])) {
                                    echo _tag('h4', ['class' => 'uic-slider__card__title'], $slide['title']);
                                }
                                echo _tag_close('a');

                                if (!empty($slide['description'])) {
                                    echo _tag('div', [
                                        'class' => 'uic-slider__card__text wysiwyg-content'
                                    ], $slide['description']);
                                }
                                echo _tag_close('div');
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="uic-slider__dots" data-glide-el="controls[nav]">
                    <?php for ($i = 0; $i < count($slides); $i++) { ?>
                        <button type="button" class="uic-slider__dot" data-glide-dir="=<?= $i ?>" title="Skip to Slide <?= $i + 1 ?>"><span class="sr-only">Skip to Slide <?= $i + 1 ?></span></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>