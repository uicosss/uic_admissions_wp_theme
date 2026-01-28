<?php

$header_text = get_field('header_text');
$resources = get_field('resources');
$bottom_link = get_field('bottom_link');

if ($bottom_link):
    $bottom_link_url = $bottom_link['url'];
    $bottom_link_title = $bottom_link['title'];
    $bottom_link_target = $bottom_link['target'];
endif;
?>

<section class="uic-section uic-resource-links">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <div class="uic-resource-links__container">
                <?php
                if (!empty($header_text)) { ?>
                    <h3 class="uic-resource-links__header"><?= $header_text ?></h3>
                <?php } ?>
                <div class="uic-resource-links__content">
                    <ul>
                        <?php
                        $i = 0;
                        foreach ($resources as $resource) {
                            $i += 1;
                            if (!empty($resource) && $i !== count($resources)) {
                                echo '<li><a class="uic-resource-links__link" href="' . esc_url($resource['link']['url']) . '" target="' . esc_attr($resource['link']['target']) . '">' . $resource['link']['title'] . '<span class="uic-resource-links__link__arrow"></span></a></li>';
                            } else {
                                echo '<li><a class="uic-resource-links__link uic-resource-links__link__final" href="' . esc_url($resource['link']['url']) . '" target="' . esc_attr($resource['link']['target']) . '">' . $resource['link']['title'] . '<span class="uic-resource-links__link__arrow"></span></a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <?php
                if (!empty($bottom_link)) { ?>
                    <a class="uic-resource-links__bottom-link" href="<?= $bottom_link_url ?>" target="<?= $bottom_link_target ?>"><?= strtoupper($bottom_link_title) ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</section>