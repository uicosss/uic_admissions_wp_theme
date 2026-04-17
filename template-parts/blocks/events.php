<?php

require_once ( __DIR__ . '/../components/calendar.php');

$events_config = get_field('events_config', 'option');

if (!empty($events_config) && !empty($events_config['events_mode'])) {
    $events_mode = $events_config['events_mode'];
}
else {
    $events_mode = 'default';
}

$section_label = get_field('section_label');
$header_text = get_field('header_text');

$in_person_prompt = get_field('in_person_prompt');
$in_person_link = get_field('in_person_link');
$virtual_prompt = get_field('virtual_prompt');
$virtual_link = get_field('virtual_link');
$academic_prompt = get_field('academic_prompt');
$academic_link = get_field('academic_link');

$upcoming_prompt = get_field('upcoming_prompt');
$upcoming_link = get_field('upcoming_link');

$in_person_no_results_text = get_field('in-person_no_results_text');
$virtual_no_results_text = get_field('virtual_no_results_text');
$academic_no_results_text = get_field('academic_no_results_text');

/*
Example no results markup for each panel if there are no events to show:
<div class="uic-events__event__location">
    In-Person
</div>
<div class="uic-events__event__kind">
    Information Session
</div>
<div class="uic-events__event__details">
    <div class="uic-events__event__name">
        No upcoming academic events
    </div>
</div>
*/

$events = get_upcoming_events();
$events_filtered = [];

$panel_limit = 10;
$next_virtual = [];  // column 2 in arbitrary mode
$next_in_person = [];  // column 1 in arbitrary mode
$next_academic = [];  // column 3 in arbitrary mode
$person_value = null;

$next_column_1 = [];
$next_column_2 = [];
$next_column_3 = [];

if (!empty($_GET) && !empty($_GET["person"])) {
	$person_value = $_GET["person"];
}

$linksArray = [
    $in_person_link,
    $virtual_link,
    $academic_link,
    $upcoming_link
];

foreach ($linksArray as $key=>$linkArray) {
	if (!empty($person_value) && !empty($linkArray['url']) && strpos($linkArray['url'], 'applynow.uic.edu') !== false) {
		if (strpos($linkArray['url'], '?') === false) {
			$linksArray[$key]['url'] = $linksArray[$key]['url'] . '?person=' . $person_value;
		} else {
			$linksArray[$key]['url'] = $linksArray[$key]['url'] . '&person=' . $person_value;
		}
	}
}

if ($events !== null) {
    foreach ($events as $event) {

		if (time() > $event['timestamp_start']) {
			continue;
		}

		if (!empty($person_value) && !empty($event['link']) && strpos($event['link'], 'applynow.uic.edu') !== false) {

			if (strpos($event['link'], '?') === false) {
				$event['link'] = $event['link'] . '?person=' . $person_value;
			} else {
				$event['link'] = $event['link'] . '&person=' . $person_value;
			}

		}

		// If we are here, then the event is a valid $event_filtered item
		array_push($events_filtered, $event);
        if ($events_mode === 'arbitrary') {
            if (count($next_column_1) == $panel_limit && count($next_column_2) == $panel_limit && count($next_column_3) == $panel_limit) {
                continue;
            }
            // in arbitrary mode, event placement is determined by whether the event category is in the respective column category lists (which can have overlapping categories)
            if (count($next_column_1) < $panel_limit && $event['is_col_1']) {
                $next_column_1[] = $event;
            }
            if (count($next_column_2) < $panel_limit && $event['is_col_2']) {
                $next_column_2[] = $event;
            }
            if (count($next_column_3) < $panel_limit && $event['is_col_3']) {
                $next_column_3[] = $event;
            }
        }
        else {
            if (count($next_virtual) == $panel_limit && count($next_in_person) == $panel_limit && count($next_academic) == $panel_limit) {
                continue;
            }
            if (count($next_virtual) < $panel_limit && $event['is_virtual'] === true) {
                $next_virtual[] = $event;
            } elseif (count($next_in_person) < $panel_limit && $event['is_inperson_enrollment_management'] === true && $event['is_virtual'] === false) {
                $next_in_person[] = $event;
            } elseif (count($next_academic) < $panel_limit && ($event['is_inperson_academic'] === true || $event['is_virtual_academic'] === true)) {
                $next_academic[] = $event;
            }
        }
	}
}

if ($events_mode === 'arbitrary') {
    $next_in_person = $next_column_1;
    $next_virtual = $next_column_2;
    $next_academic = $next_column_3;
}

?>
<section class="uic-section uic-events">
    <div class="uic-section__container">
        <div class="uic-section__inner">
            <?php if (!empty($section_label)) { ?>
                <div class="uic-section__label">
                    <h2 class="uic-h2" id="<?= sanitize_title($section_label) ?>"><?= $section_label ?></h2>
                </div>
            <?php } ?>
            <?php if (!empty($header_text)) { ?>
                <h3 class="uic-h3"><?= $header_text ?></h3>
            <?php } ?>
            <div class="uic-events__grid">
                <h4 class="uic-events__grid-item uic-events__grid-item--prompt uic-events__grid-item--prompt-0">
                    <?php if (!empty($in_person_prompt)) { echo $in_person_prompt; } ?>
                </h4>
                <h4 class="uic-events__grid-item uic-events__grid-item--prompt uic-events__grid-item--prompt-1">
                    <?php if (!empty($virtual_prompt)) { echo $virtual_prompt; } ?>
                </h4>
                <h4 class="uic-events__grid-item uic-events__grid-item--prompt uic-events__grid-item--prompt-2">
                    <?php if (!empty($academic_prompt)) { echo $academic_prompt; } ?>
                </h4>
                <h4 class="uic-events__grid-item uic-events__grid-item--prompt uic-events__grid-item--prompt-3">
                    <?php if (!empty($upcoming_prompt)) { echo $upcoming_prompt; } ?>
                </h4>
                <div class="uic-events__grid-item uic-events__grid-item--box-0">
                    <div class="uic-events__box-container">
                        <div class="uic-events__box uic-events__event uic-events__slider" data-max-page-size="1">
                            <?php if (empty($next_in_person)) { ?>
                                <?php echo $in_person_no_results_text; ?>
                            <?php } else { ?>
                                <div class="uic-events__panel__container">
                                    <div class="uic-events__panel__controls">
                                        <button type="button" class="uic-events__panel__prev arrow_hidden" data-calendar-arrow="prev" title="View Prev Event"></button>
                                        <button type="button" class="uic-events__panel__next" data-calendar-arrow="next" title="View Next Event"></button>
                                    </div>
                                    <div class="uic-events__panel__track" data-glide-el="track">
                                        <div class="uic-events__panel__items">
                                            <?php foreach ($next_in_person as $i => $event_in_person) { ?>
                                                <div class="uic-events__panel__item">
                                                    <div class="uic-events__event__location">
                                                        In-Person
                                                    </div>
                                                    <div class="uic-events__event__kind">
                                                        <?php if (!empty($event_in_person['event_category'])) { ?>
                                                            <?= $event_in_person['event_category']; ?>
                                                        <?php } else { ?>
                                                            Information Session<br />
                                                            And Campus Tour
                                                        <?php } ?>
                                                    </div>
                                                    <div class="uic-events__event__details">
                                                        <div class="uic-events__event__name">
                                                            <?= $event_in_person['title'] ?>
                                                        </div>
                                                        <div class="uic-events__event__datetime">
                                                            <?= $event_in_person['date_start'] ?><br />
                                                            <?= $event_in_person['time_start'] ?>
                                                            -
                                                            <?= $event_in_person['time_end'] ?>
                                                        </div>
                                                    </div>
                                                    <div class="uic-events__event__cta">
                                                        <a
                                                            href="<?= esc_url($event_in_person['link']) ?>"
                                                            title="<?= esc_attr('Register for ' . $event_in_person['title']) ?>"
                                                            class="uic-events__event__register"
                                                            target="_blank"
                                                        >
                                                            <span class="uic-events__event__register__text">Register</span>
                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="uic-events__event__register__arrow" viewBox="0 0 77.87 75.75">
                                                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/events-register-arrow.svg#uic-events__event__register__arrow" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--box-1">
                    <div class="uic-events__box-container">
                        <div class="uic-events__box uic-events__event uic-events__slider" data-max-page-size="1">
                            <?php if (empty($next_virtual)) { ?>
                                <?php echo $virtual_no_results_text; ?>
                            <?php } else { ?>
                                <div class="uic-events__panel__container">
                                    <div class="uic-events__panel__controls">
                                        <button type="button" class="uic-events__panel__prev arrow_hidden" data-calendar-arrow="prev" title="View Prev Event"></button>
                                        <button type="button" class="uic-events__panel__next" data-calendar-arrow="next" title="View Next Event"></button>
                                    </div>
                                    <div class="uic-events__panel__track" data-glide-el="track">
                                        <div class="uic-events__panel__items">
                                            <?php foreach ($next_virtual as $i => $event_virtual) { ?>
                                                <div class="uic-events__panel__item">
                                                    <div class="uic-events__event__location">
                                                        Virtual
                                                    </div>
                                                    <div class="uic-events__event__kind">
                                                        Information Session
                                                    </div>
                                                    <div class="uic-events__event__details">
                                                        <div class="uic-events__event__name">
                                                            <?= $event_virtual['title'] ?>
                                                        </div>
                                                        <div class="uic-events__event__datetime">
                                                            <?= $event_virtual['date_start'] ?><br />
                                                            <?= $event_virtual['time_start'] ?>
                                                            -
                                                            <?= $event_virtual['time_end'] ?>
                                                        </div>
                                                    </div>
                                                    <div class="uic-events__event__cta">
                                                        <a
                                                            href="<?= esc_url($event_virtual['link']) ?>"
                                                            title="<?= esc_attr('Register for ' . $event_virtual['title']) ?>"
                                                            class="uic-events__event__register"
                                                            target="_blank"
                                                        >
                                                            <span class="uic-events__event__register__text">Register</span>
                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="uic-events__event__register__arrow" viewBox="0 0 77.87 75.75">
                                                                <use xlink:href="<?= THEME_ASSET_BASE ?>/images/events-register-arrow.svg#uic-events__event__register__arrow" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--box-2">
                    <div class="uic-events__box-container">
                        <div class="uic-events__box uic-events__event uic-events__slider" data-max-page-size="1">
                            <?php if (empty($next_academic)) { ?>
                                <?php echo $academic_no_results_text; ?>
                            <?php } else { ?>
                                <div class="uic-events__panel__container">
                                    <div class="uic-events__panel__controls">
                                        <button type="button" class="uic-events__panel__prev arrow_hidden" data-calendar-arrow="prev" title="View Prev Event"></button>
                                        <button type="button" class="uic-events__panel__next" data-calendar-arrow="next" title="View Next Event"></button>
                                    </div>
                                    <div class="uic-events__panel__track" data-glide-el="track">
                                        <div class="uic-events__panel__items">
                                            <?php foreach ($next_academic as $i => $event_academic) { ?>
                                                <div class="uic-events__panel__item">
                                                    <div class="uic-events__event__location">
                                                        <?php if ($event_academic['is_virtual']) { ?>
                                                            Virtual
                                                        <?php } else { ?>
                                                            In-Person
                                                        <?php } ?>
                                                    </div>
                                                    <div class="uic-events__event__kind">
                                                        <?php if (!empty($event_academic['event_category'])) { ?>
                                                            <?= $event_academic['event_category']; ?>
                                                        <?php } else { ?>
                                                            Information Session
                                                        <?php } ?>
                                                    </div>
                                                    <div class="uic-events__event__details">
                                                        <div class="uic-events__event__name">
                                                            <?= $event_academic['title'] ?>
                                                        </div>
                                                        <div class="uic-events__event__datetime">
                                                            <?= $event_academic['date_start'] ?><br />
                                                            <?= $event_academic['time_start'] ?>
                                                            -
                                                            <?= $event_academic['time_end'] ?>
                                                        </div>
                                                    </div>
                                                    <div class="uic-events__event__cta">
                                                        <a
                                                            href="<?= esc_url($event_academic['link']) ?>"
                                                            title="<?= esc_attr('Register for ' . $event_academic['title']) ?>"
                                                            class="uic-events__event__register"
                                                            target="_blank"
                                                        >
                                                            <span class="uic-events__event__register__text">Register</span>
                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="uic-events__event__register__arrow" viewBox="0 0 77.87 75.75">
                                                                <use xlink:href="<?= THEME_ASSET_BASE ?>//images/events-register-arrow.svg#uic-events__event__register__arrow" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--box-3">
                    <div class="uic-events__box-container">
                        <div class="uic-events__box uic-calendar__container">
                            <div class="uic-calendar">
                                <?= render_calendar($events_filtered) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--link uic-events__grid-item--link-0">
                    <?php if (!empty($in_person_link)) {
                        echo '<a href="' . esc_url($linksArray[0]['url']) . '"';
                        echo ' title="' . esc_attr($in_person_link['title']) . '"';
                        if (!empty($in_person_link['target'])) {
                            echo ' target="' . esc_attr($in_person_link['target']) . '"';
                        }
                        echo '>' . $in_person_link['title'] . '</a>';
                    } ?>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--link uic-events__grid-item--link-1">
                    <?php if (!empty($virtual_link)) {
                        echo '<a href="' . esc_url($linksArray[1]['url']) . '"';
                        echo ' title="' . esc_attr($virtual_link['title']) . '"';
                        if (!empty($virtual_link['target'])) {
                            echo ' target="' . esc_attr($virtual_link['target']) . '"';
                        }
                        echo '>' . $virtual_link['title'] . '</a>';
                    } ?>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--link uic-events__grid-item--link-2">
                     <?php if (!empty($academic_link)) {
						echo '<a href="' . esc_url($linksArray[2]['url']) . '"';
                        echo ' title="' . esc_attr($academic_link['title']) . '"';
                        if (!empty($academic_link['target'])) {
                            echo ' target="' . esc_attr($academic_link['target']) . '"';
                        }
                        echo '>' . $academic_link['title'] . '</a>';
                    } ?>
                </div>
                <div class="uic-events__grid-item uic-events__grid-item--link uic-events__grid-item--link-3">
                     <?php if (!empty($upcoming_link)) {
                        echo '<a href="' . esc_url($linksArray[3]['url']) . '"';
                        echo ' title="' . esc_attr($upcoming_link['title']) . '"';
                        if (!empty($upcoming_link['target'])) {
                            echo ' target="' . esc_attr($upcoming_link['target']) . '"';
                        }
                        echo '>' . $upcoming_link['title'] . '</a>';
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>
