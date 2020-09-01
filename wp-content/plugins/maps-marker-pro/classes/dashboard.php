<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Dashboard {
	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		if (!MMP::$settings['dashboardWidget']) {
			return;
		}

		add_action('admin_enqueue_scripts', array($this, 'load_resources'));
		add_action('wp_ajax_mmp_dashboard_rss', array($this, 'get_rss'));
		add_action('wp_dashboard_setup', array($this, 'add_widget'));
		add_action('wp_network_dashboard_setup', array($this, 'add_widget'));
	}

	/**
	 * Loads the required resources
	 *
	 * @since 4.0
	 *
	 * @param string $hook Name of the current admin page
	 */
	public function load_resources($hook) {
		if ($hook !== 'index.php') {
			return;
		}

		wp_enqueue_style('mmp-dashboard');
		wp_enqueue_script('mmp-dashboard');
	}

	/**
	 * Retrieves the RSS feed
	 *
	 * @since 4.0
	 */
	public function get_rss() {
		$l10n = MMP::get_instance('MMP\L10n');

		if (!isset($_POST['nonce']) || wp_verify_nonce($_POST['nonce'], 'mmp-dashboard') === false) {
			wp_send_json(array(
				'success'  => false,
				'response' => '<p>' . esc_html__('Security check failed', 'mmp') . '</p>'
			));
		}

		require_once(ABSPATH . WPINC . '/class-simplepie.php');

		$feed = new \SimplePie();
		$feed->set_feed_url('https://www.mapsmarker.com/feed/');
		if (is_dir(MMP::$cache_dir) && is_writable(MMP::$cache_dir)) {
			$feed->enable_cache(true);
			$feed->set_cache_location(MMP::$cache_dir);
			$feed->set_cache_duration(86400);
		} else {
			$feed->enable_cache(false);
		}
		$feed->set_stupidly_fast(true);
		$feed->enable_order_by_date(true);
		$feed->init();
		$feed->handle_content_type();

		if ($feed->error) {
			wp_send_json(array(
				'success'  => false,
				'response' => '<p>' . sprintf(esc_html__('Feed could not be retrieved, please try again later or read the latest blog posts at %1$s.', 'mmp'), '<a href="https://www.mapsmarker.com/news/" target="_blank">https://www.mapsmarker.com/news/</a>') . '</p>'
			));
		}

		ob_start();
		?>
		<ul>
			<?php foreach ($feed->get_items(0, 3) as $item): ?>
				<li><?= $l10n->date('date', $item->get_date('Y-m-d H:i:s')) ?>: <a href="<?= esc_url($item->get_permalink()) ?>?ref=dashboard"><?= esc_html(wp_strip_all_tags($item->get_title())) ?></a>
			<?php endforeach; ?>
		</ul>
		<?php
		$rss = ob_get_clean();

		wp_send_json(array(
			'success'  => true,
			'response' => $rss
		));
	}

	/**
	 * Adds the dashboard widget
	 *
	 * @since 4.0
	 */
	public function add_widget() {
		if (MMP::$settings['whitelabelBackend']) {
			$prefix = esc_html__('Maps', 'mmp');
		} else {
			$prefix = 'Maps Marker Pro';
		}

		wp_add_dashboard_widget(
			'mmp-dashboard-widget',
			$prefix . ' - ' . esc_html__('recent markers', 'mmp'),
			array($this, 'dashboard_widget'),
			array($this, 'dashboard_widget_control')
		);
	}

	/**
	 * Displays the dashboard widget
	 *
	 * @since 4.0
	 */
	public function dashboard_widget() {
		global $wpdb;
		$license = MMP::get_instance('MMP\License');
		$db = MMP::get_instance('MMP\DB');
		$l10n = MMP::get_instance('MMP\L10n');

		$latest_version = get_transient('mapsmarkerpro_latest');
		$widgets = get_option('dashboard_widget_options');
		$options = array(
			'markers' => (isset($widgets['mmp-dashboard-widget']['markers'])) ? absint($widgets['mmp-dashboard-widget']['markers']) : 5,
			'blog'    => (isset($widgets['mmp-dashboard-widget']['blog'])) ? !!$widgets['mmp-dashboard-widget']['blog'] : true
		);
		$markers = $db->get_all_markers(array(
			'orderby'   => 'id',
			'sortorder' => 'desc',
			'limit'     => $options['markers']
		));

		?>
		<input type="hidden" id="mmp-dashboard-nonce" value="<?= wp_create_nonce('mmp-dashboard') ?>" />
		<?php if (!$license->check_for_updates(true)): ?>
			<div class="mmp-dashboard-invalid">
				<?= sprintf(esc_html__('Warning: your license is invalid!', 'mmp'), MMP::$version) ?><br />
				<a href="<?= get_admin_url(null, 'admin.php?page=mapsmarkerpro_license') ?>"><?= esc_html__('Please go to the license page for more info.', 'mmp') ?></a>
			</div>
		<?php else: ?>
			<?php if (!$license->check_for_updates()): ?>
				<div class="mmp-dashboard-expired">
					<?= esc_html__('Warning: your access to updates and support for Maps Marker Pro has expired!', 'mmp') ?><br />
					<?php if ($latest_version !== false && !version_compare(MMP::$version, $latest_version, '>=')): ?>
						<?= esc_html__('Latest available version:', 'mmp') ?> <a href="https://www.mapsmarker.com/v<?= $latest_version ?>" target="_blank" title="<?= esc_attr__('Show release notes', 'mmp') ?>"><?= $latest_version ?></a> (<a href="https://www.mapsmarker.com/changelog/pro/" target="_blank"><?= esc_html__('show all available changelogs', 'mmp') ?></a>)<br />
					<?php endif; ?>
					<?= sprintf(esc_html__('You can continue using version %1$s without any limitations. However, you will not be able access the support system or get updates including bugfixes, new features and optimizations.', 'mmp'), MMP::$version) ?><br />
					<a href="<?= get_admin_url(null, 'admin.php?page=mapsmarkerpro_license') ?>"><?= esc_html__('Please renew your access to updates and support to keep your plugin up-to-date and safe.', 'mmp') ?></a>
				</div>
			<?php endif; ?>
			<hr class="mmp-dashboard-separator" />
			<div class="mmp-dashboard-markers">
				<?php if (!count($markers)): ?>
					<p><?= esc_html__('No markers found', 'mmp') ?></p>
				<?php else: ?>
					<ul>
						<?php foreach ($markers as $marker): ?>
							<li>
								<span>
									<a href="<?= get_admin_url(null, "admin.php?page=mapsmarkerpro_marker&id={$marker->id}") ?>" title="<?= esc_attr__('Edit marker', 'mmp') ?>">
										<img src="<?= ($marker->icon) ? MMP::$icons_url . $marker->icon : plugins_url('images/leaflet/marker.png', __DIR__) ?>" />
									</a>
								</span>
								<span>
									<a href="<?= get_admin_url(null, "admin.php?page=mapsmarkerpro_marker&id={$marker->id}") ?>" title="<?= esc_attr__('Edit marker', 'mmp') ?>">
										<?= ($marker->name) ? esc_html($marker->name) : esc_html__('(no name)', 'mmp') ?>
									</a><br />
									<?= esc_html__('created on', 'mmp') ?> <?= $l10n->date('datetime', $marker->created_on) ?>, <?= esc_html__('created by', 'mmp') ?> <?= esc_html($marker->created_by) ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if (!MMP::$settings['whitelabelBackend'] && $options['blog']): ?>
			<hr class="mmp-dashboard-separator" />
			<div class="mmp-dashboard-blog">
				<p><?= esc_html__('Latest blog posts from www.mapsmarker.com', 'mmp') ?></p>
				<div id="mmp_blog_posts">
					<img src="<?= plugins_url('images/paging-ajax-loader.gif', __DIR__) ?>" />
				</div>
			</div>
			<hr class="mmp-dashboard-separator" />
			<div class="mmp-dashboard-links">
				<a href="https://www.mapsmarker.com/" target="_blank">
					<img src="<?= plugins_url('images/icons/website-home.png', __DIR__) ?>" /> MapsMarker.com
				</a>
				<a href="https://affiliates.mapsmarker.com/" target="_blank" title="<?= esc_attr__('MapsMarker affiliate program - sign up now and receive commissions up to 50%!', 'mmp') ?>">
					<img src="<?= plugins_url('images/icons/affiliates.png', __DIR__) ?>" /> <?= esc_html__('Affiliates', 'mmp') ?>
				</a>
				<a href="https://www.mapsmarker.com/reseller/" target="_blank" title="<?= esc_attr__('MapsMarker reseller program - re-sell with a 20% discount!', 'mmp') ?>">
					<img src="<?= plugins_url('images/icons/resellers.png', __DIR__) ?>" /> <?= esc_html__('Resellers', 'mmp') ?>
				</a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Displays and handles the dashboard widget settings
	 *
	 * @since 4.0
	 */
	public function dashboard_widget_control() {
		$widgets = get_option('dashboard_widget_options');
		$options = array(
			'markers' => (isset($widgets['mmp-dashboard-widget']['markers'])) ? absint($widgets['mmp-dashboard-widget']['markers']) : 5,
			'blog'    => (isset($widgets['mmp-dashboard-widget']['blog'])) ? !!$widgets['mmp-dashboard-widget']['blog'] : true
		);

		if (isset($_POST['mmp-dashboard-widget-control'])) {
			if (isset($_POST['mmp-dashboard-widget-control']['markers'])) {
				$options['markers'] = absint($_POST['mmp-dashboard-widget-control']['markers']);
			}
			if (!MMP::$settings['whitelabelBackend']) {
				$options['blog'] = isset($_POST['mmp-dashboard-widget-control']['blog']);
			}
			$widgets['mmp-dashboard-widget'] = $options;
			update_option('dashboard_widget_options', $widgets);
		}

		?>
		<p>
			<label>
				<?= esc_html__('Number of markers to show:', 'mmp') ?>
				<input type="number" name="mmp-dashboard-widget-control[markers]" min="0" max="100" value="<?= $options['markers'] ?>" />
			</label>
		</p>
		<?php if (!MMP::$settings['whitelabelBackend']): ?>
			<p>
				<label>
					<?= esc_html__('Show blog posts and link section:', 'mmp') ?>
					<input type="checkbox" name="mmp-dashboard-widget-control[blog]" <?php checked($options['blog']) ?> />
				</label>
			</p>
		<?php endif; ?>
		<?php
	}
}
