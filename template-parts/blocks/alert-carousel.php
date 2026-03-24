<?php
$eyebrow = get_field('eyebrow');
$active_alerts = [];
$now = current_time('timestamp');

if (have_rows('alerts')) :
	while (have_rows('alerts')) : the_row();
		$header = get_sub_field('header');
		$description = get_sub_field('description');
		$cta_link = get_sub_field('cta_link');
		$start_datetime = get_sub_field('start_datetime', false);
		$end_datetime = get_sub_field('end_datetime', false);

		$start_timestamp = !empty($start_datetime) ? strtotime($start_datetime) : null;
		$end_timestamp = !empty($end_datetime) ? strtotime($end_datetime) : null;

		$show_alert = true;

		if ($start_timestamp !== null && $now < $start_timestamp) {
			$show_alert = false;
		}

		if ($end_timestamp !== null && $now > $end_timestamp) {
			$show_alert = false;
		}

		if ($show_alert) {
			$active_alerts[] = [
				'header'      => $header,
				'description' => $description,
				'cta_link'    => $cta_link,
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
		<div class="splide__track">
			<ul class="splide__list">
				<?php foreach ($active_alerts as $alert) :
					$link = $alert['cta_link'];
					$link_url = !empty($link['url']) ? $link['url'] : '';
					$link_title = !empty($link['title']) ? $link['title'] : 'Learn More';
					$link_target = !empty($link['target']) ? $link['target'] : '_self';
				?>
					<li class="splide__slide">
                        <div class="alert-carousel__item">
                                <?php if (!empty($eyebrow)) : ?>
                                    <div class="alert-carousel__eyebrow">
                                        <?php echo esc_html($eyebrow); ?>
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
                                    $link_url = !empty($link['url']) ? $link['url'] : '';
                                    $link_title = !empty($link['title']) ? $link['title'] : '';
                                    $link_target = !empty($link['target']) ? $link['target'] : '';
                                ?>
                                    <div class="alert-carousel__link-wrap">
                                        <a href="<?php echo esc_url($link_url); ?>"
                                        class="alert-carousel__link uic-alert__cta"
                                        title="<?php echo esc_attr($link_title); ?>"
                                        <?php if ($link_target) : ?>
                                            target="<?php echo esc_attr($link_target); ?>"
                                        <?php endif; ?>>
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
		$link = $alert['cta_link'];
		$link_url = !empty($link['url']) ? $link['url'] : '';
		$link_title = !empty($link['title']) ? $link['title'] : 'Learn More';
		$link_target = !empty($link['target']) ? $link['target'] : '_self';
		?>
		<div class="alert-carousel__item">
				<?php if (!empty($eyebrow)) : ?>
					<div class="alert-carousel__eyebrow">
						<?php echo esc_html($eyebrow); ?>
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
                        $link_url = !empty($link['url']) ? $link['url'] : '';
                        $link_title = !empty($link['title']) ? $link['title'] : '';
                        $link_target = !empty($link['target']) ? $link['target'] : '';
                    ?>

                    <a href="<?php echo esc_url($link_url); ?>"
                    class="alert-carousel__link uic-alert__cta"
                    title="<?php echo esc_attr($link_title); ?>"
                    <?php if ($link_target) : ?>
                        target="<?php echo esc_attr($link_target); ?>"
                    <?php endif; ?>>

                        <span class="uic-h2">
                            <?php echo esc_html($link_title); ?>
                        </span>

                        <div class="uic-alert__cta__arrow"></div>
                    </a>
                <?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

</section>