<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Update {
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
		add_filter('puc_check_now-maps-marker-pro', array($this, 'puc_update_check'));

		add_action('init', array($this, 'update'));
		add_action('all_admin_notices', array($this, 'check'));
		add_action('all_admin_notices', array($this, 'changelog'));
		add_action('wp_ajax_mmp_dismiss_changelog', array($this, 'dismiss_changelog'));
	}

	/**
	 * Filters the PUC update check
	 *
	 * @since 4.0
	 *
	 * @param bool $check Whether a check for updates would occur
	 */
	public function puc_update_check($check) {
		$license = MMP::get_instance('MMP\License');

		if ($check !== false) {
			$check = $license->check_for_updates();
		}

		return $check;
	}

	/**
	 * Executes the update routines
	 *
	 * @since 4.0
	 */
	public function update() {
		global $wpdb;
		$license = MMP::get_instance('MMP\License');
		$mmp_settings = MMP::get_instance('MMP\Settings');
		$setup = MMP::get_instance('MMP\Setup');

		$version = get_option('mapsmarkerpro_version');
		if (!$version || version_compare($version, MMP::$version, '>=') || !$license->check_for_updates(true)) {
			return;
		}

		if (!version_compare($version, '4.3', '>=')) {
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_layers`
				CHANGE `url` `url` VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_maps`
				ADD `geojson` TEXT NOT NULL AFTER `filters`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				ADD `blank` INT(1) NOT NULL AFTER `link`"
			);
		}

		if (!version_compare($version, '4.4', '>=')) {
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_maps`
				CHANGE `geojson` `geojson` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL"
			);
		}

		if (!version_compare($version, '4.5', '>=')) {
			$map_ids = $wpdb->get_col(
				"SELECT `id`
				FROM `{$wpdb->prefix}mmp_maps`"
			);
			foreach ($map_ids as $map_id) {
				$map = $wpdb->get_row($wpdb->prepare(
					"SELECT `settings`
					FROM `{$wpdb->prefix}mmp_maps`
					WHERE `id` = %d",
					$map_id
				));
				if ($map === null) {
					continue;
				}
				$map->settings = json_decode($map->settings, true);
				if (isset($map->settings['layersCollapsed']) && is_bool($map->settings['layersCollapsed'])) {
					$map->settings['layersCollapsed'] = ($map->settings['layersCollapsed'] === true) ? 'collapsed' : 'expanded';
				}
				if (isset($map->settings['filtersCollapsed']) && is_bool($map->settings['filtersCollapsed'])) {
					$map->settings['filtersCollapsed'] = ($map->settings['filtersCollapsed'] === true) ? 'collapsed' : 'expanded';
				}
				if (isset($map->settings['minimapMinimized']) && is_bool($map->settings['minimapMinimized'])) {
					$map->settings['minimapMinimized'] = ($map->settings['minimapMinimized'] === true) ? 'collapsed' : 'expanded';
				}
				if (isset($map->settings['gpxChartPolylineWeight'])) {
					$map->settings['gpxChartLineWidth'] = $map->settings['gpxChartPolylineWeight'];
				}
				if (isset($map->settings['gpxChartPolylineColor'])) {
					$map->settings['gpxChartLineColor'] = $map->settings['gpxChartPolylineColor'];
				}
				if (isset($map->settings['gpxChartPolygon'])) {
					$map->settings['gpxChartFill'] = $map->settings['gpxChartPolygon'];
				}
				if (isset($map->settings['gpxChartPolygonFillColor'])) {
					$map->settings['gpxChartFillColor'] = $map->settings['gpxChartPolygonFillColor'];
				}
				$map->settings = $mmp_settings->validate_map_settings($map->settings, false, false);
				$map->settings = json_encode($map->settings, JSON_FORCE_OBJECT);
				$update = $wpdb->update(
					"{$wpdb->prefix}mmp_maps",
					array('settings' => $map->settings),
					array('id' => $map_id),
					array('%s'),
					array('%d')
				);
			}

			$map_defaults = get_option('mapsmarkerpro_map_defaults');
			if (isset($map_defaults['layersCollapsed']) && is_bool($map_defaults['layersCollapsed'])) {
				$map_defaults['layersCollapsed'] = ($map_defaults['layersCollapsed'] === true) ? 'collapsed' : 'expanded';
			}
			if (isset($map_defaults['filtersCollapsed']) && is_bool($map_defaults['filtersCollapsed'])) {
				$map_defaults['filtersCollapsed'] = ($map_defaults['filtersCollapsed'] === true) ? 'collapsed' : 'expanded';
			}
			if (isset($map_defaults['minimapMinimized']) && is_bool($map_defaults['minimapMinimized'])) {
				$map_defaults['minimapMinimized'] = ($map_defaults['minimapMinimized'] === true) ? 'collapsed' : 'expanded';
			}
			if (isset($map_defaults['gpxChartPolylineWeight'])) {
				$map_defaults['gpxChartLineWidth'] = $map_defaults['gpxChartPolylineWeight'];
			}
			if (isset($map_defaults['gpxChartPolylineColor'])) {
				$map_defaults['gpxChartLineColor'] = $map_defaults['gpxChartPolylineColor'];
			}
			if (isset($map_defaults['gpxChartPolygon'])) {
				$map_defaults['gpxChartFill'] = $map_defaults['gpxChartPolygon'];
			}
			if (isset($map_defaults['gpxChartPolygonFillColor'])) {
				$map_defaults['gpxChartFillColor'] = $map_defaults['gpxChartPolygonFillColor'];
			}
			$map_defaults = $mmp_settings->validate_map_settings($map_defaults, false, false);
			update_option('mapsmarkerpro_map_defaults', $map_defaults);

			$settings = get_option('mapsmarkerpro_settings');
			$settings = $mmp_settings->validate_settings($settings, false, false);
			update_option('mapsmarkerpro_settings', $settings);
		}

		if (!version_compare($version, '4.7', '>=')) {
			wp_clear_scheduled_hook('mmp_temp_cleanup', array(604800));
		}

		if (!version_compare($version, '4.8', '>=')) {
			delete_option('mapsmarkerpro_editor');
		}

		if (!version_compare($version, '4.11', '>=')) {
			$map_ids = $wpdb->get_col(
				"SELECT `id`
				FROM `{$wpdb->prefix}mmp_maps`"
			);
			foreach ($map_ids as $map_id) {
				$map = $wpdb->get_row($wpdb->prepare(
					"SELECT `settings`, `filters`
					FROM `{$wpdb->prefix}mmp_maps`
					WHERE `id` = %d",
					$map_id
				));
				if ($map === null) {
					continue;
				}
				$map->settings = json_decode($map->settings, true);
				if (isset($map->settings['gpxStartIcon'])) {
					if (substr($map->settings['gpxStartIcon'], -strlen('/images/leaflet/gpx-start.png')) === '/images/leaflet/gpx-start.png') {
						$map->settings['gpxStartIcon'] = '';
					} else {
						$map->settings['gpxStartIcon'] = basename($map->settings['gpxStartIcon']);
					}
				}
				if (isset($map->settings['gpxEndIcon'])) {
					if (substr($map->settings['gpxEndIcon'], -strlen('/images/leaflet/gpx-end.png')) === '/images/leaflet/gpx-end.png') {
						$map->settings['gpxEndIcon'] = '';
					} else {
						$map->settings['gpxEndIcon'] = basename($map->settings['gpxEndIcon']);
					}
				}
				$map->settings = json_encode($map->settings, JSON_FORCE_OBJECT);
				$map->filters = json_decode($map->filters, true);
				foreach ($map->filters as $key => $map_filter) {
					if (isset($map_filter['icon'])) {
						if (substr($map_filter['icon'], -strlen('/images/leaflet/marker.png')) === '/images/leaflet/marker.png') {
							$map->filters[$key]['icon'] = '';
						} else {
							$map->filters[$key]['icon'] = basename($map_filter['icon']);
						}
					}
				}
				$map->filters = json_encode($map->filters, JSON_FORCE_OBJECT);
				$update = $wpdb->update(
					"{$wpdb->prefix}mmp_maps",
					array(
						'settings' => $map->settings,
						'filters' => $map->filters
					),
					array('id' => $map_id),
					array('%s', '%s'),
					array('%d')
				);
			}
		}

		if (!version_compare($version, '4.12', '>=')) {
			$map_ids = $wpdb->get_col(
				"SELECT `id`
				FROM `{$wpdb->prefix}mmp_maps`"
			);
			foreach ($map_ids as $map_id) {
				$map = $wpdb->get_row($wpdb->prepare(
					"SELECT `settings`
					FROM `{$wpdb->prefix}mmp_maps`
					WHERE `id` = %d",
					$map_id
				));
				if ($map === null) {
					continue;
				}
				$map->settings = json_decode($map->settings, true);
				if (isset($map->settings['gpxIcons'])) {
					$map->settings['gpxShowStartIcon'] = $map->settings['gpxIcons'];
					$map->settings['gpxShowEndIcon'] = $map->settings['gpxIcons'];
				}
				$map->settings = $mmp_settings->validate_map_settings($map->settings, false, false);
				$map->settings = json_encode($map->settings, JSON_FORCE_OBJECT);
				$update = $wpdb->update(
					"{$wpdb->prefix}mmp_maps",
					array('settings' => $map->settings),
					array('id' => $map_id),
					array('%s'),
					array('%d')
				);
			}

			$map_defaults = get_option('mapsmarkerpro_map_defaults');
			if (isset($map_defaults['gpxIcons'])) {
				$map_defaults['gpxShowStartIcon'] = $map_defaults['gpxIcons'];
				$map_defaults['gpxShowEndIcon'] = $map_defaults['gpxIcons'];
			}
			$map_defaults = $mmp_settings->validate_map_settings($map_defaults, false, false);
			update_option('mapsmarkerpro_map_defaults', $map_defaults);
		}

		if (!version_compare($version, '4.13', '>=')) {
			$map_ids = $wpdb->get_col(
				"SELECT `id`
				FROM `{$wpdb->prefix}mmp_maps`"
			);
			foreach ($map_ids as $map_id) {
				$map = $wpdb->get_row($wpdb->prepare(
					"SELECT `settings`
					FROM `{$wpdb->prefix}mmp_maps`
					WHERE `id` = %d",
					$map_id
				));
				if ($map === null) {
					continue;
				}
				$map->settings = json_decode($map->settings, true);
				if (!isset($map->settings['maxBounds'])) {
					continue;
				}
				$bounds = explode(',', $map->settings['maxBounds']);
				if (count($bounds) !== 4) {
					continue;
				}
				$newBounds = array($bounds[1], $bounds[0], $bounds[3], $bounds[2]);
				$map->settings['maxBounds'] = implode(',', $newBounds);
				$map->settings = json_encode($map->settings, JSON_FORCE_OBJECT);
				$update = $wpdb->update(
					"{$wpdb->prefix}mmp_maps",
					array('settings' => $map->settings),
					array('id' => $map_id),
					array('%s'),
					array('%d')
				);
			}

			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_maps`
				ADD `created_by_id` bigint(20) NOT NULL AFTER `created_by`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_maps`
				ADD `updated_by_id` bigint(20) NOT NULL AFTER `updated_by`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				ADD `created_by_id` bigint(20) NOT NULL AFTER `created_by`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				ADD `updated_by_id` bigint(20) NOT NULL AFTER `updated_by`"
			);

			$usernames = $wpdb->get_col(
				"SELECT `username`
				FROM (
					SELECT `created_by` AS `username`
					FROM `{$wpdb->prefix}mmp_maps`
					UNION ALL
					SELECT `updated_by` AS `username`
					FROM `{$wpdb->prefix}mmp_maps`
					UNION ALL
					SELECT `created_by` AS `username`
					FROM `{$wpdb->prefix}mmp_markers`
					UNION ALL
					SELECT `updated_by` AS `username`
					FROM `{$wpdb->prefix}mmp_markers`
				) AS t
				GROUP BY `username`"
			);
			$user_ids = array();
			foreach ($usernames as $username) {
				$user = get_user_by('login', $username);
				$user_ids[$username] = ($user) ? $user->ID : get_current_user_id();
			}

			foreach ($user_ids as $username => $user_id) {
				$wpdb->update(
					"{$wpdb->prefix}mmp_maps",
					array('created_by_id' => $user_id),
					array('created_by' => $username),
					array('%d'),
					array('%s')
				);
				$wpdb->update(
					"{$wpdb->prefix}mmp_maps",
					array('updated_by_id' => $user_id),
					array('updated_by' => $username),
					array('%d'),
					array('%s')
				);
				$wpdb->update(
					"{$wpdb->prefix}mmp_markers",
					array('created_by_id' => $user_id),
					array('created_by' => $username),
					array('%d'),
					array('%s')
				);
				$wpdb->update(
					"{$wpdb->prefix}mmp_markers",
					array('updated_by_id' => $user_id),
					array('updated_by' => $username),
					array('%d'),
					array('%s')
				);
			}

			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_maps`
				DROP COLUMN `created_by`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_maps`
				DROP COLUMN `updated_by`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				DROP COLUMN `created_by`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				DROP COLUMN `updated_by`"
			);

			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				ADD `schedule_from` DATETIME NULL DEFAULT NULL AFTER `blank`"
			);
			$wpdb->query(
				"ALTER TABLE `{$wpdb->prefix}mmp_markers`
				ADD `schedule_until` DATETIME NULL DEFAULT NULL AFTER `schedule_from`"
			);
		}

		// WordPress no longer triggers the activation hook after updates
		// So always run the setup routine to avoid potential problems
		$setup->setup();

		update_option('mapsmarkerpro_version', MMP::$version);
		update_option('mapsmarkerpro_changelog', $version);
		update_option('mapsmarkerpro_key_local', null);
	}

	/**
	 * Checks whether an update is available
	 *
	 * @since 4.0
	 */
	public function check() {
		global $pagenow;
		$license = MMP::get_instance('MMP\License');
		$l10n = MMP::get_instance('MMP\L10n');

		if ((strpos($this->page, 'mapsmarkerpro') === false || $this->page === 'mapsmarkerpro_license') && $pagenow !== 'plugins.php') {
			return;
		}

		if ($license->check_for_updates()) {
			$update_plugins = get_site_transient('update_plugins');
			if (isset($plugin_updates->response[MMP::$file]->new_version)) {
				$new_version = $update_plugins->response[MMP::$file]->new_version;
				?>
				<div class="notice notice-warning">
					<p>
						<strong><?= esc_html__('Maps Marker Pro - plugin update available!', 'mmp') ?></strong><br />
						<?= sprintf($l10n->kses__('You are currently using v%1$s and the plugin author highly recommends updating to v%2$s for new features, bugfixes and updated translations (please see <a href="%3$s" target="_blank">this blog post</a> for more details about the latest release).', 'mmp'), MMP::$version, $new_version, "https://mapsmarker.com/v{$new_version}p") ?><br />
						<?php if (current_user_can('update_plugins')): ?>
							<?= sprintf($l10n->kses__('Update instruction: please start the update from the <a href="%1$s">updates page</a>.', 'mmp'), get_admin_url(null, 'update-core.php')) ?>
						<?php else: ?>
							<?= sprintf($l10n->kses__('Update instruction: as your user does not have the right to update plugins, please contact your <a href="%1$s">administrator</a>', 'mmp'), 'mailto:' . get_option('admin_email')) ?>
						<?php endif; ?>
					</p>
				</div>
				<?php
			}
		} else if ($license->check_for_updates(true)) {
			$latest_version = get_transient('mapsmarkerpro_latest');
			if ($latest_version === false) {
				$check_latest = wp_remote_get('https://www.mapsmarker.com/updates_pro/?action=get_metadata&slug=maps-marker-pro', array(
					'sslverify' => true,
					'timeout' => 5
				));
				if (is_wp_error($check_latest) || $check_latest['response']['code'] !== 200) {
					$latest_version = MMP::$version;
				} else {
					$latest_version = json_decode($check_latest['body']);
					if ($latest_version->version === null) {
						$latest_version = MMP::$version;
					} else {
						$latest_version = $latest_version->version;
					}
				}
				set_transient('mapsmarkerpro_latest', $latest_version, 60 * 60 * 24);
			}
			?>
			<div class="notice notice-warning">
				<p>
					<strong><?= esc_html__('Warning: your access to updates and support for Maps Marker Pro has expired!', 'mmp') ?></strong><br />
					<?php if ($latest_version !== false && version_compare($latest_version, MMP::$version, '>')): ?>
						<?= esc_html__('Latest available version:', 'mmp') ?> <a href="https://www.mapsmarker.com/v<?= $latest_version ?>" target="_blank" title="<?= esc_attr__('Show release notes', 'mmp') ?>"><?= $latest_version ?></a> (<a href="https://www.mapsmarker.com/changelog/pro/" target="_blank"><?= esc_html__('show all available changelogs', 'mmp') ?></a>)<br />
					<?php endif; ?>
					<?= sprintf(esc_html__('You can continue using version %1$s without any limitations. However, you will not be able access the support system or get updates including bugfixes, new features and optimizations.', 'mmp'), MMP::$version) ?><br />
					<?= sprintf($l10n->kses__('<a href="%1$s">Please renew your access to updates and support to keep your plugin up-to-date and safe</a>.', 'mmp'), get_admin_url(null, 'admin.php?page=mapsmarkerpro_license')) ?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Displays the changelog after an update
	 *
	 * @since 4.0
	 */
	public function changelog() {
		$changelog = get_option('mapsmarkerpro_changelog');

		if (!$changelog || strpos($this->page, 'mapsmarkerpro') === false) {
			return;
		}

		?>
		<style>
			#mmp-changelog-wrap {
				margin: 10px 20px 0 2px;
				padding: 5px;
				background-color: #ffffe0;
				border: 1px solid #e6db55;
				border-radius: 5px;
			}
			#mmp-changelog-wrap h2 {
				margin: 0;
				padding: 0;
				font-weight: bold;
			}
			#mmp-changelog {
				overflow: auto;
				height: 250px;
				margin: 5px 0;
				border: 1px dashed #e6db55;
			}
		</style>

		<div id="mmp-changelog-wrap">
			<h2><?= sprintf(esc_html__('Maps Marker Pro has been successfully updated from version %1s to %2s!', 'mmp'), $changelog, MMP::$version) ?></h2>
			<div id="mmp-changelog">
				<p><?= esc_html__('Loading changelog, please wait ...', 'mmp') ?></p>
			</div>
			<button type="button" id="mmp-dismiss-changelog" class="button button-secondary"><?= esc_html__('Hide changelog', 'mmp') ?></button>
		</div>

		<script>
			jQuery(document).ready(function($) {
				var link = 'https://www.mapsmarker.com/?changelog=<?= $changelog ?>-<?= MMP::$version ?>';

				$('#mmp-changelog').load(link, function(response, status, xhr) {
					if (status === 'error') {
						$('#mmp-changelog').append('<p><?= esc_html__('Changelog could not be loaded, please try again later.', 'mmp') ?></p>');
					}
				});

				$('#mmp-dismiss-changelog').click(function() {
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						context: this,
						data: {
							action: 'mmp_dismiss_changelog',
							nonce: '<?= wp_create_nonce('mmp-dismiss-changelog') ?>'
						},
						beforeSend: function() {
							$('#mmp-changelog-wrap').remove();
						}
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Dismisses the changelog
	 *
	 * @since 4.0
	 */
	public function dismiss_changelog() {
		if (!isset($_POST['nonce']) || wp_verify_nonce($_POST['nonce'], 'mmp-dismiss-changelog') === false) {
			wp_die();
		}

		update_option('mapsmarkerpro_changelog', null);

		wp_die();
	}
}
