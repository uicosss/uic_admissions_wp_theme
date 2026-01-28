<?php

$cards = get_field('cards');
$header_text = get_field('header_text');
$bottom_link = get_field('bottom_link');
$person_value = null;

if (!empty($_GET) && !empty($_GET["person"])) {
	$person_value = $_GET["person"];
}

$layout_class = 'uic-icon-cards__grid--4x2';
if (count($cards) <= 4) {
    $layout_class = 'uic-icon-cards__grid--4x1';
}

?>
<section class="uic-section uic-icon-cards">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <div class="uic-icon-cards__content">
                <h3 class="uic-h3"><?= $header_text ?></h3>
                <div class="uic-icon-cards__grid <?= $layout_class ?>">
                    <?php
                    foreach($cards as $card) {
                        if ($card['link']['url'] !== '#') {

							if (!empty($person_value) && !empty($card['link']['url']) && strpos($card['link']['url'], 'applynow.uic.edu') !== false && $card['link']['title'] === 'Schedule an Appointment') {

								if (strpos($card['link']['url'], '?') === false) {
									$card['link']['url'] = $card['link']['url'] . '?person=' . $person_value;
								} else {
									$card['link']['url'] = $card['link']['url'] . '&person=' . $person_value;
								}
							}

                            echo _tag_open('a', [
                                'href' => $card['link']['url'],
                                'class' => 'uic-icon-cards__card',
                                'title' => empty($card['link']['title']) ? null : $card['link']['title'],
                                'target' => empty($card['link']['target']) ? null : $card['link']['target']
                            ]);
                        } else {
                            echo _tag_open('div', [
                                'class' => 'uic-icon-cards__card'
                            ]);
                        }
                        echo _tag('img', [
                            'role' => 'img',
                            'src' => $card['icon']['url'],
                            'class' => 'uic-icon-cards__card__icon',
                            'alt' => (empty(!$card['icon']['alt'])
                                ? $card['icon']['alt']
                                : (!empty($card['icon']['title'])
                                    ? $card['icon']['title']
                                    : null
                                )
                            )
                        ]);
                        echo _tag('span', [
                            'class' => 'uic-icon-cards__card__text'
                        ], wrap_with_arrow($card['link']['title'], '<span class="uic-icon-cards__card__arrow"></span>'));

                        if ($card['link']['url'] !== '#') {
                            echo _tag_close('a');
                        } else {
                            echo _tag_close('div');
                        }
                    }
                    ?>
                </div>
                <?php
                if (!empty($bottom_link)) {
                    echo '<a href="' . esc_url($bottom_link['url']) . '" class="uic-icon-cards__under-text"';
                    echo ' title="' . esc_attr($bottom_link['title']) . '"';
                    if (!empty($bottom_link['target'])) {
                        echo ' target="' . esc_attr($bottom_link['target']) . '"';
                    }
                    echo '>'. $bottom_link['title'] . '</a>';

                }
                ?>
            </div>
        </div>
    </div>
</section>
