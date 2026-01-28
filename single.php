<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package thirdwave
 */

 get_header();

 if (have_posts()) {
	 while (have_posts()) {
		 the_post();
		 the_content();
	 }
 }
 
 get_footer();
