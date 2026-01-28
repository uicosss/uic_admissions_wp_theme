<?php

$slides = get_field('slides');
$animation_style = get_field('animation_style');


$hero_style = [
    'fade' =>  ' uic-hero--no-mask uic-hero--no-zoom',
    'mask-only' => ' uic-hero--no-zoom',
    'zoom-only' => ' uic-hero--no-mask',
    'all'=> ''
];

$h1_mapping = [
    'discover' => 'Discover',
    'explore' => 'Explore',
    'connect' => 'Connect',
    'become' => 'Become'
];

$view_box_mapping = [
    'discover' => '0 0 848.9 146.4',
    'explore' => '0 0 736.9 146.4',
    'connect' => '0 0 789.8 146.4',
    'become' => '0 0 677 146.4'
];
?>
<section class="uic-hero<?= $hero_style[$animation_style] ?? '' ?>">
    <h1 class="uic-h1"><?= get_bloginfo('name') ?></h1>
    <div class="uic-hero__overlay">
        <div class="uic-section">
            <div class="uic-section__container">
                <div class="uic-section__inner">
                    <div class="uic-hero__controls">
                        <button type="button" class="uic-hero__controls__autoplay" title="Pause Slideshow" aria-label="Pause Slideshow">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" area-hidden="true" viewBox="4 4 40 40">
                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/hero/hero-play-icon.svg#uic-hero__controls__autoplay--play-00" />
                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/hero/hero-play-icon.svg#uic-hero__controls__autoplay--play-01" />
                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/hero/hero-pause-icon.svg#uic-hero__controls__autoplay--pause-00" />
                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/hero/hero-pause-icon.svg#uic-hero__controls__autoplay--pause-01" />
                            </svg>
                            <span class="sr-only">Pause Slideshow</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="uic-hero__background">
        <div class="uic-hero__slider" data-slide-transition-duration="2000">
            <div class="uic-hero__slider__track-container">
                <div class="uic-hero__slider__track" data-glide-el="track">
                    <div class="uic-hero__slider__items">
                        <?php
                        $i = 0;
                        foreach ($slides as $slide) {
                            echo '<div class="uic-hero__slider__item' . ( $i++ === 0 ? ' glide__slide--active' : '') . '" style="background-image: url(\''. esc_url($slide['slide_image']['url']) . '\') ">';
                            echo '<svg xmlns:xlink="http://www.w3.org/1999/xlink" alt="'.$h1_mapping[$slide['slide_text']].'"';
                            echo ' aria-label="'.$h1_mapping[$slide['slide_text']].'"';
                            echo ' class="uic-hero__text uic-hero__text--' . $slide['slide_text'] . '" viewBox="' . HERO_TEXT_VIEWBOX[$slide['slide_text']] . '">';
                                    echo '<title>' . $h1_mapping[$slide['slide_text']] . '</title>';
                                    echo '<use xlink:href="' . THEME_ASSET_BASE . '/images/hero/text/hero-text-'.  $slide['slide_text'] .'.svg#uic-hero__slider__text" />';
                                echo '</svg>';
                            echo '</div>';
                        }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

