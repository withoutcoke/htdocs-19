<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Download {
	/**
	 * Downloads the GPX file attached to a map
	 *
	 * @since 4.0
	 */
	public function download_gpx() {
		if (!isset($_GET['url'])) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('URL missing', 'mmp'));
		}
		$url = esc_url_raw($_GET['url']);
		if (substr(strtolower($url), 0, 4) !== 'http') {
			$url = get_site_url(null, $url);
		}
		if (wp_http_validate_url($url) === false) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('Invalid URL', 'mmp'));
		}
		$id = attachment_url_to_postid($url);
		if ($id === 0) {
			$file = wp_remote_get($url);
			if (is_wp_error($file) || $file['response']['code'] !== 200) {
				die(esc_html__('Error', 'mmp') . ': ' . esc_html__('Could not retrieve file', 'mmp'));
			}
			$content = $file['body'];
			$filename = basename($url);
			$filesize = $file['headers']['content-length'];
		} else {
			$file = get_attached_file($id);
			if (!file_exists($file)) {
				die(esc_html__('Error', 'mmp') . ': ' . esc_html__('File not found', 'mmp'));
			}
			$content = file_get_contents($file);
			$filename = basename($file);
			$filesize = filesize($file);
		}

		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: application/gpx+xml');
		header('Content-Length: ' . $filesize);

		echo $content;
	}

	/**
	 * Downloads a file stored in the plugin's temp directory
	 *
	 * @since 4.0
	 */
	public function download_temp() {
		if (!isset($_GET['nonce']) || wp_verify_nonce($_GET['nonce'], 'mmp-download-temp') === false) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('Security check failed', 'mmp'));
		}
		if (!isset($_GET['filename'])) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('Filename missing', 'mmp'));
		}
		$filename = basename($_GET['filename']);
		if (!$filename || validate_file($filename) !== 0) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('Invalid filename', 'mmp'));
		}
		$file = MMP::$temp_dir . $filename;
		if (!file_exists($file)) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('File not found', 'mmp'));
		}

		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . filesize($file));

		readfile($file);
	}

	/**
	 * Downloads debug information
	 *
	 * @since 4.13
	 */
	public function download_debug() {
		$l10n = MMP::get_instance('MMP\L10n');
		$debug = MMP::get_instance('MMP\Debug');

		if (!isset($_GET['nonce']) || wp_verify_nonce($_GET['nonce'], 'mmp-download-debug') === false) {
			die(esc_html__('Error', 'mmp') . ': ' . esc_html__('Security check failed', 'mmp'));
		}
		$debug_info = $debug->get_info();
		$filename = 'debug-' . gmdate('Y-m-d-his') . '.log';

		header('Cache-Control: no-store, no-cache');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: text/plain');

		var_export($debug_info);
	}
}
