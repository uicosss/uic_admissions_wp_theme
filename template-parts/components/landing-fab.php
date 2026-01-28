<?php
$hide_buttons = get_field('hide_buttons');
$fab_links = get_field('fab_links');
if (is_page_template('page-landing.php')) {
    if (! empty($hide_buttons) && $hide_buttons === true) {
        $visibility = 'hidden';
    } else {
        $visibility = '';
    }
?>
    <nav class="uic-fab <?= $visibility ?>" aria-label="Quick Action Menu" <?= $visibility === 'hidden' ? 'aria-hidden="true"' : '' ?>>
        <ul class="uic-fab__container">
            <?php

            $i = 0;
            if (!empty($fab_links)) {
                foreach ($fab_links as $link) {
                    $url = $link['link']['url'];
                    $title = $link['link']['title'];
                    $target = $link['link']['target'];

                    if (str_starts_with($url, '#')) {
                        $url = '/' . $url;
                    }
                    echo '<li>';
                    echo '<a href="' . esc_url($url) . '"';

                    if (!empty($title)) echo ' title="' . esc_attr($title) . '"';
                    else echo ' title="' . esc_attr($title) . '"';

                    if (!empty($target)) echo ' target="' . esc_attr($target) . '"';
                    else if (!str_starts_with($url, '/') && !str_starts_with($url, '#')) echo ' target="_blank"';

                    echo ' class="uic-fab__dot';
                    if ($i === 0) echo ' uic-fab--gray';
                    else if ($i === 1) echo ' uic-fab--blue';
                    else echo ' uic-fab--red';

                    if ($url === '#chat') echo ' uic-chat__trigger';

                    echo '"><span class="uic-fab__text">';
                    echo $title;
                    echo '</span></a></li>';
                    $i++;
                }
            } ?>
        </ul>
    </nav>
<?php } ?>