<?php
/**
 * Template Name: Landing Page template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package thirdwave
 */

// get_header();
include( locate_template( 'header-landing.php' ) );


if (have_posts()) {
	while (have_posts()) {
		the_post();
		get_template_part( 'template-parts/content', 'page' );
	}
}

// get_footer();
include( locate_template( 'footer-landing.php' ) );

?>
