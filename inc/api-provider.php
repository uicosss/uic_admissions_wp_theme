<?php
add_action('rest_api_init', function () {
    register_rest_route('tuition-calendar/v1', '/tuition-data', array(
        'methods' => 'GET',
        'callback' => 'get_tuition_data',
        'permission_callback' => function ($request) {
            $auth_header = $request->get_header('X-UIC-Shared-Key');
            return defined('UIC_SHARED_API_KEY') && $auth_header === UIC_SHARED_API_KEY;
        }
    ));
});

function get_tuition_data($req)
{
    // from my understatnding the data will live within wordpress?
    // so perhaps there will some handling up here?
    $data = [
        'something' => 'else',
        'and' => 'another'
    ];

    if (empty($data)) {
        return new WP_Error(
            'no_data',
            'Tuition data missing, try again later',
            array('status' => 404)
        );
    }

    return new WP_REST_Response($data, 200);
}
