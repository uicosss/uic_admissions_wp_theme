<?php

use GuzzleHttp\Client;

function wrap_with_arrow($text, $arrow_markup, $wrap_open = '<span>', $wrap_close = '</span>') {
    $words = preg_split('/(\s+)/', $text);
    if (count($words) === 0) return '';
    else if (count($words) === 1) return $wrap_open . $words[0] . ' ' . $arrow_markup . $wrap_close;
    else {
        $result = '';
        for($i = 0; $i < count($words) - 1; $i++) {
            $result .= $wrap_open . $words[$i] . ' ' . $wrap_close;
        }
        $result .= $wrap_open . $words[count($words) - 1] . ' ' . $arrow_markup . $wrap_close;
    }
    return $result;
}

function get_vimeo_meta($vimeo_id) {
    $lookup_successful = (false !== ($cached_meta = get_transient('VIMEO_VIDEO_META::'.$vimeo_id)));

    if ($lookup_successful && gettype($cached_meta) !== 'integer') {
        return $cached_meta;
    } else if ($lookup_successful && gettype($cached_meta) === 'integer' && $cached_meta >= VIMEO_META_MAX_FAILED_REQUESTS) {
        // NOOP; if we've failed too much, don't try again until TTL expires the key
        return null;
    } else {
        $vimeo_meta = null;
        // save old error reporting level
        $old_error_reporting_level = error_reporting();
        error_reporting(0);

        // make request to get vimeo meta data
        try {
            $response = json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $vimeo_id . '.json'));
            if (gettype($response) === 'array' && count($response) > 0) {
                $vimeo_meta = $response[0];
            }
        } catch(Exception $e) { }

        // restore old error reporting level
        error_reporting($old_error_reporting_level);


        if ($vimeo_meta) {
            // cache vimeo meta data
            set_transient('VIMEO_VIDEO_META::'.$vimeo_id, $vimeo_meta, VIMEO_META_TTL);
            return $vimeo_meta;
        } else {
            // inc failed request count
            set_transient(
                'VIMEO_VIDEO_META::'.$vimeo_id,
                gettype($cached_meta) === 'integer' ? $cached_meta + 1 : 1,
                VIMEO_META_FAILED_TTL
            );
            return null;
        }
    }
}

function date_compare($a, $b) {
	$date_a=date_parse($a->StartDate);
	$date_b=date_parse($b->StartDate);
    return $date_a > $date_b;
}

function get_upcoming_events() {
	$events_config = get_field('events_config', 'option');
    $events_mode = 'default';
    $ttl = 60 * 60 * 4; // default to 4 hrs
    $ttl_error = 60 * 30; // error ttl 30 min
	$is_admissions_list = "AES On Campus / FY Visit,AES On Campus / Open House,AES On Campus / TR Visit,AES On Campus / UG Visit,AES On Campus / UIC Preview,Non AES On Campus / Financial Aid (Recruit),Non AES On Campus / Housing (Recruit)";
	$is_academic_list = "Non AES On Campus / AHS (Recruit),Non AES On Campus / CADA (Recruit),Non AES On Campus / CADA (Recruit_Partner),Non AES On Campus / CBA (Recruit),Non AES On Campus / CBA (Recruit_Partner),Non AES On Campus / CUPPA (Recruit),Non AES On Campus / EDU (Recruit),Non AES On Campus / ENGR (Recruit),Non AES On Campus / LAS (Recruit),Non AES On Campus / LAS (Recruit_Partner),Non AES On Campus / NURS (Recruit),Non AES On Campus / PHARM (Recruit),Non AES On Campus / SPH (Recruit),Non AES On Campus / HC/GPPA (Recruit)";

    $is_col_1_list="";
    $is_col_2_list="";
    $is_col_3_list="";

    if (!empty($events_config) && !empty($events_config['events_ttl'])) {
        $ttl_raw = intval($events_config['events_ttl']);
        if ($ttl_raw >= 1 || $ttl_raw <= (60 * 60 * 24 * 7)) {
            // if value is < 1 sec or > 1 week, ignore
			$ttl = $ttl_raw;
        }
    }
    if (!empty($events_config) && !empty($events_config['events_mode'])) {
        $events_mode = $events_config['events_mode'];
    }
	if (!empty($events_config) && !empty($events_config['admission_events_category_list'])) {
        $is_admissions_list = $events_config['admission_events_category_list'];
    }
	if (!empty($events_config) && !empty($events_config['academic_events_category_list'])) {
        $is_academic_list = $events_config['academic_events_category_list'];
    }
    if (!empty($events_config) && !empty($events_config['col_1_events_category_list'])) {
        $is_col_1_list = $events_config['col_1_events_category_list'];
    }
    if (!empty($events_config) && !empty($events_config['col_2_events_category_list'])) {
        $is_col_2_list = $events_config['col_2_events_category_list'];
    }
    if (!empty($events_config) && !empty($events_config['col_3_events_category_list'])) {
        $is_col_3_list = $events_config['col_3_events_category_list'];
    }

    $cached_events = get_transient('UPCOMING_EVENTS');
    $lookup_successful = $cached_events !== false;

	if ($ttl === 1) {
        // Forcing a refresh
		$lookup_successful = false;
	}

    // $cached_events = wp_cache_get('UPCOMING_EVENTS', false, $lookup_successful);
    if ($lookup_successful && gettype($cached_events) === 'array') {
        return $cached_events;
    } else {
        $full_url = str_replace('{{KEY}}', EVENTS_API_KEY, EVENTS_API_ENDPOINT);
        $events = [];

        try {
            $client = new Client();
            $response = $client->get($full_url, [
                'auth' => [
                    EVENTS_API_USER,
                    EVENTS_API_PASS
                ]
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new Exception('Error pulling events data from endpoint');
            }

            $json = json_decode($response->getBody()->getContents());
			usort($json->row, "date_compare");

            foreach ($json->row as $raw_event) {
                if ($raw_event->InviteOnly === '1') {
                    continue;
                }

				$space = (!property_exists($raw_event, 'SPACE') || empty($raw_event->SPACE)) ? 0 : intval($raw_event->SPACE);
				if ($space <= 0) {
					continue;
				}

                $t0 = new DateTimeImmutable( $raw_event->StartDate . ' ' . $raw_event->StartTime . ' CST');
                $t1 = new DateTimeImmutable( $raw_event->EndDate . ' ' . $raw_event->EndTime . ' CST');

				$event = [
                    'id' => $raw_event->ID,
					'open_seats' => $space,
                    'date_start' => $t0->format('l, F j'),
                    'date_start_utc' => $t0->format('Y-m-d'),
                    'date_end' => $raw_event->EndDate,
                    'time_start' => $t0->format('g:i a'),
                    'time_end' => $t1->format('g:i a'),
                    'timestamp_start' => $t0->getTimestamp(),
                    'timestamp_end' => $t1->getTimestamp(),
                    'title' => $raw_event->Title,
                    'link' => $raw_event->fixURL,
                    'event_category' => !empty($raw_event->EventCategory) ? $raw_event->EventCategory : null,

                    // for DEFAULT MODE, any event in the admissions list is considered admissions, any event in the academic list is considered academic. For CUSTOM MODE, the column assignment is determined by whether the event category is in the respective column category lists (which can have overlapping categories)

					// In person events that Enrollment Management, sponsored
					'is_inperson_enrollment_management' => (array_search($raw_event->Category, explode(',', $is_admissions_list)) !== false && $raw_event->Online !== '1') ? true : false,
					// Any virtual event, regardless of sponsor
                    'is_virtual'  => ($raw_event->Online === '1') ? true : false,
                    'is_virtual_academic' => (array_search($raw_event->Category,explode(',', $is_academic_list)) !== false && $raw_event->Online === '1') ? true : false,
					// In person events that are campus, not Enrollment Management, sponsored
                    'is_inperson_academic' => (array_search($raw_event->Category, explode(',', $is_academic_list)) !== false && $raw_event->Online !== '1') ? true : false,

                    // for ARBITRARY MODE, column assignment is determined by whether the event category is in the respective column category lists (which can have overlapping categories)
                    'is_col_1' => (array_search($raw_event->Category, explode(',', $is_col_1_list)) !== false) ? true : false,
                    'is_col_2' => (array_search($raw_event->Category, explode(',', $is_col_2_list)) !== false) ? true : false,
                    'is_col_3' => (array_search($raw_event->Category, explode(',', $is_col_3_list)) !== false) ? true : false,
                ];

                $events[] = $event;
            }

            set_transient(
                'UPCOMING_EVENTS',
                $events,
                $ttl
            );
        } catch (Exception $e) {
            set_transient(
                'UPCOMING_EVENTS',
                [],
                $ttl_error
            );
        }

        return !empty($events) ? $events : null;
    }
}

/**
 * a list of all self-closing tags (eg: <br />)
 * @see https://developer.mozilla.org/en-US/docs/Glossary/Void_element#self-closing_tags
 */
define('SELF_CLOSING_TAGS', [
    'area',
    'base',
    'br',
    'col',
    'embed',
    'hr',
    'img',
    'input',
    'keygen',
    'link',
    'meta',
    'param',
    'source',
    'track',
    'wbr'
]);

/**
 * list of attributes that can contain (just) a URL
 * @see https://stackoverflow.com/a/2725168
 **/
define('URL_ATTRS', [
    'href',
    'src',
    'action',
    'method',
    'formaction',
    'cite',
    'poster',
    'data',
    'classid',
    'longdesc',
    'usemap',
    'archive',
    'codebase',
    'icon',
    'manifest',
    'xmlns:xlink',
    'xlink:href'
]);


function _tag_open($tag, $attrs = [], $suppress_close = false, $force_url_encode = []) {
    $result = '<' . $tag;
    foreach($attrs as $attr => $value) {
        if (is_null($value)) continue;
        else if (in_array($attr, URL_ATTRS) || in_array($attr, $force_url_encode)) {
            $result .= ' ' . $attr . '="' . esc_url($value) . '"';
        } else {
            $result .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }
    }
    if (!$suppress_close) $result .= '>';
    return $result;
}

function _tag_close($tag) {
    return '</' . $tag . '>';
}

function _tag($tag, $_attrOrChildren = [], $_children = []) {
    $args = func_get_args();
    $attrs = $_attrOrChildren;
    $children = $_children;
    if (count(func_get_args()) === 2 && (!is_array($_attrOrChildren) || array_is_list($_attrOrChildren))) {
        $attrs = [];
        $children = $_attrOrChildren;
    }

    $result = _tag_open($tag, $attrs, true);
    $is_self_closing = in_array(strtolower($tag), SELF_CLOSING_TAGS);
    if (is_array($children) && (!$is_self_closing || count($children) > 0)) {
        $result .= '>';
        foreach($children as $child) {
            if (!$child) continue;
            else $result .= $child;
        }
        $result .= _tag_close($tag);
    } else if (is_string($children) || is_numeric($children)) {
        $result .= '>' . $children . _tag_close($tag);
    } else if (is_bool($children)) {
        $result .= '>' . ($children ? 'true' : 'false') . _tag_close($tag);
    } else if ($is_self_closing) {
        $result .= ' />';
    } else {
        $result .= '>' . $children . _tag_close($tag);
    }

    return $result;
}

function render_tab_content($data, $id_prefix) {
    $items = $data['items'];
    $note  = $data['note'];
    $links = $data['links'];

    if (!empty($items)) : ?>
        <div class="tab-checklist__accordion">
            <?php foreach ($items as $i => $item) :
                $title = $item['title'] ?? '';
                $inner = $item['inner_text'] ?? '';
                $f_note = $item['footer_note'] ?? '';
                $f_text = $item['footer_text'] ?? '';
                $cid   = $id_prefix . '-' . $i;
                if (!$title && !$inner) continue;
            ?>
                <div class="tab-checklist__item">
                    <button type="button" class="tab-checklist__itembtn" aria-expanded="false" aria-controls="<?= $cid ?>">
                        <span class="tab-checklist__itemtitle"><?= esc_html($title) ?></span>
                        <span class="tab-checklist__chev" aria-hidden="true"></span>
                    </button>
                    <div id="<?= $cid ?>" class="tab-checklist__itemcontent" hidden>
                        <?php if ($inner) : ?>
                            <div class="tab-checklist__itemtext"><?= wp_kses_post($inner) ?></div>
                        <?php endif; ?>

                        <?php if ($f_note || $f_text) : ?>
                            <hr class="tab-checklist__divider" aria-hidden="true" />
                            <?php if ($f_note) : ?>
                                <div class="tab-checklist__footer-note"><?= wp_kses_post(wpautop($f_note)) ?></div>
                            <?php endif; ?>
                            <?php if ($f_text) : ?>
                                <div class="tab-checklist__footer-text"><?= wp_kses_post($f_text) ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif;

    if ($note) : ?>
        <div class="tab-checklist__note">
            <?= wp_kses_post($note) ?>
        </div>
    <?php endif;

    if (!empty($links)) : ?>
        <div class="uic-cta-footer__container">
            <?php foreach ($links as $row) :
                $link = $row['link'] ?? null;
                if (!is_array($link) || empty($link['url'])) continue;
            ?>
                <a class="uic-cta-footer__link uic-cta-footer__link__white"
                   href="<?= esc_url($link['url']) ?>"
                   <?= !empty($link['target']) ? 'target="'.esc_attr($link['target']).'" rel="noopener noreferrer"' : '' ?>>
                    <?= esc_html($link['title'] ?: 'Learn more') ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif;
}
