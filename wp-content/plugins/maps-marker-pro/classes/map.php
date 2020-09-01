<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Map {
	/**
	 * Registers the hooks
	 *
	 * @since 4.0
	 */
	public function init() {
		add_filter('mmp_popup', array($this, 'popup'));

		add_action('wp_ajax_mmp_map_markers', array($this, 'map_markers'));
		add_action('wp_ajax_nopriv_mmp_map_markers', array($this, 'map_markers'));
		add_action('wp_ajax_mmp_map_geojson', array($this, 'map_geojson'));
		add_action('wp_ajax_nopriv_mmp_map_geojson', array($this, 'map_geojson'));
		add_action('wp_ajax_mmp_marker_settings', array($this, 'marker_settings'));
		add_action('wp_ajax_nopriv_mmp_marker_settings', array($this, 'marker_settings'));
	}

	/**
	 * Modifies the CSS styles considered safe by KSES
	 *
	 * @since 4.8
	 *
	 * @param array $styles Current safe CSS styles
	 */
	public function kses_css($styles) {
		$styles[] = 'display';

		return $styles;
	}

	/**
	 * Prepares the popup content for output
	 *
	 * @since 4.0
	 *
	 * @param string $popup Current popup content
	 */
	public function popup($popup) {
		global $wp_embed, $allowedposttags;

		$popup = $wp_embed->run_shortcode($popup);
		$popup = $wp_embed->autoembed($popup);
		$popup = do_shortcode($popup);
		if (MMP::$settings['popupKses']) {
			add_filter('safe_style_css', array($this, 'kses_css'));
			$additionaltags = array(
				'iframe' => array(
					'id' => true,
					'name' => true,
					'src' => true,
					'class' => true,
					'style' => true,
					'frameborder' => true,
					'scrolling' => true,
					'align' => true,
					'width' => true,
					'height' => true,
					'marginwidth' => true,
					'marginheight' => true,
					'allowfullscreen' => true
				),
				'style' => array(
					'media' => true,
					'scoped' => true,
					'type' => true
				),
				'form' => array(
					'action' => true,
					'accept' => true,
					'accept-charset' => true,
					'enctype' => true,
					'method' => true,
					'name' => true,
					'target' => true
				),
				'input' => array(
					'accept' => true,
					'align' => true,
					'alt' => true,
					'autocomplete' => true,
					'autofocus' => true,
					'checked' => true,
					'dirname' => true,
					'disabled' => true,
					'form' => true,
					'formaction' => true,
					'formenctype' => true,
					'formmethod' => true,
					'formnovalidate' => true,
					'formtarget' => true,
					'height' => true,
					'id' => true,
					'list' => true,
					'max' => true,
					'maxlength' => true,
					'min' => true,
					'multiple' => true,
					'name' => true,
					'pattern' => true,
					'placeholder' => true,
					'readonly' => true,
					'required' => true,
					'size' => true,
					'src' => true,
					'step' => true,
					'type' => true,
					'value' => true,
					'width' => true
				),
				'source' => array(
					'type' => true,
					'src' => true
				)
			);
			$popup = wp_kses($popup, array_merge($allowedposttags, $additionaltags));
		}

		return wpautop($popup);
	}

	/**
	 * AJAX request for retrieving the map settings
	 *
	 * @since 4.0
	 *
	 * @param string $type Map type
	 * @param int $id Map ID
	 * @param string $lang Map language
	 */
	public function map_settings($type, $id, $lang) {
		$db = MMP::get_instance('MMP\DB');
		$l10n = MMP::get_instance('MMP\L10n');
		$mmp_settings = MMP::get_instance('MMP\Settings');

		$current_user = wp_get_current_user();

		if (!is_admin()) {
			do_action('wpml_switch_language', $lang);
		}

		if ($type === 'map' || $type === 'custom') {
			if ($id) {
				$map = $db->get_map($id);
				$settings = $mmp_settings->validate_map_settings(json_decode($map->settings, true));
				$settings['panelEdit'] = $map->created_by_id == $current_user->ID || current_user_can('mmp_edit_other_maps');
				$settings['name'] = esc_html($l10n->__($map->name, "Map (ID {$id}) name"));
				if ($type === 'map') {
					$settings['filtersDetails'] = json_decode($map->filters, true);
					// Translates filters if the name is the same as that of the map
					foreach ($settings['filtersDetails'] as $map => $filter) {
						$settings['filtersDetails'][$map]['name'] = $l10n->__($settings['filtersDetails'][$map]['name'], "Map (ID {$map}) name");
					}
				} else {
					$settings['filtersDetails'] = array();
				}
			} else {
				$settings = $mmp_settings->get_map_defaults();
				$settings['panelEdit'] = false;
				$settings['name'] = '';
				$settings['filtersDetails'] = array();
			}
		} else if ($type === 'marker') {
			if ($id) {
				$marker = $db->get_marker($id);
				$settings = $mmp_settings->get_map_defaults();
				$settings['lat'] = $marker->lat;
				$settings['lng'] = $marker->lng;
				$settings['zoom'] = $marker->zoom;
				$settings['name'] = esc_html($l10n->__($marker->name, "Marker (ID {$id}) name"));
				$settings['filtersDetails'] = array();
			}
		} else {
			wp_send_json(array(
				'success'  => false,
				'response' => esc_html__('Error: map could not be loaded - invalid type. Please contact the site owner.', 'mmp')
			));
		}

		$settings['availableBasemaps'] = $mmp_settings->get_basemaps();
		$settings['availableOverlays'] = $mmp_settings->get_overlays();

		$settings['errorTileUrl'] = plugins_url('images/error-tile-image.png', __DIR__);
		$settings['basemapBingCulture'] = (MMP::$settings['bingCulture'] === 'automatic') ? str_replace('_', '-', get_locale()) : MMP::$settings['bingCulture'];
		$settings['basemapGoogleStyles'] = json_decode($settings['basemapGoogleStyles']);

		if (MMP::$settings['backlinks']) {
			if (MMP::$settings['affiliateId'] === '') {
				$suffix = 'welcome';
			} else {
				$suffix = MMP::$settings['affiliateId'] . '.html';
			}
			$prefix = '<a href="https://www.mapsmarker.com/' . $suffix . '" target="_blank" title="' . esc_attr__('Maps Marker Pro - #1 mapping plugin for WordPress', 'mmp') . '">MapsMarker.com</a> (<a href="http://www.leafletjs.com" target="_blank" title="' . sprintf(esc_attr__('%1$s is based on Leaflet.js maintained by Vladimir Agafonkin', 'mmp'), 'Maps Marker Pro') . '">Leaflet</a>/<a href="https://mapicons.mapsmarker.com" target="_blank" title="' . sprintf(esc_attr__('%1$s uses icons from the Maps Icons Collection maintained by Nicolas Mollet', 'mmp'), 'Maps Marker Pro') . '">Icons</a>)';
		} else {
			$prefix = '';
		}

		if (MMP::$settings['googleLanguage'] === 'browser_setting') {
			$google_language = '';
		} else if (MMP::$settings['googleLanguage'] === 'wordpress_setting') {
			$google_language = substr(get_locale(), 0, 2);
		} else {
			$google_language = MMP::$settings['googleLanguage'];
		}

		$globals = array(
			'language' => ($l10n->ml) ? ICL_LANGUAGE_CODE : false,
			'apiFullscreen' => MMP::$settings['apiFullscreen'],
			'apiExport' => MMP::$settings['apiExport'],
			'attributionPrefix' => $prefix,
			'directionsProvider' => MMP::$settings['directionsProvider'],
			'directionsGoogleType' => MMP::$settings['directionsGoogleType'],
			'directionsGoogleTraffic' => MMP::$settings['directionsGoogleTraffic'],
			'directionsGoogleUnits' => MMP::$settings['directionsGoogleUnits'],
			'directionsGoogleAvoidHighways' => MMP::$settings['directionsGoogleAvoidHighways'],
			'directionsGoogleAvoidTolls' => MMP::$settings['directionsGoogleAvoidTolls'],
			'directionsGooglePublicTransport' => MMP::$settings['directionsGooglePublicTransport'],
			'directionsGoogleWalking' => MMP::$settings['directionsGoogleWalking'],
			'directionsGoogleLanguage' => $google_language,
			'directionsGoogleOverview' => MMP::$settings['directionsGoogleOverview'],
			'directionsYoursType' => MMP::$settings['directionsYoursType'],
			'directionsYoursRoute' => MMP::$settings['directionsYoursRoute'],
			'directionsYoursLayer' => MMP::$settings['directionsYoursLayer'],
			'directionsOrsRoute' => MMP::$settings['directionsOrsRoute'],
			'directionsOrsType' => MMP::$settings['directionsOrsType'],
			'googleApiKey' => MMP::$settings['googleApiKey'],
			'bingApiKey' => MMP::$settings['bingApiKey'],
			'hereApiKey' => MMP::$settings['hereApiKey'],
			'hereAppId' => MMP::$settings['hereAppId'],
			'hereAppCode' => MMP::$settings['hereAppCode'],
			'tomApiKey' => MMP::$settings['tomApiKey'],
			'errorTiles' => MMP::$settings['errorTiles'],
			'iconSize' => array(MMP::$settings['iconSizeX'], MMP::$settings['iconSizeY']),
			'iconAnchor' => array(MMP::$settings['iconAnchorX'], MMP::$settings['iconAnchorY']),
			'popupAnchor' => array(MMP::$settings['iconPopupAnchorX'], MMP::$settings['iconPopupAnchorY']),
			'shadowUrl' => (MMP::$settings['iconCustomShadow'] === 'custom') ? MMP::$settings['iconCustomShadowUrl'] : plugins_url('images/leaflet/marker-shadow.png', __DIR__),
			'shadowSize' => array(MMP::$settings['iconShadowSizeX'], MMP::$settings['iconShadowSizeY']),
			'shadowAnchor' => array(MMP::$settings['iconShadowAnchorX'], MMP::$settings['iconShadowAnchorY'])
		);

		$settings = array_merge($globals, $settings);
		$settings = apply_filters('mmp_map_settings', $settings);
		if ($id) {
			$settings = apply_filters("mmp_map_{$id}_settings", $settings);
		}

		wp_send_json(array(
			'success'  => true,
			'response' => $settings
		));
	}

	/**
	 * AJAX request for retrieving the map markers
	 *
	 * @since 4.0
	 */
	public function map_markers() {
		$db = MMP::get_instance('MMP\DB');
		$l10n = MMP::get_instance('MMP\L10n');

		$type = (isset($_POST['type'])) ? $_POST['type'] : null;
		$id = (isset($_POST['id'])) ? $_POST['id'] : null;
		$custom = (isset($_POST['custom'])) ? $_POST['custom'] : null;
		$all = (isset($_POST['all']) && $_POST['all'] == 'true') ? true : false;
		$lang = (isset($_POST['lang'])) ? $_POST['lang'] : null;

		$current_user = wp_get_current_user();

		if (!is_admin()) {
			do_action('wpml_switch_language', $lang);
		}

		if (!$id || $id === 'new') {
			wp_send_json(array());
		}

		if ($type === 'map') {
			if ($all) {
				$filters = array();
			} else {
				$filters = array('include_maps' => $id);
			}
			$filters['scheduled'] = false;
		} else if ($type === 'marker') {
			$filters = array('include' => $id);
		} else if ($type === 'custom') {
			$filters = array('include' => $custom);
		} else {
			wp_send_json(array());
		}

		$data = array();
		$total = $db->count_markers($filters);
		$batches = ceil($total / 1000);
		for ($i = 1; $i <= $batches; $i++) {
			$filters = array_merge($filters, array(
				'offset' => ($i - 1) * 1000,
				'limit' => 1000
			));
			$markers = $db->get_all_markers($filters);
			foreach ($markers as $marker) {
				$data[] = array(
					'id'      => $marker->id,
					'lat'     => $marker->lat,
					'lng'     => $marker->lng,
					'name'    => $l10n->__($marker->name, "Marker (ID {$marker->id}) name"),
					'address' => $l10n->__($marker->address, "Marker (ID {$marker->id}) address"),
					'popup'   => apply_filters('mmp_popup', $l10n->__($marker->popup, "Marker (ID {$marker->id}) popup")),
					'link'    => $marker->link,
					'blank'   => $marker->blank,
					'icon'    => $marker->icon,
					'created' => strtotime($marker->created_on),
					'updated' => strtotime($marker->updated_on),
					'maps'    => explode(',', $marker->maps),
					'edit'    => $marker->created_by_id == $current_user->ID || current_user_can('mmp_edit_other_markers')
				);
			}
		}

		$data = apply_filters('mmp_map_markers', $data);
		foreach ($db->sanitize_ids($id) as $map_id) {
			$data = apply_filters("mmp_map_{$map_id}_markers", $data);
		}

		wp_send_json($data);
	}

	/**
	 * AJAX request for retrieving the map GeoJSON
	 *
	 * @since 4.3
	 */
	public function map_geojson() {
		$db = MMP::get_instance('MMP\DB');

		$type = (isset($_POST['type'])) ? $_POST['type'] : null;
		$id = (isset($_POST['id'])) ? $_POST['id'] : null;
		$lang = (isset($_POST['lang'])) ? $_POST['lang'] : null;

		if (!is_admin()) {
			do_action('wpml_switch_language', $lang);
		}

		if ($id && $id !== 'new' && ($type === 'map' || $type === 'custom')) {
			$maps = $db->get_all_maps(false, array(
				'include' => $id
			));
			foreach ($maps as $map) {
				$geojson[$map->id] = json_decode(($map->geojson) ? $map->geojson : '{}');
			}
		}

		if (!isset($geojson) || !count($geojson)) {
			$geojson = json_decode('{}');
		}

		$geojson = apply_filters('mmp_map_geojson', $geojson);
		foreach ($db->sanitize_ids($id) as $map_id) {
			$geojson = apply_filters("mmp_map_{$map_id}_geojson", $geojson);
		}

		wp_send_json($geojson);
	}

	/**
	 * AJAX request for retrieving the marker settings
	 *
	 * @since 4.0
	 */
	public function marker_settings() {
		$db = MMP::get_instance('MMP\DB');
		$mmp_settings = MMP::get_instance('MMP\Settings');

		$id = (isset($_POST['id'])) ? absint($_POST['id']) : null;
		$basemap = isset($_POST['basemap']) ? preg_replace('/[^0-9A-Za-z]/', '', $_POST['basemap']) : null;
		$lat = (isset($_POST['lat'])) ? floatval($_POST['lat']) : null;
		$lng = (isset($_POST['lng'])) ? floatval($_POST['lng']) : null;
		$zoom = (isset($_POST['zoom'])) ? abs(floatval($_POST['zoom'])) : null;

		$settings = $mmp_settings->get_marker_defaults();

		if ($id) {
			$marker = $db->get_marker($id);
			$settings['name'] = $marker->name;
			$settings['address'] = $marker->address;
			$settings['lat'] = $marker->lat;
			$settings['lng'] = $marker->lng;
			$settings['zoom'] = $marker->zoom;
			$settings['popup'] = $marker->popup;
			$settings['link'] = $marker->link;
			$settings['blank'] = $marker->blank;
			$settings['icon'] = $marker->icon;
			$settings['maps'] = $db->sanitize_ids($marker->maps);
		} else {
			$settings['basemap'] = ($basemap) ? $basemap : $settings['basemap'];
			$settings['name'] = '';
			$settings['address'] = '';
			$settings['lat'] = ($lat) ? $lat : $settings['lat'];
			$settings['lng'] = ($lng) ? $lng : $settings['lng'];
			$settings['zoom'] = ($zoom) ? $zoom : $settings['zoom'];
			$settings['popup'] = '';
			$settings['link'] = '';
			$settings['blank'] = '1';
			$settings['maps'] = array();
		}

		$settings['availableBasemaps'] = $mmp_settings->get_basemaps();

		$globals = array(
			'googleApiKey' => MMP::$settings['googleApiKey'],
			'bingApiKey' => MMP::$settings['bingApiKey'],
			'bingCulture' => (MMP::$settings['bingCulture'] === 'automatic') ? str_replace('_', '-', get_locale()) : MMP::$settings['bingCulture'],
			'hereApiKey' => MMP::$settings['hereApiKey'],
			'hereAppId' => MMP::$settings['hereAppId'],
			'hereAppCode' => MMP::$settings['hereAppCode'],
			'tomApiKey' => MMP::$settings['tomApiKey']
		);

		$settings = array_merge($globals, $settings);
		$settings = apply_filters('mmp_marker_settings', $settings);
		if ($id) {
			$settings = apply_filters("mmp_marker_{$id}_settings", $settings);
		}

		wp_send_json(array(
			'settings' => $settings
		));
	}
}
