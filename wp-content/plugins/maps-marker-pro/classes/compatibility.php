<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Compatibility {
	/**
	 * Name of the current page
	 *
	 * @since 4.0
	 * @var string
	 */
	private $page;

	/**
	 * Sets up the class
	 *
	 * @since 4.0
	 */
	public function __construct() {
		$this->page = isset($_GET['page']) ? $_GET['page'] : null;
	}

	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		if (!MMP::$settings['adminNotices']) {
			return;
		}

		add_action('all_admin_notices', array($this, 'check_compatibilities'));
	}

	/**
	 * Checks for compatibility issues
	 *
	 * @since 4.0
	 */
	public function check_compatibilities() {
		global $wp_rewrite;
		$l10n = MMP::get_instance('MMP\L10n');

		// Notices only shown on plugin pages
		if (strpos($this->page, 'mapsmarkerpro') !== false) {
			// Beta information
			if (MMP::$settings['betaTesting']) {
				$this->show_notice('info', sprintf($l10n->kses__('Beta testing is enabled - updates will be downloaded from the beta release channel. Use these versions at your own risk, as they might be unstable, and please use the <a href="%1$s" target="_blank">helpdesk</a> for feedback.', 'mmp'), 'https://www.mapsmarker.com/helpdesk/'));
			}

			// Permalinks compatibility check
			if ($wp_rewrite->using_mod_rewrite_permalinks()) {
				$response_code = wp_remote_retrieve_response_code(wp_remote_head(
					API::$base_url . API::$slug . '/'
				));
				if ($response_code === 404) {
					$message = sprintf($l10n->kses__('Permalinks for the Maps Marker Pro API endpoints are not working correctly, which means API links (e.g. fullscreen) will not work. To fix this, please navigate to the <a href="%1$s">WordPress integration settings</a> and add the URL to your WordPress folder to the option "Permalinks base URL".', 'mmp'), get_admin_url(null, 'admin.php?page=mapsmarkerpro_settings#misc_wordpress'));
					$guesses = array('wordpress', 'wp', 'blog');
					foreach ($guesses as $guess) {
						$response_code = wp_remote_retrieve_response_code(wp_remote_head(
							API::$base_url . $guess . '/' . API::$slug . '/'
						));
						if ($response_code !== 404) {
							$message .= ' ' . sprintf(esc_html__('The correct URL is: %1$s'), '<code>' . API::$base_url . $guess . '/' . '</code>');
							break;
						}
					}
					$this->show_notice('error', $message);
				}
			}
		}

		// Incompatible plugins
		$plugins = array(
			array(
				'name' => 'Better WordPress Minify',
				'file' => 'bwp-minify/bwp-minify.php'
			),
			array(
				'name' => 'WP deferred javaScripts',
				'file' => 'wp-deferred-javascripts/wp-deferred-javascripts.php'
			)
		);
		foreach ($plugins as $plugin) {
			if (is_plugin_active($plugin['file'])) {
				$this->show_notice('error', sprintf(esc_html__('You are using the plugin "%1$s", which is severely outdated and incompatible with Maps Marker Pro - please deactivate it.', 'mmp'), $plugin['name']));
			}
		}

		// Plugin Autoptimize
		if (is_plugin_active('autoptimize/autoptimize.php') && class_exists('autoptimizeConfig')) {
			$autoopt_config = \autoptimizeConfig::instance();
			if ($autoopt_config->get('autoptimize_js') === 'on') {
				if ($autoopt_config->get('autoptimize_js_forcehead') === 'on' && strpos($autoopt_config->get('autoptimize_js_exclude'), 'mapsmarkerpro.js') === false) {
					$this->show_notice('error', sprintf($l10n->kses__('You are using the plugin "Autoptimize", which breaks Maps Marker Pro. Please go to the <a href="%1$s">settings page</a>, enable advanced settings and either disable "Force JavaScript in &lt;head&gt;?" or add the following to the script exclusion list: %2$s', 'mmp'), get_admin_url(null, 'options-general.php?page=autoptimize'), '<code>mapsmarkerpro.js</code>'));
				}
			}
		}

		// Plugin Async JavaScript
		if (is_plugin_active('async-javascript/async-javascript.php') && get_option('aj_enabled') === '1') {
			$aj_exclusions = get_option('aj_exclusions');
			if (strpos($aj_exclusions, 'mapsmarkerpro.js') === false) {
				$this->show_notice('error', sprintf($l10n->kses__('You are using the plugin "Async JavaScript", which breaks Maps Marker Pro. Please go to the <a href="%1$s">settings page</a> and add the following to the script exclusion list: %2$s', 'mmp'), get_admin_url(null, 'options-general.php?page=async-javascript&tab=settings'), '<code>mapsmarkerpro.js</code>'));
			}
		}
	}

	/**
	 * Outputs an admin notice
	 *
	 * @since 4.0
	 *
	 * @param string $level Notice level (info, warning, error)
	 * @param string $message Message to be displayed
	 */
	private function show_notice($level, $message) {
		?><div class="notice notice-<?= $level ?>"><p><?= $message ?></p></div><?php
	}
}
