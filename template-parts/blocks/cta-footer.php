<?php
$links = get_field('cta_links');

$padding_choice = get_field('padding_choice');
$padding = $padding_choice === 'top-padding' ? 'uic-cta-footer__extra-padding' : '';

$background_color = get_field('background_color');
$text_color = get_field('text_color');

?>

<section class="uic-section uic-cta-footer__<?php echo esc_attr($background_color) ?>">
    <div class="uic-cta-footer__container <?php echo esc_attr($padding) ?>">
        <?php
            foreach($links as $link) {
                if ( !empty($link) ):
                    $link_url = $link['link']['url'];
                    $link_title = $link['link']['title'];
                    $link_target = $link['link']['target'];
                    echo '<a href="' . $link_url . 
                            '" target="' . $link_target . '" 
                            class="uic-cta-footer__link uic-cta-footer__link__' . $text_color . '">'
                                . $link_title . 
                        '</a>';
                endif;
            }
        ?>
    </div>
</section>