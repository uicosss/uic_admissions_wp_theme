<?php

$nav_menus = wp_get_nav_menus();

if (is_page_template('page-landing.php')) {
    $landing_menu = array_filter($nav_menus, fn($nav) => $nav->slug === 'landing-menu');
    $nav_items = wp_get_nav_menu_items('landing-menu');
} else {
    // this is the primary menu, so it works best as the fallback
    $nav_locations = get_nav_menu_locations();
    $nav_items = wp_get_nav_menu_items($nav_locations['primary']);
}
?>
<nav class="uic-navbar" aria-label="Primary">
    <ul class="uic-navbar__items">
        <?php
        if (!empty($mini_navbar_link)) {
            if ($mini_navbar_link):
                $mini_navbar_link_url = $mini_navbar_link['url'];
                $mini_navbar_link_title = $mini_navbar_link['title'];
                $mini_navbar_link_target = $mini_navbar_link['target'];
            endif;

            if ($mini_navbar_link) {
                echo '<li class="uic-navbar__item"><a href="' . $mini_navbar_link_url . '"';
                echo ' title="' . $mini_navbar_link_title . '"';
                echo ' target="' . $mini_navbar_link_target .  '"';
                echo '><span class="uic-navbar__item__text">';
                echo $mini_navbar_link_title;
                echo '</span></a></li>';
            }
        } else {
            foreach ($nav_items as $nav_item) {
                $url = $nav_item->url;
                if (str_starts_with($url, '#')) {
                    $url = '/' . $url;
                }
                echo '<li class="uic-navbar__item"><a href="' . esc_url($url) . '"';

                if (!empty($nav_item->attr_title)) echo ' title="' . esc_attr($nav_item->attr_title) . '"';
                else echo ' title="' . esc_attr($nav_item->title) . '"';

                if (!empty($nav_item->target)) echo ' target="' . esc_attr($nav_item->target) . '"';
                else if (!str_starts_with($url, '/') && !str_starts_with($url, '#')) echo ' target="_blank"';

                echo '><span class="uic-navbar__item__text">';
                echo $nav_item->title;
                echo '</span></a></li>';
            }
        } ?>
    </ul>
    <div class="uic-navbar__toggle-container">
        <button
            type="button"
            class="uic-navbar__toggle"
            title="Toggle Main Menu"
            aria-label="Toggle Main Menu"
            aria-expanded="false"
            aria-controls="uic-navbar__items">
            <div class="uic-navbar__toggle__icon">
                <div class="uic-navbar__toggle__icon-line"></div>
                <div class="uic-navbar__toggle__icon-line"></div>
                <div class="uic-navbar__toggle__icon-line"></div>
            </div>
        </button>
    </div>
</nav>