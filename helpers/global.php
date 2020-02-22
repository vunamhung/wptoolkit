<?php

namespace vnh;

use WP_Filesystem_Base;
use WP_Post;
use WP_Query;
use wpdb;

/**
 * @return wpdb
 */
function wpdb() {
	global $wpdb;

	return $wpdb;
}

/**
 * @return WP_Query
 */
function wp_query() {
	global $wp_query;

	return $wp_query;
}

/**
 * @return WP_Post
 */
function post() {
	global $post;

	return $post;
}

/**
 * @return WP_Filesystem_Base
 */
function fs() {
	global $wp_filesystem;

	return $wp_filesystem;
}
