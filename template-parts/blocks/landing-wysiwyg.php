<?php

$editor = get_field('editor');
$background_color = get_field('background_color');

$cta_link = get_field('cta_link');
$code_footer = get_field('code_footer');

if ( $cta_link ):
    $cta_link_url = $cta_link['url'];
    $cta_link_title = $cta_link['title'];
    $cta_link_target = $cta_link['target'];
endif;

?>

<section class="uic-section uic-aid uic-aid__<?php echo esc_attr($background_color) ?>">
    <?php if(!empty($editor)) { ?>
        <div class="uic-aid__wysiwyg uic-aid__wysiwyg__container">
            <?= $editor ?>  
        </div>
    <?php } ?>
    <div class="uic-aid__link-container">
        <?php if (!empty($cta_link)) { ?>
            <a class="uic-aid__cta-link" href="<?= $cta_link_url ?>" target="<?= $cta_link_target ?>"><?= strtoupper($cta_link_title) ?><span class="uic-aid__cta-link__arrow"></span></a>
        <?php } ?>

        <?php if (!empty($code_footer)) { ?>
            <p class="uic-aid__code"><?= $code_footer ?></p>
        <?php } ?>
    </div>

</section>