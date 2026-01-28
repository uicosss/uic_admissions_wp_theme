<?php 

add_filter( 'gform_field_value_current_url', 'populate_current_url');
function populate_current_url($value){
	?>
	<script>
		document.cookie = `current_url=${encodeURIComponent(window.location.href)}`;
	</script>
	<?php
	if ($_COOKIE['current_url']) {
		$current_url = $_COOKIE['current_url'];
	} else {
		$current_url = 'URL unknown';
	}
    return $current_url;
}

add_action( 'gform_pre_submission', 'pre_submission_handler' );
function pre_submission_handler($form) {
	if ($_COOKIE['current_url']) {
    	$_POST['input_10'] = $current_url = $_COOKIE['current_url'];
	} else {
    	$_POST['input_10'] = $current_url = 'URL unknown';
	}
}

add_filter( 'gform_field_value_resolution', 'populate_resolution' );
function populate_resolution( $value ) {
	?>
	<script>
		const screenWidth = window.screen.width;
		const screenHeight = window.screen.height;
		const screenRes = `${screenWidth} x ${screenHeight}`
		document.cookie=`screen_res=${screenRes}`;
	</script>
	<?php
	$screen_res = $_COOKIE['screen_res'];
    return $screen_res;
}

add_filter( 'gform_field_value_window_size', 'populate_window_size' );
function populate_window_size( $value ) {
	?>
	<script>
		const windowWidth = window.innerWidth;
		const windowHeight = window.innerHeight;
		const windowSize = `${windowWidth} x ${windowHeight}`
		document.cookie=`window_size=${windowSize}`;
	</script>
	<?php
	$window_size = $_COOKIE['window_size'];
    return $window_size;
}