<?php

$section_label = get_field('section_label');
$header_text = get_field('header_text');
$slides = get_field('slides');

?>

<section class="uic-section uic-image-card-slider">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <?php if (!empty($section_label)) { ?>
                <div class="uic-section__label">
                    <h2 class="uic-h2" id="<?= sanitize_title($section_label) ?>"><?= $section_label ?></h2>
                </div>
            <?php } ?>
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
                            <?php foreach ($slides as $slide) { ?>
                                <div class="uic-slider__item uic-slider__card">
                                    <?php
                                    if (!empty($slide['image'])) {
                                        $image = $slide['image'];
                                        echo '<img src="' . esc_url($image['sizes']['image-card_thumbnail']) . '"';
                                        if (!empty($image['alt']) || !empty($image['title'])) {
                                            echo ' alt="' . esc_attr($image['alt'] ?? $image['title']) . '"';
                                        }
                                        echo ' class="uic-slider__card__media"  />';
                                    }
                                    ?>

                                    <?php if (!empty($slide['caption_title'])) { ?>
                                        <h4 class="uic-slider__card__title"><?= $slide['caption_title'] ?></h4>
                                    <?php } ?>

                                    <?php if (!empty($slide['caption'])) { ?>
                                        <div class="uic-slider__card__text wysiwyg-content"><?= $slide['caption'] ?></div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
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