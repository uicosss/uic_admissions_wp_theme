<?php
add_action('rest_api_init', function () {
    // thus the api route will be:
    // __BaseUrl__ . /wp-json/tuition-calendar/v1/tuition-data
    register_rest_route('tuition-calendar/v1', '/tuition-data', array(
        'methods' => 'GET',
        'callback' => 'get_tuition_data',
        'permission_callback' => function ($request) {
            $auth_header = $request->get_header('X-UIC-Shared-Key');
            return defined('UIC_SHARED_API_KEY') && $auth_header === UIC_SHARED_API_KEY;
        }
    ));
});

function get_tuition_data()
{
    /*
        This data resides within the
        ACF theme options page: calulator-config
        (Theme Config -> Calculator Config)
    */
    $differentials = [];
    $departments = get_field('differentials', 'option');

    foreach ($departments as $department) {
        $programs = [];
        foreach ($department['programs'] as $program) {
            array_push($programs, [
                'name' => $program['name'],
                'cost' => (float)$program['cost'],
                'slug' => $program['slug'],
            ]);
        }
        array_push($differentials, [
            'programs' => $programs,
            'department' => $department['department']
        ]);
    }

    $calculator_data = [
        'base_tuition' => array_map(fn($value) => (float)$value, get_field('tuition', 'option')),
        'mandatory_fees' => array_map(fn($value) => (float)$value, get_field('mandatory_fees', 'option')),
        'elective_fees' => array_map(fn($value) => (float)$value, get_field('elective_fees', 'option')),
        'cost_of_attendance' => array_map(fn($value) => (float)$value, get_field('cost_of_attendance', 'option')),
        'differentials' => $differentials,
        'result_links' => get_field('result_links', 'option')
    ];

    if (empty($calculator_data)) {
        return new WP_Error(
            'no_data',
            'Tuition data missing, try again later',
            array('status' => 404)
        );
    }

    return new WP_REST_Response($calculator_data, 200);
}
