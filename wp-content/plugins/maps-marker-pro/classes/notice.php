<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Notice {
	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		if (!MMP::$settings['adminNotices']) {
			return;
		}

		add_action('all_admin_notices', array($this, 'show_notice'));
		add_action('wp_ajax_mmp_dismiss_admin_notice', array($this, 'dismiss_admin_notice'));
	}

	/**
	 * Shows previously addeded admin notices
	 *
	 * @since 4.0
	 */
	public function show_notice() {
		if (isset($_GET['page']) && $_GET['page'] === 'mapsmarkerpro_license') {
			return;
		}

		$notices = get_option('mapsmarkerpro_notices');
		if (!is_array($notices) || !count($notices)) {
			return;
		}

		foreach ($notices as $key => $value) {
			$notice = $this->get_notice($value);
			if ($notice === false) {
				unset($notices[$key]);
				update_option('mapsmarkerpro_notices', $notices);
				continue;
			}

			?>
			<div class="notice notice-<?= $notice['level'] ?> is-dismissible mmp-dismissible" data-notice="<?= $value ?>">
				<p><?= $notice['msg'] ?></p>
			</div>
			<?php
		}

		?>
		<script>
			jQuery(document).ready(function($) {
				$('.mmp-dismissible').click(function(e) {
					if (!$(e.target).hasClass('notice-dismiss')) {
						return;
					}

					$.ajax({
						type: 'POST',
						url: ajaxurl,
						context: this,
						data: {
							action: 'mmp_dismiss_admin_notice',
							nonce: '<?= wp_create_nonce('mmp-dismiss-admin-notice') ?>',
							notice: $(this).data('notice')
						}
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Dismisses an admin notice
	 *
	 * @since 4.0
	 */
	public function dismiss_admin_notice() {
		if (!isset($_POST['nonce']) || wp_verify_nonce($_POST['nonce'], 'mmp-dismiss-admin-notice') === false) {
			wp_die();
		}

		if (!isset($_POST['notice'])) {
			wp_die();
		}

		$this->remove_admin_notice($_POST['notice']);

		wp_die();
	}

	/**
	 * Adds an admin notice
	 *
	 * @since 4.0
	 *
	 * @param string $notice Admin notice index
	 */
	public function add_admin_notice($notice) {
		$notices = get_option('mapsmarkerpro_notices');
		if (!is_array($notices)) {
			$notices = array();
		}

		$key = array_search($notice, $notices);
		if ($key === false) {
			$notices[] = $notice;
			update_option('mapsmarkerpro_notices', $notices);
		}
	}

	/**
	 * Removes an admin notice
	 *
	 * @since 4.0
	 *
	 * @param string $notice Admin notice index
	 */
	public function remove_admin_notice($notice) {
		$notices = get_option('mapsmarkerpro_notices');
		if (!is_array($notices)) {
			$notices = array();
		}

		$key = array_search($notice, $notices);
		if ($key !== false) {
			unset($notices[$key]);
			update_option('mapsmarkerpro_notices', $notices);
		}
	}

	/**
	 * Retrieves an admin notice
	 *
	 * @since 4.0
	 *
	 * @param string $notice Admin notice index
	 */
	private function get_notice($notice) {
		$l10n = MMP::get_instance('MMP\L10n');

		$notices = array(
			'finish_install' => array(
				'level' => 'info',
				'msg'   => '<a href="' . get_admin_url(null, 'admin.php?page=mapsmarkerpro_license') . '"><img style="width: 50px; height: 50px; margin-right: 10px; vertical-align: middle;" src="' . plugins_url('images/logo-mapsmarker-pro.svg', __DIR__) . '" />' . esc_html__('Please click here to finish the installation of Maps Marker Pro.', 'mmp') . '</a>'
			),
			'new_install' => array(
				'level' => 'info',
				'msg'   => esc_html__('Installation finished - you can now start creating maps!', 'mmp') . ' (<a href="https://www.mapsmarker.com/starter-guide/" target="_blank">' . esc_html__('open starter guide', 'mmp') . '</a>)<br />' . sprintf($l10n->kses__('We recommend using OpenStreetMap, but if you also want to use Google Maps, you need to register a <a href="%1$s" target="_blank">Google Maps Javascript API key</a>.', 'mmp'), 'https://www.mapsmarker.com/google-maps-javascript-api/')
			),
			'migration_ok' => array(
				'level' => 'info',
				'msg'   => sprintf(esc_html__('An installation of Maps Marker Pro %1$s was detected.', 'mmp'), '3.1.1') . '<br />' . sprintf($l10n->kses__('You can copy your existing maps to this version using the <a href="%1$s">data migration tool</a>.', 'mmp'), get_admin_url(null, 'admin.php?page=mapsmarkerpro_tools#migration'))
			),
			'migration_update' => array(
				'level' => 'info',
				'msg'   => esc_html__('An older installation of Maps Marker Pro was detected.', 'mmp') . '<br />' . sprintf($l10n->kses__('If you want to copy your existing maps to this version, you need to update the old Maps Marker Pro installation to version %1$s first. For more information, please see the <a href="%2$s">data migration tool</a>.', 'mmp'), '3.1.1', get_admin_url(null, 'admin.php?page=mapsmarkerpro_tools#migration'))
			)
		);

		return (isset($notices[$notice])) ? $notices[$notice] : false;
	}
}
