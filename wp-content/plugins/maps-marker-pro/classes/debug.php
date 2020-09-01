<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Debug {
	/**
	 * Returns debug information
	 *
	 * @since 4.13
	 */
	public function get_info() {
		global $wp_version, $wp_rewrite;

		return array(
			'mmp_version'  => MMP::$version,
			'wp_version'   => $wp_version,
			'php_version'  => PHP_VERSION,
			'wp_rewrite'   => $wp_rewrite->using_mod_rewrite_permalinks(),
			'api_response' => wp_remote_head(API::$base_url . API::$slug . '/')
		);
	}
}
