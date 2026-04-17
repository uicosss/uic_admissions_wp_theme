<?php

function get_remote_tuition_data()
{
    $cache_key = 'UIC_CALCULATOR_DATA';
    $data = get_transient($cache_key);

    if (false === $data) {
        // change for live
        $api_url = CALC_API_ENDPOINT;
        // get from api
        $res = wp_remote_get(
            $api_url,
            array(
                'headers' => [
                    'X-UIC-Shared-Key' => defined('UIC_SHARED_API_KEY') ? UIC_SHARED_API_KEY : '',
                    'Accept'           => 'application/json'
                ],
                'timeout' => 15
            )
        );

        if (is_wp_error($res)) {
            return false;
        }

        $body = wp_remote_retrieve_body($res);
        $data = json_decode($body, true);

        if (empty($data)) {
            return false; // do not cache empty json
        }

        // set in cache for 24 hours
        set_transient($cache_key, $data, 24 * HOUR_IN_SECONDS);
    }

    return $data;
}
