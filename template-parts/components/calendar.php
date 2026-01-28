<?php

function render_calendar($events) {
    $current_date_str = null;
    foreach($events as $event) {
        if ($event['date_start_utc'] !== $current_date_str) {
            if ($current_date_str !== null) {
                echo _tag_close('div');
                echo _tag_close('div');
                echo _tag_close('div');
            }
            echo _tag_open('div', [
                'class' => 'uic-calendar__highlighted-date',
                'data-date' => $event['date_start_utc']
            ]);
            echo _tag_open('div', [
                'class' => 'uic-calendar-popup'
            ]);
            echo _tag_open('div', [
                'class' => 'uic-calendar-popup__inner'
            ]);
            $current_date_str = $event['date_start_utc'];
        }

        echo _tag('a', [
            'href' => $event['link'],
            'target' => '_blank',
            'title' => 'Register for ' . $event['title'],
            'class' => 'uic-calendar-popup__event'
        ], [
            _tag('svg', [
                'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
                'class' => 'uic-calendar-popup__icon',
                'viewBox' => '0 0 512 512'
            ], [
                _tag('use', [
                    'xlink:href' => THEME_ASSET_BASE . '/images/calendar-icon.svg#uic-calendar-popup__icon'
                ])
            ]),
            _tag('span', [
                'class' => 'uic-calendar-popup__details'
            ], [
                _tag('span', [
                    'class' => 'uic-calendar-popup__title'
                ], $event['title']),
                _tag('span', [
                    'class' => 'uic-calendar-popup__time'
                ], $event['time_start'] . ' - ' . $event['time_end'])
            ])
        ]);
    }
    echo _tag_close('div');
    echo _tag_close('div');
    echo _tag_close('div');
}