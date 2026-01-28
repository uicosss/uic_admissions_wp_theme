<?php
// landing page links are not fetched here, primary seem better suited
$nav_locations = get_nav_menu_locations();
$nav_items = wp_get_nav_menu_items($nav_locations['primary']);

$footer_cfg = get_field('footer_config', 'option');

$footer_logo_link = $footer_cfg['footer_logo_link'];

$footer_addresses = $footer_cfg['footer_addresses'];
$address_0_title  = $footer_addresses['address_0_title'];
$address_0_content  = $footer_addresses['address_0_content'];
$address_1_title  = $footer_addresses['address_1_title'];
$address_1_content  = $footer_addresses['address_1_content'];
$address_2_title  = $footer_addresses['address_2_title'];
$address_2_content  = $footer_addresses['address_2_content'];

$footer_social = $footer_cfg['footer_social'];
$instagram_link = $footer_social['social_instagram'];
$facebook_link =  $footer_social['social_facebook'];
$twitter_link =  $footer_social['social_twitter'];

require(__DIR__ . '/template-parts/components/back-to-top.php');

?>

<footer class="uic-footer uic-section">
	<div class="uic-section__container">
		<div class="uic-section__inner uic-section__inner--no-padding">
			<div class="uic-footer__logo">
				<?php
				echo '<a href="' . esc_url($footer_logo_link['url']) . '"';
				if (!empty($footer_logo_link['title'])) echo ' title="' . esc_attr($footer_logo_link['title']) . '"';
				if (!empty($footer_logo_link['target'])) echo ' target="' . esc_attr($footer_logo_link['target']) . '"';
				echo '>';
				echo '<img src="' . THEME_ASSET_BASE . '/images/logo-full.svg"';
				if (!empty($footer_logo_link['title'])) echo ' alt="' . esc_attr($footer_logo_link['title']) . '"';
				else echo ' alt="UIC Logo"';
				echo ' /></a>';
				?>
			</div>
			<div class="uic-footer__divider"></div>
			<div class="uic-footer__addresses">
				<div class="uic-footer__address">
					<span class="uic-footer__address__title"><?= $address_0_title ?></span>
					<?= $address_0_content ?>
				</div>
				<div class="uic-footer__address">
					<span class="uic-footer__address__title"><?= $address_1_title ?></span>
					<?= $address_1_content ?>
				</div>
				<div class="uic-footer__address">
					<span class="uic-footer__address__title"><?= $address_2_title ?></span>
					<?= $address_2_content ?>
				</div>
			</div>
			<div class="uic-footer__divider"></div>
			<nav class="uic-footer__nav" aria-label="Primary">
				<ul class="uic-footer__nav__items">
					<?php
					foreach ($nav_items as $nav_item) {
						$url = $nav_item->url;
						if (str_starts_with($url, '#')) {
							$url = '/' . $url;
						}
						echo '<li class="uic-footer__nav__item"><a href="' . esc_url($url) . '"';

						if (!empty($nav_item->attr_title)) echo ' title="' . esc_attr($nav_item->attr_title) . '"';
						else echo ' title="' . esc_attr($nav_item->title) . '"';

						if (!empty($nav_item->target)) echo ' target="' . esc_attr($nav_item->target) . '"';
						else if (!str_starts_with($url, '/') && !str_starts_with($url, '#')) echo ' target="_blank"';

						echo '><span class="uic-footer__nav__item-text">';
						echo $nav_item->title;
						echo '</span></a></li>';
					} ?>
				</ul>
				<div class="uic-footer__social">
					<?php
					if (!empty($instagram_link))  {
						echo '<a href="' . esc_url($instagram_link['url']) . '" class="uic-footer__social__link"';
						if (!empty($instagram_link['title'])) echo ' title="' . esc_attr($instagram_link['title']) . '"';
						if (!empty($instagram_link['target'])) echo ' target="' . esc_attr($instagram_link['target']) . '"';
						echo '><img src="' . THEME_ASSET_BASE . '/images/social-icons/instagram.svg" alt="Instagram Logo" /></a>';
					}
					if (!empty($facebook_link))  {
						echo '<a href="' . esc_url($facebook_link['url']) . '" class="uic-footer__social__link"';
						if (!empty($facebook_link['title'])) echo ' title="' . esc_attr($facebook_link['title']) . '"';
						if (!empty($facebook_link['target'])) echo ' target="' . esc_attr($facebook_link['target']) . '"';
						echo '><img src="' . THEME_ASSET_BASE . '/images/social-icons/facebook.svg" alt="Facebook Logo" /></a>';
					}
					if (!empty($twitter_link))  {
						echo '<a href="' . esc_url($twitter_link['url']) . '" class="uic-footer__social__link"';
						if (!empty($twitter_link['title'])) echo ' title="' . esc_attr($twitter_link['title']) . '"';
						if (!empty($twitter_link['target'])) echo ' target="' . esc_attr($twitter_link['target']) . '"';
						echo '><img src="' . THEME_ASSET_BASE . '/images/social-icons/twitter.svg" alt="Twitter Logo" /></a>';
					} ?>
				</div>
			</nav>   
		</div>
	</div>
</footer>

<?php 
require(__DIR__ . '/template-parts/blocks/feedback.php');
wp_footer();
?>
</body>
</html>