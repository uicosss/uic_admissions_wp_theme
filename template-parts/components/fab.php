<?php
    $nav_menus = wp_get_nav_menus();
    if ( is_page_template( 'page-landing.php' ) ) {
        $fab_menu = array_filter($nav_menus, fn ($nav) => $nav->slug === 'fab-landing-menu');
    } else {
        // this is the primary menu, so it works best as the fallback
        $fab_menu = array_filter($nav_menus, fn ($nav) => $nav->slug === 'fab-menu');
    }
    if ($fab_menu) {
        $nav_items = is_page_template( 'page-landing.php' ) ? wp_get_nav_menu_items('fab-landing-menu') : wp_get_nav_menu_items('fab-menu');        
        if ( ! empty($hide_fab) && $hide_fab === true) {
            $visibility = 'hidden';
        } else {
            $visibility = '';
        }
        ?>
        <nav class="uic-fab <?= $visibility ?>" aria-label="Quick Action Menu" <?= $visibility === 'hidden' ? 'aria-hidden="true"' : '' ?>>
            <ul class="uic-fab__container">
                <?php

                $i = 0;
                foreach ($nav_items as $nav_item) {
                    $url = $nav_item->url;
                    if (str_starts_with($url, '#')) {
                        $url = '/' . $url;
                    }
                    echo '<li>';
                    echo '<a href="' . esc_url($url) . '"';

                    if (!empty($nav_item->attr_title)) echo ' title="' . esc_attr($nav_item->attr_title) . '"';
                    else echo ' title="' . esc_attr($nav_item->title) . '"';

                    if (!empty($nav_item->target)) echo ' target="' . esc_attr($nav_item->target) . '"';
                    else if (!str_starts_with($url, '/') && !str_starts_with($url, '#')) echo ' target="_blank"';

                    echo ' class="uic-fab__dot';
                    if ($i === 0) echo ' uic-fab--gray';
                    else if ($i === 1) echo ' uic-fab--blue';
                    else echo ' uic-fab--red';

                    if ($nav_item->url === '#chat') echo ' uic-chat__trigger';

                    echo '"><span class="uic-fab__text">';
                    echo $nav_item->title;
                    echo '</span></a></li>';
                    $i++;
                } ?>
            </ul>
        </nav>
    <?php
    } ?>