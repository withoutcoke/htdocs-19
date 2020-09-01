<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Shortcodes {
	/**
	 * List of active shortcodes
	 *
	 * @since 4.0
	 * @var array
	 */
	private $shortcodes;

	/**
	 * Sets up the class
	 *
	 * @since 4.0
	 */
	public function __construct() {
		$this->shortcodes = array();
	}

	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		add_action('wp_enqueue_scripts', array($this, 'load_resources'));
		add_action('init', array($this, 'add_shortcodes'));
		add_action('wp_footer', array($this, 'load_shortcodes'), 21);
	}

	/**
	 * Loads the required resources
	 *
	 * @since 4.6
	 */
	public function load_resources() {
		wp_enqueue_style('mapsmarkerpro');
		if (is_rtl()) {
			wp_enqueue_style('mapsmarkerpro-rtl');
		}
	}

	/**
	 * Adds the shortcodes
	 *
	 * @since 4.0
	 */
	public function add_shortcodes() {
		add_shortcode(MMP::$settings['shortcode'], array($this, 'map_shortcode'));
	}

	/**
	 * Processes the map shortcode
	 *
	 * @since 4.0
	 *
	 * @param array $atts Attributes used in the shortcode
	 */
	public function map_shortcode($atts) {
		global $wp;
		$db = MMP::get_instance('MMP\DB');
		$mmp_settings = MMP::get_instance('MMP\Settings');
		$api = MMP::get_instance('MMP\API');
		$l10n = MMP::get_instance('MMP\L10n');

		// Backwards compatibility for layer attribute
		if (isset($atts['layer'])) {
			$atts['map'] = $atts['layer'];
		}
		// Backwards compatibility for highlightmarker attribute
		if (isset($atts['highlightmarker'])) {
			$atts['highlight'] = $atts['highlightmarker'];
		}

		if (isset($atts['map'])) {
			$type = 'map';
			$id = absint($atts['map']);
		} else if (isset($atts['marker'])) {
			$type = 'marker';
			$id = absint($atts['marker']);
		} else if (isset($atts['custom'])) {
			$type = 'custom';
			$id = absint($atts['custom']);
		} else {
			$type = false;
			$id = false;
		}

		if (!$type || !$id) {
			return $this->error(esc_html__('Error: map could not be loaded - invalid shortcode. Please contact the site owner.', 'mmp'));
		}

		if ($type === 'map' || $type === 'custom') {
			$map = $db->get_map($id);
			if (!$map) {
				return $this->error(sprintf(esc_html__('Error: map could not be loaded - a map with ID %1$s does not exist. Please contact the site owner.', 'mmp'), $id));
			}
		} else if ($type === 'marker') {
			$marker = $db->get_marker($id);
			if (!$marker) {
				return $this->error(sprintf(esc_html__('Error: map could not be loaded - a marker with ID %1$s does not exist. Please contact the site owner.', 'mmp'), $id));
			}
		}

		if ($type === 'map' && (is_feed() || (function_exists('is_amp_endpoint') && is_amp_endpoint()))) {
			ob_start();
			?>
			<p>
				<?= esc_html($l10n->__($map->name, "Map (ID {$id}) name")) ?><br />
				<a href="<?= $api->link("/fullscreen/{$id}/") ?>" title="<?= esc_attr__('Embedded map - show in fullscreen mode', 'mmp') ?>">
					<img src="<?= plugins_url('images/map-rss-feed.png', __DIR__) ?>" width="304" height="197" /><br />
					<?= esc_html__('Embedded map - show in fullscreen mode', 'mmp') ?>
				</a>
			</p>
			<?php
			return ob_get_clean();
		}

		$uid = (isset($atts['uid'])) ? esc_js($atts['uid']) : substr(md5(rand()), 0, 8);
		$lazy = (isset($atts['lazy'])) ? ($atts['lazy'] === 'true') : MMP::$settings['lazyLoad'];
		$markers = (isset($atts['markers'])) ? '[' . $db->sanitize_ids($atts['markers'], true) . ']' : '[]';
		$highlight = (isset($atts['highlight'])) ? absint($atts['highlight']) : 'null';
		$fullscreen = (isset($atts['fullscreen'])) ? 'true' : 'false';
		$chart = (isset($atts['chart'])) ? $atts['chart'] : 'null';

		// Array containing map setting keys and their respective lowercase versions
		// Needed because WordPress converts shortcode attributes to lowercase
		// Also prevents issues when incorrect capitalization for overrides is used
		$allowed_settings = array();
		foreach (array_keys($mmp_settings->map_settings_sanity()) as $setting) {
			$allowed_settings[strtolower($setting)] = $setting;
		}

		// Array containing the correctly capitalized setting keys and overrides
		$settings = array();
		foreach ($atts as $key => $att) {
			if (isset($allowed_settings[$key])) {
				$settings[$allowed_settings[$key]] = $att;
			}
		}
		$settings = $mmp_settings->validate_map_settings($settings, true, true);

		if (MMP::$settings['googleApiKey']) {
			wp_enqueue_script('mmp-googlemaps');
		}
		wp_enqueue_script('mapsmarkerpro');

		$this->shortcodes[] = array(
			'uid'        => $uid,
			'type'       => $type,
			'id'         => $id,
			'lazy'       => $lazy,
			'markers'    => $markers,
			'overrides'  => json_encode($settings, JSON_FORCE_OBJECT),
			'language'   => ($l10n->ml) ? ICL_LANGUAGE_CODE : '',
			'highlight'  => $highlight,
			'fullscreen' => $fullscreen,
			'chart'      => $chart,
			'page'       => trailingslashit(home_url(add_query_arg(array(), $wp->request)))
		);

		return '<div id="maps-marker-pro-' . $uid . '" class="maps-marker-pro">' . esc_html__('Map loading, please wait ...', 'mmp') . '</div>';
	}

	/**
	 * Loads the active shortcodes
	 *
	 * @since 4.0
	 */
	public function load_shortcodes() {
		if (!count($this->shortcodes)) {
			return;
		}

		?>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				<?php foreach ($this->shortcodes as $shortcode): ?>
					new MapsMarkerPro({
						uid: '<?= $shortcode['uid'] ?>',
						type: '<?= $shortcode['type'] ?>',
						id: '<?= $shortcode['id'] ?>',
						lazy: '<?= $shortcode['lazy'] ?>',
						markers: <?= $shortcode['markers'] ?>,
						overrides: <?= $shortcode['overrides'] ?>,
						language: '<?= $shortcode['language'] ?>',
						highlight: <?= $shortcode['highlight'] ?>,
						fullscreen: <?= $shortcode['fullscreen'] ?>,
						chart: '<?= $shortcode['chart'] ?>',
						page: '<?= $shortcode['page'] ?>'
					});
				<?php endforeach; ?>
			});
		</script>
		<?php
	}

	/**
	 * Displays an error message if the shortcode is invalid
	 *
	 * @since 4.0
	 *
	 * @param string $message Message to be displayed
	 */
	private function error($message) {
		return '<div class="maps-marker-pro mmp-map-error">' . $message . '</div>';
	}
}
