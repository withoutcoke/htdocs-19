<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Resources {
	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		add_action('wp_enqueue_scripts', array($this, 'register_frontend_resources'));
		add_action('admin_enqueue_scripts', array($this, 'register_backend_resources'));
		add_action('wp_enqueue_media', array($this, 'register_media_resources'));
		add_action('enqueue_block_editor_assets', array($this, 'register_block_resources'));
	}

	/**
	 * Registers the resources used on the front end
	 *
	 * @since 4.0
	 */
	public function register_frontend_resources() {
		$l10n = MMP::get_instance('MMP\L10n');

		wp_register_style('mapsmarkerpro', plugins_url('css/mapsmarkerpro.css', __DIR__), array(), MMP::$version);
		wp_register_style('mapsmarkerpro-rtl', plugins_url('css/mapsmarkerpro-rtl.css', __DIR__), array('mapsmarkerpro'), MMP::$version);

		wp_register_script('mmp-googlemaps', $this->get_google_maps_url(), array(), null, true);
		wp_register_script('mapsmarkerpro', plugins_url('js/mapsmarkerpro.js', __DIR__), array(), MMP::$version, true);
		wp_localize_script('mapsmarkerpro', 'ajaxurl', get_admin_url(null, 'admin-ajax.php'));
		wp_localize_script('mapsmarkerpro', 'mmpVars', $this->get_plugin_vars());
		wp_localize_script('mapsmarkerpro', 'mmpL10n', $l10n->map_strings());
		if (MMP::$settings['customJs']) {
			wp_add_inline_script('mapsmarkerpro', MMP::$settings['customJs']);
		}
	}

	/**
	 * Registers the resources used on the back end
	 *
	 * @since 4.0
	 */
	public function register_backend_resources() {
		$l10n = MMP::get_instance('MMP\L10n');

		wp_register_style('mapsmarkerpro', plugins_url('css/mapsmarkerpro.css', __DIR__), array(), MMP::$version);
		wp_register_style('mapsmarkerpro-rtl', plugins_url('css/mapsmarkerpro-rtl.css', __DIR__), array(), MMP::$version);
		wp_register_style('mmp-flatpickr', plugins_url('css/flatpickr.css', __DIR__), array(), MMP::$version);
		wp_register_style('mmp-admin', plugins_url('css/admin.css', __DIR__), array(), MMP::$version);
		wp_register_style('mmp-admin-rtl', plugins_url('css/admin-rtl.css', __DIR__), array(), MMP::$version);
		wp_register_style('mmp-leaflet-geoman', plugins_url('css/leaflet-geoman.css', __DIR__), array(), MMP::$version);
		wp_register_style('mmp-dashboard', plugins_url('css/dashboard.css', __DIR__), array(), MMP::$version);

		wp_register_script('mmp-googlemaps', $this->get_google_maps_url(), array(), null, true);
		wp_register_script('mapsmarkerpro', plugins_url('js/mapsmarkerpro.js', __DIR__), array(), MMP::$version, true);
		wp_localize_script('mapsmarkerpro', 'mmpVars', $this->get_plugin_vars());
		wp_localize_script('mapsmarkerpro', 'mmpL10n', $l10n->map_strings());
		wp_register_script('mmp-flatpickr', plugins_url('js/flatpickr.js', __DIR__), array('jquery'), MMP::$version, true);
		wp_register_script('mmp-admin', plugins_url('js/admin.js', __DIR__), array('jquery', 'jquery-ui-sortable'), MMP::$version, true);
		wp_localize_script('mmp-admin', 'mmpAdminVars', $this->get_plugin_vars());
		wp_localize_script('mmp-admin', 'mmpGeocoding', $this->geocoding_settings());
		wp_localize_script('mmp-admin', 'mmpAdminL10n', $l10n->admin_strings());
		wp_register_script('mmp-leaflet-geoman', plugins_url('js/leaflet-geoman.js', __DIR__), array('mapsmarkerpro'), MMP::$version, true);
		wp_register_script('mmp-dashboard', plugins_url('js/dashboard.js', __DIR__), array('jquery'), MMP::$version, true);
	}

	/**
	 * Registers the resources used for the TinyMCE editor
	 *
	 * @since 4.0
	 */
	public function register_media_resources() {
		wp_register_style('mmp-shortcode', plugins_url('css/shortcode.css', __DIR__), array(), MMP::$version);

		wp_register_script('mmp-shortcode', plugins_url('js/shortcode.js', __DIR__), array('jquery'), MMP::$version, true);
	}

	/**
	 * Registers the resources used for the Gutenberg block editor
	 *
	 * @since 4.3
	 */
	public function register_block_resources() {
		$l10n = MMP::get_instance('MMP\L10n');

		wp_register_style('mmp-gb-block', plugins_url('css/block.css', __DIR__), array('wp-edit-blocks'), MMP::$version);

		wp_register_script('mmp-gb-block', plugins_url('js/block.js', __DIR__), array('wp-blocks', 'wp-element'), MMP::$version, true);
		wp_localize_script('mmp-gb-block', 'mmpGbVars', $this->gb_vars());
		wp_localize_script('mmp-gb-block', 'mmpGbL10n', $l10n->gb_strings());

		register_block_type('mmp/map', array(
			'editor_style'  => 'mmp-gb-block',
			'editor_script' => 'mmp-gb-block'
		));
	}

	/**
	 * Returns the URL for the Google Maps API
	 *
	 * @since 4.0
	 */
	private function get_google_maps_url() {
		$google_maps_url = 'https://maps.googleapis.com/maps/api/js?key=' . MMP::$settings['googleApiKey'];
		if (MMP::$settings['googleLanguage'] !== 'browser_setting') {
			$google_maps_url .= '&language=';
			if (MMP::$settings['googleLanguage'] === 'wordpress_setting') {
				$google_maps_url .= substr(get_locale(), 0, 2);
			} else {
				$google_maps_url .= MMP::$settings['googleLanguage'];
			}
		}

		return $google_maps_url;
	}

	/**
	 * Returns the plugin vars needed for JavaScript
	 *
	 * @since 4.0
	 */
	private function get_plugin_vars() {
		return array(
			'baseUrl'   => API::$base_url,
			'slug'      => API::$slug,
			'apiUrl'    => API::$base_url . API::$slug . '/',
			'adminUrl'  => get_admin_url(),
			'pluginUrl' => plugins_url('/', __DIR__),
			'iconsUrl'  => MMP::$icons_url,
			'shortcode' => MMP::$settings['shortcode']
		);
	}

	/**
	 * Returns the geocoding settings needed for JavaScript
	 *
	 * @since 4.0
	 */
	private function geocoding_settings() {
		$locale = get_locale();

		if (MMP::$settings['geocodingPhotonLanguage'] === 'automatic') {
			$photon_language = strtolower(substr($locale, 0, 2));
			if (!in_array($photon_language, array('de', 'fr', 'it'))) {
				$photon_language = 'en';
			}
		} else {
			$photon_language = MMP::$settings['geocodingPhotonLanguage'];
		}

		$tomtom_language = (MMP::$settings['geocodingTomTomLanguage']) ? MMP::$settings['geocodingTomTomLanguage'] : str_replace('_', '-', get_locale());
		if (!in_array($tomtom_language, array('NGT', 'NGT-Latn', 'af-ZA', 'ar', 'eu-ES', 'bg-BG', 'ca-ES', 'zh-CN', 'zh-TW', 'cs-CZ', 'da-DK', 'nl-BE', 'nl-NL', 'en-AU', 'en-NZ', 'en-GB', 'en-US', 'et-EE', 'fi-FI', 'fr-CA', 'fr-FR', 'gl-ES', 'de-DE', 'el-GR', 'hr-HR', 'he-IL', 'hu-HU', 'id-ID', 'it-IT', 'kk-KZ', 'lv-LV', 'lt-LT', 'ms-MY', 'No-NO', 'nb-NO', 'pl-PL', 'pt-BR', 'pt-PT', 'ro-RO', 'ru-RU', 'ru-Latn-RU', 'ru-Cyrl-RU', 'sr-RS', 'sk-SK', 'sl-SI', 'es-ES', 'es-419', 'sv-SE', 'th-TH', 'tr-TR', 'uk-UA', 'vi-VN'))) {
			$tomtom_language = '';
		}

		$footer_tips = '<a href="https://mapsmarker.com/geocoding-optimization/" target="_blank" title="' . esc_attr__('Show tutorial at mapsmarker.com', 'mmp') . '">' . esc_html__('Tip: adjust geocoding settings for more targeted search results', 'mmp') . '</a><br />';

		$settings = array(
			'algolia' => array(
				'appId'             => MMP::$settings['geocodingAlgoliaAppId'],
				'apiKey'            => MMP::$settings['geocodingAlgoliaApiKey'],
				'language'          => (MMP::$settings['geocodingAlgoliaLanguage']) ? MMP::$settings['geocodingAlgoliaLanguage'] : substr($locale, 0, 2),
				'countries'         => MMP::$settings['geocodingAlgoliaCountries'],
				'aroundLatLngViaIP' => MMP::$settings['geocodingAlgoliaAroundLatLngViaIp'],
				'aroundLatLng'      => MMP::$settings['geocodingAlgoliaAroundLatLng'],
				'footer'            => '<div class="ap-footer">' . $footer_tips . 'Built by <a href="https://www.mapsmarker.com/algolia-places/" target="_blank" title="Search by Algolia" class="ap-footer-algolia"></a> using <a href="https://community.algolia.com/places/documentation.html#license" class="ap-footer-osm" target="_blank" title="Algolia Places data &copy; OpenStreetMap contributors"> data</a></div>'
			),
			'photon' => array(
				'language'        => $photon_language,
				'locationbiaslat' => MMP::$settings['geocodingPhotonBiasLat'],
				'locationbiaslon' => MMP::$settings['geocodingPhotonBiasLon'],
				'bounds'          => MMP::$settings['geocodingPhotonBounds'],
				'lat1'            => MMP::$settings['geocodingPhotonBoundsLat1'],
				'lon1'            => MMP::$settings['geocodingPhotonBoundsLon1'],
				'lat2'            => MMP::$settings['geocodingPhotonBoundsLat2'],
				'lon2'            => MMP::$settings['geocodingPhotonBoundsLon2'],
				'filter'          => MMP::$settings['geocodingPhotonFilter'],
				'footer'          => '<div class="ap-footer">' . $footer_tips . '<div style="float:right;"><a href="https://www.mapsmarker.com/photon/" target="_blank"><img src="' . plugins_url('images/geocoding/photon-mapsmarker-small.png', __DIR__) . '" width="144" height="23"/></a></div><div style="float:right;margin:4px 5px 0 0;"><a href="https://www.mapsmarker.com/photon/" target="_blank">' . esc_html__('Powered by', 'mmp') . '</a></div></div>'
			),
			'locationiq' => array(
				'apiKey'   => MMP::$settings['geocodingLocationIqApiKey'],
				'bounds'   => MMP::$settings['geocodingLocationIqBounds'],
				'lat1'     => MMP::$settings['geocodingLocationIqBoundsLat1'],
				'lon1'     => MMP::$settings['geocodingLocationIqBoundsLon1'],
				'lat2'     => MMP::$settings['geocodingLocationIqBoundsLat2'],
				'lon2'     => MMP::$settings['geocodingLocationIqBoundsLon2'],
				'language' => (MMP::$settings['geocodingLocationIqLanguage']) ? MMP::$settings['geocodingLocationIqLanguage'] : substr($locale, 0, 2),
				'footer'   => '<div class="ap-footer">' . $footer_tips . '<a href="https://www.mapsmarker.com/locationiq-geocoding/" target="_blank">' . esc_html__('Powered by', 'mmp') . ' LocationIQ</a></div>'
			),
			'mapquest'        => array(
				'api_key'     => MMP::$settings['geocodingMapQuestApiKey'],
				'boundingBox' => MMP::$settings['geocodingMapQuestBounds'],
				'lat1'        => MMP::$settings['geocodingMapQuestBoundsLat1'],
				'lon1'        => MMP::$settings['geocodingMapQuestBoundsLon1'],
				'lat2'        => MMP::$settings['geocodingMapQuestBoundsLat2'],
				'lon2'        => MMP::$settings['geocodingMapQuestBoundsLon2'],
				'footer'      => '<div class="ap-footer">' . $footer_tips . '<div style="float:right;"><a href="https://www.mapsmarker.com/mapquest-geocoding/" target="_blank"><img src="' . plugins_url('images/geocoding/mapquest-logo-small.png', __DIR__) . '" width="144" height="26"/></a></div><div style="float:right;margin:6px 5px 0 0;"><a href="https://www.mapsmarker.com/mapquest-geocoding/" target="_blank">' . esc_html__('Powered by', 'mmp') . '</a></div></div>'
			),
			'google' => array(
				'nonce'  => wp_create_nonce('mmp-google-places'),
				'footer' => '<div class="ap-footer">' . $footer_tips . '<a href="https://www.mapsmarker.com/google-geocoding/" target="_blank"><img src="' . plugins_url('images/geocoding/powered-by-google.png', __DIR__) . '" width="144" height="18" /></a></div>'
			),
			'tomtom' => array(
				'apiKey'     => MMP::$settings['geocodingTomTomApiKey'],
				'lat'        => MMP::$settings['geocodingTomTomLat'],
				'lon'        => MMP::$settings['geocodingTomTomLon'],
				'radius'     => MMP::$settings['geocodingTomTomRadius'],
				'language'   => $tomtom_language,
				'countrySet' => MMP::$settings['geocodingTomTomCountrySet'],
				'footer'     => '<div class="ap-footer">' . $footer_tips . '<div style="float:right;"><a href="https://www.mapsmarker.com/tomtom-geocoding/" target="_blank"><img src="' . plugins_url('images/geocoding/tomtom-logo.png', __DIR__) . '" width="99" height="26"/></a></div><div style="float:right;margin:6px 5px 0 0;"><a href="https://www.mapsmarker.com/tomtom-geocoding/" target="_blank">' . esc_html__('Powered by', 'mmp') . '</a></div></div>'
			),
			'header' => esc_html__('To select a location, please click on a result or press', 'mmp')
		);

		return $settings;
	}

	/**
	 * Returns the Gutenberg vars needed for JavaScript
	 *
	 * @since 4.3
	 */
	private function gb_vars() {
		$db = MMP::get_instance('MMP\DB');

		$maps = $db->get_all_maps(false, array(
			'orderby'   => 'id',
			'sortorder' => 'desc'
		));
		$data = array();
		foreach ($maps as $map) {
			$data[] = array(
				'id'   => $map->id,
				'name' => "[ID {$map->id}] " . (($map->name) ? esc_html($map->name) : esc_html__('(no name)', 'mmp'))
			);
		}

		return array(
			'iconUrl'   => plugins_url('images/logo-mapsmarker-pro.svg', __DIR__),
			'shortcode' => MMP::$settings['shortcode'],
			'maps'      => $data
		);
	}
}
