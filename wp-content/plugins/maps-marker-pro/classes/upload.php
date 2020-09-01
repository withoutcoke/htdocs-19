<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Upload {
	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		add_action('wp_ajax_mmp_icon_upload', array($this, 'icon_upload'));
	}

	/**
	 * AJAX request for uploading a marker icon to the icons directory
	 *
	 * @since 4.0
	 */
	public function icon_upload() {
		if (!isset($_POST['nonce']) || wp_verify_nonce($_POST['nonce'], 'mmp-icon-upload') === false) {
			wp_send_json(array(
				'success'  => false,
				'response' => esc_html__('Security check failed', 'mmp')
			));
		}

		if (!isset($_FILES['upload'])) {
			wp_send_json(array(
				'success'  => false,
				'response' => esc_html__('File missing', 'mmp')
			));
		}

		add_filter('upload_dir', function($upload) {
			$upload['subdir'] = '';
			$upload['path'] = untrailingslashit(MMP::$icons_dir);
			$upload['url'] = untrailingslashit(MMP::$icons_url);

			return $upload;
		});

		$upload = wp_handle_upload($_FILES['upload'], array(
			'test_form' => false,
			'mimes' => array(
				'png' => 'image/png',
				'gif' => 'image/gif',
				'jpg' => 'image/jpeg'
			)
		));

		if (isset($upload['error'])) {
			wp_send_json(array(
				'success'  => false,
				'response' => $upload['error']
			));
		}

		$upload['name'] = basename($upload['file']);

		wp_send_json(array(
			'success'  => true,
			'response' => $upload
		));
	}

	/**
	 * Reads a CSV file and converts it to an associative array
	 *
	 * @since 4.9
	 *
	 * @param string $file Absolute path to the file
	 * @param string $delimiter (optional) Field delimiter (autodetected if empty)
	 * @param string $enclosure (optional) Field enclosure
	 * @param string $escape (optional) Escape character (disabled if empty)
	 */
	public function parse_csv($file, $delimiter = '', $enclosure = '"', $escape = '\\') {
		ini_set('auto_detect_line_endings', '1');

		$handle = @fopen($file, 'r');
		if ($handle === false) {
			return false;
		};

		$this->skip_bom($handle);

		if ($delimiter === '') {
			$dels = array(',', ';', "\t");
			foreach ($dels as $del) {
				$row1 = fgetcsv($handle, 0, $del, $enclosure, $escape);
				$row2 = fgetcsv($handle, 0, $del, $enclosure, $escape);

				rewind($handle);
				$this->skip_bom($handle);

				if (count($row1) > 1 && count($row1) === count($row2)) {
					$delimiter = $del;
					break;
				}
			}

			if ($delimiter === '') {
				return null;
			}
		}

		$header = null;
		$data = array();
		while (($row = fgetcsv($handle, 0, $delimiter, $enclosure, $escape)) !== false) {
			if (!$header) {
				if (count($row) < 2) {
					return null;
				}

				$header = $row;
				continue;
			}

			if (count($header) !== count($row)) {
				return null;
			}

			$data[] = array_combine($header, $row);
		}
		fclose($handle);

		return $data;
	}

	/**
	 * Reads a JSON file and converts it to an associative array
	 *
	 * @since 4.9
	 *
	 * @param string $file Absolute path to the file
	 */
	public function parse_json($file) {
		$content = @file_get_contents($file);
		if ($content === false) {
			return false;
		}

		return json_decode($content, true);
	}

	/**
	 * Determines the maximum permitted file size for uploads
	 *
	 * @since 4.0
	 */
	public function get_max_upload_size() {
		$post = $this->parse_size(ini_get('post_max_size'));
		$upload = $this->parse_size(ini_get('upload_max_filesize'));
		$memory = $this->parse_size(ini_get('memory_limit'));
		$max = min($post, $upload, $memory);

		return $max;
	}

	/**
	 * Parses a size string (e.g. 8M) into bytes
	 *
	 * @since 4.0
	 *
	 * @param string $size Size string to parse
	 */
	public function parse_size($size) {
		if (intval($size) <= 0) {
			return 0;
		}

		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
		$size = preg_replace('/[^0-9\.]/', '', $size);
		if ($unit) {
			$size = $size * pow(1024, stripos('bkmgtpezy', $unit[0]));
		}

		return round($size);
	}

	/**
	 * Returns a list of available marker icons
	 *
	 * @since 4.0
	 */
	public function get_icons() {
		$allowed = array('png', 'gif', 'jpg', 'jpeg');
		$icons = array();
		if (($dir = @opendir(MMP::$icons_dir)) !== false) {
			while (($file = readdir($dir)) !== false) {
				$info = pathinfo($file);
				$ext = strtolower($info['extension']);
				if (!is_dir($dir . $file) && in_array($ext, $allowed)) {
					$icons[] = $file;
				}
			}
			closedir($dir);
			sort($icons);
		}

		return $icons;
	}

	/**
	 * Moves a file pointer past the BOM magic number if detected
	 *
	 * @since 4.9
	 *
	 * @param resource $handle File pointer
	 */
	private function skip_bom($handle) {
		if (fgets($handle, 4) !== "\xef\xbb\xbf") {
			rewind($handle);
		}
	}
}
