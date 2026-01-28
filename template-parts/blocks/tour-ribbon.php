<?php

$link_0 = get_field('link_0');
$link_1 = get_field('link_1');

?>

<div class="uic-tour-ribbon uic-section">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <?php 
            if (!empty($link_0)) {
                echo '<a href="' . esc_url($link_0['url']) . '" class="uic-tour-ribbon__link"';
                echo ' title="' . esc_attr($link_0['title']) . '"';
                if (!empty($link_0['target'])) {
                    echo ' target="' . esc_attr($link_0['target']) . '"';
                }               
                echo '>' . $link_0['title'] . '<span class="uic-tour-ribbon__link-arrow"></span></a>';
            }
            if (!empty($link_1)) {
                echo '<a href="' . esc_url($link_1['url']) . '" class="uic-tour-ribbon__link"';
                echo ' title="' . esc_attr($link_1['title']) . '"';
                if (!empty($link_1['target'])) {
                    echo ' target="' . esc_attr($link_1['target']) . '"';
                }               
                echo '>' . $link_1['title'] . '<span class="uic-tour-ribbon__link-arrow"></span></a>';
            }
            ?>
        </div>
    </div>
</div>

