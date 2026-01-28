<?php

/**
 * Header file for the Thirdwave starter theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */


$header_cfg = get_field('header_config', 'option');
$header_logo_link = $header_cfg['header_logo_link'];

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
	<?php wp_head(); ?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#d50032">
	<meta name="msapplication-TileColor" content="#001e62">
	<meta name="theme-color" content="#001e62">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<script async="async" src="https://applynow.uic.edu/ping">/**/</script>
</head>

<body <?php body_class('uic'); ?>>
	<?php require_once(__DIR__ . '/template-parts/blocks/skip-to-content.php'); ?>
	<?php wp_body_open(); ?>

    <header class="uic-section uic-header" aria-label="Site Header">
        <div class="uic-section__container">
            <div class="uic-section__inner uic-section__inner--never-padding">
				<?php
				echo '<a href="' . esc_url($header_logo_link['url']) . '"';
				if (!empty($header_logo_link['title'])) echo ' title="' . esc_attr($header_logo_link['title']) . '"';
				if (!empty($header_logo_link['target'])) echo ' target="' . esc_attr($header_logo_link['target']) . '"';
				echo '>';
				?>
					<svg
						xmlns:xlink="http://www.w3.org/1999/xlink"
						viewBox="0 0 45 45"
						class="uic-header__logo"
						aria-label="<?= esc_attr(!empty($header_logo_link['title']) ? $header_logo_link['title'] : 'UIC Logo') ?>"
						alt="<?= esc_attr(!empty($header_logo_link['title']) ? $header_logo_link['title'] : 'UIC Logo') ?>"
					>
						<title><?= esc_attr(!empty($header_logo_link['title']) ? $header_logo_link['title'] : 'UIC Logo') ?></title>
						<use xlink:href="<?= THEME_ASSET_BASE ?>/images/logo-only.svg#uic-header__logo"></use>
					</svg>
				</a>
                <?php require_once(__DIR__ . '/template-parts/components/navbar.php'); ?>
            </div>
        </div>
    </header>
	

	<?php require_once(__DIR__ . '/template-parts/components/fab.php'); ?>
