<?php
// Removed global eyebrow fields from here as they are now inside the repeater
$active_alerts = [];
$now = current_time('timestamp');

if (have_rows('alerts')) :
    while (have_rows('alerts')) : the_row();
        // New: Get eyebrow fields as sub-fields
        $eyebrow_prefix = get_sub_field('eyebrow_prefix');
        $eyebrow_text   = get_sub_field('eyebrow_text');
        
        $header         = get_sub_field('header');
        $description    = get_sub_field('description');
        $cta_link       = get_sub_field('cta_link');
        $start_datetime = get_sub_field('start_datetime', false);
        $end_datetime   = get_sub_field('end_datetime', false);

        $start_timestamp = !empty($start_datetime) ? strtotime($start_datetime) : null;
        $end_timestamp   = !empty($end_datetime) ? strtotime($end_datetime) : null;

        $show_alert = true;

        if ($start_timestamp !== null && $now < $start_timestamp) {
            $show_alert = false;
        }

        if ($end_timestamp !== null && $now > $end_timestamp) {
            $show_alert = false;
        }

        if ($show_alert) {
            $active_alerts[] = [
                'eyebrow_prefix' => $eyebrow_prefix, // Added to array
                'eyebrow_text'   => $eyebrow_text,   // Added to array
                'header'         => $header,
                'description'    => $description,
                'cta_link'       => $cta_link,
            ];
        }
    endwhile;
endif;

if (empty($active_alerts)) {
    return;
}

$is_carousel = count($active_alerts) > 1;
?>

<section class="alert-carousel <?php echo $is_carousel ? 'alert-carousel--carousel splide' : 'alert-carousel--single'; ?>">

    <?php if ($is_carousel) : ?>
        <div class="splide__arrows">
            <button class="splide__arrow splide__arrow--prev">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/arrow-prev.svg" alt="Previous">
            </button>
            <button class="splide__arrow splide__arrow--next">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/arrow-next.svg" alt="Next">
            </button>
        </div>

        <div class="splide__track">
            <ul class="splide__list">
                <?php foreach ($active_alerts as $alert) : ?>
                    <li class="splide__slide">
                        <div class="alert-carousel__item">
                        
                        <?php // Pulling eyebrow fields from the $alert array item ?>
                        <?php if (!empty($alert['eyebrow_prefix']) || !empty($alert['eyebrow_text'])) : ?>
                            <div class="alert-carousel__eyebrow">
                                <?php if (!empty($alert['eyebrow_prefix'])) : ?>
                                    <span class="alert-carousel__eyebrow-prefix">
                                        <?php echo esc_html($alert['eyebrow_prefix']); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($alert['eyebrow_text'])) : ?>
                                    <span class="alert-carousel__eyebrow-text">
                                        <?php echo esc_html($alert['eyebrow_text']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                            <div class="alert-carousel__content">
                                <div class="alert-carousel__text">
                                    <?php if (!empty($alert['header'])) : ?>
                                        <h3 class="alert-carousel__header">
                                            <?php echo esc_html($alert['header']); ?>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if (!empty($alert['description'])) : ?>
                                        <div class="alert-carousel__description">
                                            <?php echo wp_kses_post($alert['description']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php
                                $link = $alert['cta_link'];
                                if (!empty($link)) :
                                    $link_url    = !empty($link['url']) ? $link['url'] : '';
                                    $link_title  = !empty($link['title']) ? $link['title'] : '';
                                    $link_target = !empty($link['target']) ? $link['target'] : '';
                                ?>
                                    <div class="alert-carousel__link-wrap">
                                        <a href="<?php echo esc_url($link_url); ?>"
                                           class="alert-carousel__link uic-alert__cta"
                                           title="<?php echo esc_attr($link_title); ?>"
                                           <?php echo $link_target ? 'target="' . esc_attr($link_target) . '"' : ''; ?>>
                                            <span class="uic-h2"><?php echo esc_html($link_title); ?></span>
                                            <div class="uic-alert__cta__arrow"></div>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>                  
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else : ?>
        <?php
        $alert = $active_alerts[0];
        ?>
        <div class="alert-carousel__item">
            <?php // Pulling eyebrow fields from the single $alert item ?>
            <?php if (!empty($alert['eyebrow_prefix']) || !empty($alert['eyebrow_text'])) : ?>
                <div class="alert-carousel__eyebrow">
                    <?php if (!empty($alert['eyebrow_prefix'])) : ?>
                        <span class="alert-carousel__eyebrow-prefix">
                            <?php echo esc_html($alert['eyebrow_prefix']); ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($alert['eyebrow_text'])) : ?>
                        <span class="alert-carousel__eyebrow-text">
                            <?php echo esc_html($alert['eyebrow_text']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="alert-carousel__content">
                <?php if (!empty($alert['header'])) : ?>
                    <h3 class="alert-carousel__header">
                        <?php echo esc_html($alert['header']); ?>
                    </h3>
                <?php endif; ?>

                <?php if (!empty($alert['description'])) : ?>
                    <div class="alert-carousel__description">
                        <?php echo wp_kses_post($alert['description']); ?>
                    </div>
                <?php endif; ?>

                <?php
                $link = $alert['cta_link'];
                if (!empty($link)) :
                    $link_url    = !empty($link['url']) ? $link['url'] : '';
                    $link_title  = !empty($link['title']) ? $link['title'] : '';
                    $link_target = !empty($link['target']) ? $link['target'] : '';
                ?>
                    <a href="<?php echo esc_url($link_url); ?>"
                       class="alert-carousel__link uic-alert__cta"
                       title="<?php echo esc_attr($link_title); ?>"
                       <?php echo $link_target ? 'target="' . esc_attr($link_target) . '"' : ''; ?>>
                        <span class="uic-h2"><?php echo esc_html($link_title); ?></span>
                        <div class="uic-alert__cta__arrow"></div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>