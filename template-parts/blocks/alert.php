<?php
$message_parts = get_field('message_parts');
$link = get_field('link');
$person_value = null;

if (!empty($_GET) && !empty($_GET["person"])) {
	$person_value = $_GET["person"];
}

if (!empty($person_value) && !empty($link['url']) && strpos($link['url'], 'applynow.uic.edu') !== false) {

	if (strpos($link['url'], '?') === false) {
		$link['url'] = $link['url'] . '?person=' . $person_value;
	} else {
		$link['url'] = $link['url'] . '&person=' . $person_value;
	}

}

?>

<div class="uic-alert uic-section">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <h2 class="uic-alert__basic">
                <?php
                foreach($message_parts as $part) {
                    if ($part['part_style'] === 'bold') {
                        echo ' <span class="uic-alert__callout">' . $part['part_text'] . '</span> ';
                    } else {
                        echo ' '.$part['part_text'].' ';
                    }
                } ?>
            </h2>
            <?php
            if (!empty($link)) {
                echo '<a href="' . esc_url($link['url']) . '" class="uic-alert__cta" title="' . esc_attr($link['title']) . '"';
                if (!empty($link['target'])) {
                    echo ' target="' . esc_attr($link['target']) . '"';
                }
                echo '><span class="uic-h2">' . $link['title'] . '</span><div class="uic-alert__cta__arrow"></div></a>';
            } ?>
        </div>
    </div>
</div>
