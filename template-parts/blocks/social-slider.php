<?php

$section_label = get_field('section_label');
$header_text = get_field('header_text');
$juicer_feed_id = get_field('juicer_feed_id');
$feed_items_shown = get_field('feed_items_shown');

?>

<section class="uic-section uic-social-slider">
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
            <div class="uic-slider" data-max-page-size="4">
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
                            <?php for($i = 0; $i < $feed_items_shown; $i++) { ?>
                                <div class="uic-slider__item">
                                    <div class="uic-slider__box-container">
                                        <div class="uic-slider__box">
                                            <?php juicer_feed('name=' . $juicer_feed_id . '&overlay=false&per=1&columns=1&page=' .  ($i * 3)); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="uic-slider__dots" data-glide-el="controls[nav]">
                    <?php for($i = 0; $i < $feed_items_shown; $i++) { ?>
                        <button type="button" class="uic-slider__dot" data-glide-dir="=<?= $i ?>" title="Skip to Slide <?= $i + 1 ?>"><span class="sr-only">Skip to Slide <?= $i + 1 ?></span></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>