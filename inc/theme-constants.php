<?php

define('HERO_TEXT_VIEWBOX', [
    'discover' => '0 0 848.9 146.4',
    'explore' => '0 0 736.9 146.4',
    'connect' => '0 0 789.8 146.4',
    'become' => '0 0 677 146.4',
    'afford' => '0 0 912 492'
]);

define('VIMEO_META_TTL', 60  * 60 * 24 * 7);
// after 5 failed attempts, don't try again for 2 hrs
define('VIMEO_META_FAILED_TTL', 60 * 60 * 2);

define('VIMEO_META_MAX_FAILED_REQUESTS', 5);

// after 3 failed attempts, don't try again for 6 hrs
define('UPCOMING_EVENTS_FAILED_TTL', 60 * 60 * 6);
define('UPCOMING_EVENTS_MAX_FAILED_REQUESTS', 3);
