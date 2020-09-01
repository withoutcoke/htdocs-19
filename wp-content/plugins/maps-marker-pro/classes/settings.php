<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Settings {
	/**
	 * Retrieves the current plugin settings
	 *
	 * @since 4.0
	 */
	public function get_settings() {
		$settings = get_option('mapsmarkerpro_settings');

		return $this->validate_settings($settings, true, false);
	}

	/**
	 * Retrieves the current map defaults
	 *
	 * @since 4.0
	 */
	public function get_map_defaults() {
		$settings = get_option('mapsmarkerpro_map_defaults');

		return $this->validate_map_settings($settings, true, false);
	}

	/**
	 * Retrieves the current marker defaults
	 *
	 * @since 4.0
	 */
	public function get_marker_defaults() {
		$settings = get_option('mapsmarkerpro_marker_defaults');

		return $this->validate_marker_settings($settings, true, false);
	}

	/**
	 * Retrieves the default plugin settings
	 *
	 * @since 4.0
	 */
	public function get_default_settings() {
		foreach ($this->settings_sanity() as $key => $value) {
			$defaults[$key] = $value['default'];
		}

		return $defaults;
	}

	/**
	 * Retrieves the default map settings
	 *
	 * @since 4.0
	 */
	public function get_default_map_settings() {
		foreach ($this->map_settings_sanity() as $key => $value) {
			$defaults[$key] = $value['default'];
		}

		return $defaults;
	}

	/**
	 * Retrieves the default marker settings
	 *
	 * @since 4.0
	 */
	public function get_default_marker_settings() {
		foreach ($this->marker_settings_sanity() as $key => $value) {
			$defaults[$key] = $value['default'];
		}

		return $defaults;
	}

	/**
	 * Retrieves the default maps page screen settings
	 *
	 * @since 4.8
	 */
	public function get_default_maps_screen_settings() {
		foreach ($this->maps_screen_settings_sanity() as $key => $value) {
			$defaults[$key] = $value['default'];
		}

		return $defaults;
	}

	/**
	 * Retrieves the default markers page screen settings
	 *
	 * @since 4.8
	 */
	public function get_default_markers_screen_settings() {
		foreach ($this->markers_screen_settings_sanity() as $key => $value) {
			$defaults[$key] = $value['default'];
		}

		return $defaults;
	}

	/**
	 * Validates the given plugin settings
	 *
	 * @since 4.0
	 * @since 4.5 $skip_missing parameter added
	 *
	 * @param array $data List of settings
	 * @param bool $default_bool (optional) Whether missing bools should use their default values or be considered false
	 * @param bool $skip_missing (optional) Whether missing keys should use their default values or be skipped
	 */
	public function validate_settings($settings, $default_bool = true, $skip_missing = false) {
		return $this->sanitize_settings($settings, $this->settings_sanity(), $default_bool, $skip_missing);
	}

	/**
	 * Validates the given map settings
	 *
	 * @since 4.0
	 * @since 4.5 $skip_missing parameter added
	 *
	 * @param array $data List of settings
	 * @param bool $default_bool (optional) Whether missing bools should use their default values or be considered false
	 * @param bool $skip_missing (optional) Whether missing keys should use their default values or be skipped
	 */
	public function validate_map_settings($settings, $default_bool = true, $skip_missing = false) {
		return $this->sanitize_settings($settings, $this->map_settings_sanity(), $default_bool, $skip_missing);
	}

	/**
	 * Validates the given marker settings
	 *
	 * @since 4.0
	 * @since 4.5 $skip_missing parameter added
	 *
	 * @param array $data List of settings
	 * @param bool $default_bool (optional) Whether missing bools should use their default values or be considered false
	 * @param bool $skip_missing (optional) Whether missing keys should use their default values or be skipped
	 */
	public function validate_marker_settings($settings, $default_bool = true, $skip_missing = false) {
		return $this->sanitize_settings($settings, $this->marker_settings_sanity(), $default_bool, $skip_missing);
	}

	/**
	 * Validates the given maps page screen settings
	 *
	 * @since 4.8
	 *
	 * @param array $data List of settings
	 * @param bool $default_bool (optional) Whether missing bools should use their default values or be considered false
	 * @param bool $skip_missing (optional) Whether missing keys should use their default values or be skipped
	 */
	public function validate_maps_screen_settings($settings, $default_bool = true, $skip_missing = false) {
		return $this->sanitize_settings($settings, $this->maps_screen_settings_sanity(), $default_bool, $skip_missing);
	}

	/**
	 * Validates the given markers page screen settings
	 *
	 * @since 4.8
	 *
	 * @param array $data List of settings
	 * @param bool $default_bool (optional) Whether missing bools should use their default values or be considered false
	 * @param bool $skip_missing (optional) Whether missing keys should use their default values or be skipped
	 */
	public function validate_markers_screen_settings($settings, $default_bool = true, $skip_missing = false) {
		return $this->sanitize_settings($settings, $this->markers_screen_settings_sanity(), $default_bool, $skip_missing);
	}

	/**
	 * Sanitizes the given settings
	 *
	 * @since 4.0
	 * @since 4.5 $skip_missing parameter added
	 *
	 * @param array $data List of settings
	 * @param array $sanity List of sanitization rules
	 * @param bool $default_bool (optional) Whether missing bools should use their default values or be considered false
	 * @param bool $skip_missing (optional) Whether missing keys should use their default values or be skipped
	 */
	public function sanitize_settings($data, $sanity, $default_bool = true, $skip_missing = false) {
		$validated = array();
		foreach ($sanity as $key => $value) {
			if ($skip_missing && !isset($data[$key])) {
				continue;
			}

			switch ($value['type']) {
				case 'bool':
					if (isset($data[$key])) {
						if ($data[$key] === true || in_array($data[$key], array('true', '1', 'on', 'enabled', 'yes', 'show'))) {
							$validated[$key] = true;
						} else {
							$validated[$key] = false;
						}
					} else {
						if ($default_bool) {
							$validated[$key] = $value['default'];
						} else {
							$validated[$key] = false;
						}
					}
					break;
				case 'int':
					if (isset($data[$key]) && intval($data[$key]) >= $value['min'] && intval($data[$key]) <= $value['max']) {
						$validated[$key] = intval($data[$key]);
					} else {
						$validated[$key] = $value['default'];
					}
					break;
				case 'absint':
					if (isset($data[$key]) && absint($data[$key]) >= $value['min'] && absint($data[$key]) <= $value['max']) {
						$validated[$key] = absint($data[$key]);
					} else {
						$validated[$key] = $value['default'];
					}
					break;
				case 'float':
					if (isset($data[$key]) && floatval($data[$key]) >= $value['min'] && floatval($data[$key]) <= $value['max']) {
						$validated[$key] = floatval($data[$key]);
					} else {
						$validated[$key] = $value['default'];
					}
					break;
				case 'absfloat':
					if (isset($data[$key]) && abs(floatval($data[$key])) >= $value['min'] && abs(floatval($data[$key])) <= $value['max']) {
						$validated[$key] = abs(floatval($data[$key]));
					} else {
						$validated[$key] = $value['default'];
					}
					break;
				case 'string':
					if (isset($data[$key])) {
						if ($value['allowed'] === true) {
							$validated[$key] = $data[$key];
							if (isset($value['sanity'])) {
								foreach ($value['sanity'] as $func) {
									$validated[$key] = $func($validated[$key]);
								}
							} else {
								$validated[$key] = sanitize_text_field($validated[$key]);
							}
							if (isset($value['empty']) && $value['empty'] === false && $validated[$key] === '') {
								$validated[$key] = $value['default'];
							}
						} else {
							if (in_array($data[$key], $value['allowed'], true)) {
								$validated[$key] = $data[$key];
							} else {
								$validated[$key] = $value['default'];
							}
						}
					} else {
						$validated[$key] = $value['default'];
					}
					break;
				case 'array':
					if (isset($data[$key])) {
						$validated[$key] = $data[$key];
					} else {
						$validated[$key] = $value['default'];
					}
					break;
				default:
					$validated[$key] = $value['default'];
					break;
			}
		}

		return $validated;
	}

	/**
	 * Returns the sanitization rules for the plugin settings
	 *
	 * @since 4.0
	 */
	public function settings_sanity() {
		$settings['googleApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		// https://developers.google.com/maps/faq#languagesupport
		$settings['googleLanguage'] = array(
			'type'    => 'string',
			'default' => 'browser_setting',
			'allowed' => array('browser_setting', 'wordpress_setting', 'ar', 'be', 'bg', 'bn', 'ca', 'cs', 'da', 'de', 'el', 'en', 'en-AU', 'en-GB', 'es', 'eu', 'fa', 'fi', 'fil', 'fr', 'gl', 'gu', 'hi', 'hr', 'hu', 'id', 'it', 'iw', 'ja', 'kk', 'kn', 'ko', 'ky', 'lt', 'lv', 'mk', 'ml', 'mr', 'my', 'nl', 'no', 'pa', 'pl', 'pt', 'pt-BR', 'pt-PT', 'ro', 'ru', 'sk', 'sl', 'sq', 'sr', 'sv', 'ta', 'te', 'th', 'tl', 'tr', 'uk', 'uz', 'vi', 'zh-CN', 'zh-TW')
		);
		$settings['bingApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		// https://msdn.microsoft.com/en-us/library/hh441729.aspx
		$settings['bingCulture'] = array(
			'type'    => 'string',
			'default' => 'automatic',
			'allowed' => array('automatic', 'af', 'am', 'ar-sa', 'as', 'az-Latn', 'be', 'bg', 'bn-BD', 'bn-IN', 'bs', 'ca', 'ca-ES-valencia', 'cs', 'cy', 'da', 'de', 'de-de', 'el', 'en-GB', 'en-US', 'es', 'es-ES', 'es-US', 'es-MX', 'et', 'eu', 'fa', 'fi', 'fil-Latn', 'fr', 'fr-FR', 'fr-CA', 'ga', 'gd-Latn', 'gl', 'gu', 'ha-Latn', 'he', 'hi', 'hr', 'hu', 'hy', 'id', 'ig-Latn', 'is', 'it', 'it-it', 'ja', 'ka', 'kk', 'km', 'kn', 'ko', 'kok', 'ku-Arab', 'ky-Cyrl', 'lb', 'lt', 'lv', 'mi-Latn', 'mk', 'ml', 'mn-Cyrl', 'mr', 'ms', 'mt', 'nb', 'ne', 'nl', 'nl-BE', 'nn', 'nso', 'or', 'pa', 'pa-Arab', 'pl', 'prs-Arab', 'pt-BR', 'pt-PT', 'qut-Latn', 'quz', 'ro', 'ru', 'rw', 'sd-Arab', 'si', 'sk', 'sl', 'sq', 'sr-Cyrl-BA', 'sr-Cyrl-RS', 'sr-Latn-RS', 'sv', 'sw', 'ta', 'te', 'tg-Cyrl', 'th', 'ti', 'tk-Latn', 'tn', 'tr', 'tt-Cyrl', 'ug-Arab', 'uk', 'ur', 'uz-Latn', 'vi', 'wo', 'xh', 'yo-Latn', 'zh-Hans', 'zh-Hant', 'zu')
		);
		$settings['hereApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['hereAppId'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['hereAppCode'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['tomApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['errorTiles'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['disabledBasemaps'] = array(
			'type'    => 'array',
			'default' => array(),
			'allowed' => true
		);
		$settings['geocodingProvider'] = array(
			'type'    => 'string',
			'default' => 'algolia',
			'allowed' => array('algolia', 'photon', 'locationiq', 'mapquest', 'google', 'tomtom')
		);
		$settings['geocodingTypingDelay'] = array(
			'type'    => 'absint',
			'default' => '400',
			'min'     => 0,
			'max'     => INF
		);
		$settings['geocodingMinChars'] = array(
			'type'    => 'absint',
			'default' => 3,
			'min'     => 1,
			'max'     => INF
		);
		$settings['geocodingAlgoliaAppId'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingAlgoliaApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingAlgoliaAroundLatLng'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingAlgoliaAroundLatLngViaIp'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['geocodingAlgoliaLanguage'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingAlgoliaCountries'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingPhotonLanguage'] = array(
			'type'    => 'string',
			'default' => 'automatic',
			'allowed' => array('automatic', 'en', 'de', 'fr', 'it')
		);
		$settings['geocodingPhotonBiasLat'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingPhotonBiasLon'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingPhotonBounds'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['geocodingPhotonBoundsLat1'] = array(
			'type'    => 'float',
			'default' => 48.326583,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingPhotonBoundsLon1'] = array(
			'type'    => 'float',
			'default' => 16.55056,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingPhotonBoundsLat2'] = array(
			'type'    => 'float',
			'default' => 48.114308,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingPhotonBoundsLon2'] = array(
			'type'    => 'float',
			'default' => 16.187325,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingPhotonFilter'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingLocationIqApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingLocationIqBounds'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['geocodingLocationIqBoundsLat1'] = array(
			'type'    => 'float',
			'default' => 48.326583,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingLocationIqBoundsLon1'] = array(
			'type'    => 'float',
			'default' => 16.55056,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingLocationIqBoundsLat2'] = array(
			'type'    => 'float',
			'default' => 48.114308,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingLocationIqBoundsLon2'] = array(
			'type'    => 'float',
			'default' => 16.187325,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingLocationIqLanguage'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingMapQuestApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingMapQuestBounds'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['geocodingMapQuestBoundsLat1'] = array(
			'type'    => 'float',
			'default' => 48.326583,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingMapQuestBoundsLon1'] = array(
			'type'    => 'float',
			'default' => 16.55056,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingMapQuestBoundsLat2'] = array(
			'type'    => 'float',
			'default' => 48.114308,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingMapQuestBoundsLon2'] = array(
			'type'    => 'float',
			'default' => 16.187325,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['geocodingGoogleAuthMethod'] = array(
			'type'    => 'string',
			'default' => 'api-key',
			'allowed' => array('api-key', 'clientid-signature')
		);
		$settings['geocodingGoogleApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingGoogleClient'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingGoogleSignature'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingGoogleChannel'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingGoogleLocation'] = array(
			'type'    => 'string',
			'default' => '0,0',
			'allowed' => true
		);
		$settings['geocodingGoogleRadius'] = array(
			'type'    => 'absint',
			'default' => 20000000,
			'min'     => 0,
			'max'     => INF
		);
		$settings['geocodingGoogleLanguage'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingGoogleRegion'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingGoogleComponents'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingTomTomApiKey'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingTomTomLat'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingTomTomLon'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingTomTomRadius'] = array(
			'type'    => 'absint',
			'default' => 0,
			'min'     => 0,
			'max'     => INF
		);
		$settings['geocodingTomTomLanguage'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['geocodingTomTomCountrySet'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['directionsProvider'] = array(
			'type'    => 'string',
			'default' => 'googlemaps',
			'allowed' => array('googlemaps', 'yours', 'ors', 'bingmaps')
		);
		$settings['directionsGoogleType'] = array(
			'type'    => 'string',
			'default' => 'm',
			'allowed' => array('m', 'k', 'h', 'p')
		);
		$settings['directionsGoogleTraffic'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['directionsGoogleUnits'] = array(
			'type'    => 'string',
			'default' => 'ptk',
			'allowed' => array('ptk', 'ptm')
		);
		$settings['directionsGoogleAvoidHighways'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['directionsGoogleAvoidTolls'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['directionsGooglePublicTransport'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['directionsGoogleWalking'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['directionsGoogleOverview'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['directionsYoursType'] = array(
			'type'    => 'string',
			'default' => 'motorcar',
			'allowed' => array('motorcar', 'bicycle', 'foot')
		);
		$settings['directionsYoursRoute'] = array(
			'type'    => 'string',
			'default' => 'fastest',
			'allowed' => array('fastest', 'shortest')
		);
		$settings['directionsYoursLayer'] = array(
			'type'    => 'string',
			'default' => 'mapnik',
			'allowed' => array('mapnik', 'cn')
		);
		$settings['directionsOrsRoute'] = array(
			'type'    => 'string',
			'default' => 'Fastest',
			'allowed' => array('Fastest', 'Shortest', 'Recommended')
		);
		$settings['directionsOrsType'] = array(
			'type'    => 'string',
			'default' => 'Car',
			'allowed' => array('Car', 'Bicycle', 'Pedestrian', 'HeavyVehicle')
		);
		$settings['affiliateId'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['betaTesting'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['appIcon'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array('esc_url')
		);
		$settings['whitelabelBackend'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['backlinks'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['iconCustomShadow'] = array(
			'type'    => 'string',
			'default' => 'default',
			'allowed' => array('default', 'custom')
		);
		$settings['iconCustomShadowUrl'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array('esc_url')
		);
		$settings['iconSizeX'] = array(
			'type'    => 'absint',
			'default' => 32,
			'min'     => 0,
			'max'     => INF
		);
		$settings['iconSizeY'] = array(
			'type'    => 'absint',
			'default' => 37,
			'min'     => 0,
			'max'     => INF
		);
		$settings['iconAnchorX'] = array(
			'type'    => 'int',
			'default' => 17,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['iconAnchorY'] = array(
			'type'    => 'int',
			'default' => 36,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['iconPopupAnchorX'] = array(
			'type'    => 'int',
			'default' => -1,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['iconPopupAnchorY'] = array(
			'type'    => 'int',
			'default' => -32,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['iconShadowSizeX'] = array(
			'type'    => 'absint',
			'default' => 41,
			'min'     => 0,
			'max'     => INF
		);
		$settings['iconShadowSizeY'] = array(
			'type'    => 'absint',
			'default' => 41,
			'min'     => 0,
			'max'     => INF
		);
		$settings['iconShadowAnchorX'] = array(
			'type'    => 'int',
			'default' => 16,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['iconShadowAnchorY'] = array(
			'type'    => 'int',
			'default' => 43,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['sitemapGoogle'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['sitemapGoogleInclude'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['sitemapGoogleExclude'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['sitemapGooglePriority'] = array(
			'type'    => 'string',
			'default' => '0.5',
			'allowed' => array('0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1')
		);
		$settings['sitemapGoogleFrequency'] = array(
			'type'    => 'string',
			'default' => 'monthly',
			'allowed' => array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never')
		);
		$settings['sitemapYoast'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['shortcode'] = array(
			'type'    => 'string',
			'default' => 'mapsmarker',
			'empty'   => false,
			'allowed' => true,
			'sanity'  => array('sanitize_key')
		);
		$settings['tinyMce'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['adminBar'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['dashboardWidget'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['adminNotices'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['apiFullscreen'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['apiExport'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['apiSitemap'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['permalinkSlug'] = array(
			'type'    => 'string',
			'default' => 'mmp',
			'empty'   => false,
			'allowed' => true,
			'sanity'  => array('sanitize_key')
		);
		$settings['permalinkBaseUrl'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array('esc_url')
		);
		$settings['popupKses'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['lazyLoad'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['pluginLanguageAdmin'] = array(
			'type'    => 'string',
			'default' => 'automatic',
			'allowed' => array('automatic', 'ar', 'af', 'bn_BD', 'bs_BA', 'bg_BG', 'ca', 'zh_CN', 'zh_TW', 'hr', 'cs_CZ', 'da_DK', 'nl_NL', 'en_US', 'fi_FI', 'fr_FR', 'gl_ES', 'de_DE', 'el', 'he_IL', 'hi_IN', 'hu_HU', 'id_ID', 'it_IT', 'ja', 'ko_KR', 'lv', 'lt_LT', 'ms_MY', 'nb_NO', 'pl_PL', 'pt_BR', 'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sv_SE', 'es_ES', 'es_MX', 'th', 'tr_TR', 'ug', 'uk_UK', 'vi', 'yi')
		);
		$settings['pluginLanguageFrontend'] = array(
			'type'    => 'string',
			'default' => 'automatic',
			'allowed' => array('automatic', 'ar', 'af', 'bn_BD', 'bs_BA', 'bg_BG', 'ca', 'zh_CN', 'zh_TW', 'hr', 'cs_CZ', 'da_DK', 'nl_NL', 'en_US', 'fi_FI', 'fr_FR', 'gl_ES', 'de_DE', 'el', 'he_IL', 'hi_IN', 'hu_HU', 'id_ID', 'it_IT', 'ja', 'ko_KR', 'lv', 'lt_LT', 'ms_MY', 'nb_NO', 'pl_PL', 'pt_BR', 'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sv_SE', 'es_ES', 'es_MX', 'th', 'tr_TR', 'ug', 'uk_UK', 'vi', 'yi')
		);
		$settings['customJs'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array()
		);

		return $settings;
	}

	/**
	 * Returns the sanitization rules for the map settings
	 *
	 * @since 4.0
	 */
	public function map_settings_sanity() {
		/**
		 * Map
		 */
		$settings['width'] = array(
			'type'    => 'absint',
			'default' => 100,
			'min'     => 0,
			'max'     => INF
		);
		$settings['widthUnit'] = array(
			'type'    => 'string',
			'default' => '%',
			'allowed' => array('%', 'px')
		);
		$settings['height'] = array(
			'type'    => 'absint',
			'default' => 600,
			'min'     => 0,
			'max'     => INF
		);
		$settings['heightUnit'] = array(
			'type'    => 'string',
			'default' => 'px',
			'allowed' => array('px')
		);
		$settings['lat'] = array(
			'type'    => 'float',
			'default' => 51.477806,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['lng'] = array(
			'type'    => 'float',
			'default' => -0.001472,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['zoom'] = array(
			'type'    => 'absfloat',
			'default' => 8,
			'min'     => 0,
			'max'     => 23
		);
		$settings['minZoom'] = array(
			'type'    => 'absfloat',
			'default' => 0,
			'min'     => 0,
			'max'     => 23
		);
		$settings['maxZoom'] = array(
			'type'    => 'absfloat',
			'default' => 18,
			'min'     => 0,
			'max'     => 23
		);
		$settings['zoomStep'] = array(
			'type'    => 'absfloat',
			'default' => 1,
			'min'     => 0.1,
			'max'     => 1
		);
		$settings['maxBounds'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array('sanitize_textarea_field')
		);
		$settings['panel'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['panelColor'] = array(
			'type'    => 'string',
			'default' => '#ccc',
			'allowed' => true
		);
		$settings['panelFs'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['panelGpx'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['panelGeoJson'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['panelKml'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['panelGeoRss'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['callback'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);

		/**
		 * Layers
		 */
		$settings['basemapDetectRetina'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['basemapEdgeBufferTiles'] = array(
			'type'    => 'absint',
			'default' => 2,
			'min'     => 0,
			'max'     => 4
		);
		$settings['basemapGoogleStyles'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array()
		);
		$settings['basemaps'] = array(
			'type'    => 'array',
			'default' => array('osm', 'stamenTerrain', 'stamenToner', 'stamenWatercolor'),
			'allowed' => true
		);
		$settings['basemapDefault'] = array(
			'type'    => 'string',
			'default' => 'osm',
			'allowed' => true
		);
		$settings['overlays'] = array(
			'type'    => 'array',
			'default' => array(),
			'allowed' => true
		);
		$settings['overlaysActive'] = array(
			'type'    => 'array',
			'default' => array(),
			'allowed' => true
		);

		/**
		 * Controls
		 */
		$settings['attributionPosition'] = array(
			'type'    => 'string',
			'default' => 'bottomright',
			'allowed' => array('topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['attributionCondensed'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['zoomControlPosition'] = array(
			'type'    => 'string',
			'default' => 'topleft',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['fullscreenPosition'] = array(
			'type'    => 'string',
			'default' => 'topleft',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['resetPosition'] = array(
			'type'    => 'string',
			'default' => 'topleft',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['resetOnDemand'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['locatePosition'] = array(
			'type'    => 'string',
			'default' => 'hidden',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['locateDrawCircle'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['locateDrawMarker'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['locateSetView'] = array(
			'type'    => 'string',
			'default' => 'untilPan',
			'allowed' => array(false, 'once', 'always', 'untilPan', 'untilPanOrZoom')
		);
		$settings['locateKeepCurrentZoomLevel'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['locateClickBehaviorInView'] = array(
			'type'    => 'string',
			'default' => 'stop',
			'allowed' => array('stop', 'setView')
		);
		$settings['locateClickBehaviorOutOfView'] = array(
			'type'    => 'string',
			'default' => 'setView',
			'allowed' => array('stop', 'setView')
		);
		$settings['locateIcon'] = array(
			'type'    => 'string',
			'default' => 'icon-cross-hairs',
			'allowed' => array('icon-cross-hairs', 'icon-pin', 'icon-arrow')
		);
		$settings['locateMetric'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['locateShowPopup'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['locateAutostart'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['scalePosition'] = array(
			'type'    => 'string',
			'default' => 'hidden',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['scaleMaxWidth'] = array(
			'type'    => 'absint',
			'default' => 100,
			'min'     => 0,
			'max'     => INF
		);
		$settings['scaleMetric'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['scaleImperial'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['layersPosition'] = array(
			'type'    => 'string',
			'default' => 'topright',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['layersCollapsed'] = array(
			'type'    => 'string',
			'default' => 'collapsed',
			'allowed' => array('collapsed', 'collapsed-mobile', 'expanded')
		);
		$settings['filtersPosition'] = array(
			'type'    => 'string',
			'default' => 'topright',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['filtersCollapsed'] = array(
			'type'    => 'string',
			'default' => 'collapsed',
			'allowed' => array('collapsed', 'collapsed-mobile', 'expanded')
		);
		$settings['filtersButtons'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['filtersName'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['filtersIcon'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['filtersCount'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['filtersOrderBy'] = array(
			'type'    => 'string',
			'default' => 'name',
			'allowed' => array('id', 'name', 'count', 'custom')
		);
		$settings['filtersSortOrder'] = array(
			'type'    => 'string',
			'default' => 'asc',
			'allowed' => array('asc', 'desc')
		);
		$settings['gpxControlPosition'] = array(
			'type'    => 'string',
			'default' => 'topright',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['gpxControlCollapsed'] = array(
			'type'    => 'string',
			'default' => 'collapsed',
			'allowed' => array('collapsed', 'collapsed-mobile', 'expanded')
		);
		$settings['minimapPosition'] = array(
			'type'    => 'string',
			'default' => 'hidden',
			'allowed' => array('hidden', 'topleft', 'topright', 'bottomleft', 'bottomright')
		);
		$settings['minimapWidth'] = array(
			'type'    => 'absint',
			'default' => 150,
			'min'     => 0,
			'max'     => INF
		);
		$settings['minimapHeight'] = array(
			'type'    => 'absint',
			'default' => 150,
			'min'     => 0,
			'max'     => INF
		);
		$settings['minimapCollapsedWidth'] = array(
			'type'    => 'absint',
			'default' => 19,
			'min'     => 0,
			'max'     => INF
		);
		$settings['minimapCollapsedHeight'] = array(
			'type'    => 'absint',
			'default' => 19,
			'min'     => 0,
			'max'     => INF
		);
		$settings['minimapZoomLevelOffset'] = array(
			'type'    => 'float',
			'default' => -5,
			'min'     => -23,
			'max'     => 23
		);
		$settings['minimapZoomLevelFixed'] = array(
			'type'    => 'int',
			'default' => 0,
			'min'     => 0,
			'max'     => 23
		);
		$settings['minimapMinimized'] = array(
			'type'    => 'string',
			'default' => 'collapsed',
			'allowed' => array('collapsed', 'collapsed-mobile', 'expanded')
		);

		/**
		 * Share
		 */
		$settings['shareUrl'] = array(
			'type'    => 'string',
			'default' => 'page',
			'allowed' => array('page', 'fs')
		);
		$settings['shareText'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true,
			'sanity'  => array('sanitize_textarea_field')
		);
		$settings['popupShare'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['listShare'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['shareFacebook'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['shareTwitter'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['shareLinkedIn'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['shareWhatsApp'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['shareEmail'] = array(
			'type'    => 'bool',
			'default' => true
		);

		/**
		 * Markers
		 */
		$settings['markerOpacity'] = array(
			'type'    => 'absfloat',
			'default' => 1,
			'min'     => 0,
			'max'     => 1
		);
		$settings['clustering'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['showCoverageOnHover'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['disableClusteringAtZoom'] = array(
			'type'    => 'absfloat',
			'default' => 0,
			'min'     => 0,
			'max'     => 23
		);
		$settings['maxClusterRadius'] = array(
			'type'    => 'absint',
			'default' => 80,
			'min'     => 1,
			'max'     => INF
		);
		$settings['singleMarkerMode'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['spiderfyDistanceMultiplier'] = array(
			'type'    => 'absfloat',
			'default' => 1,
			'min'     => 0,
			'max'     => INF
		);
		$settings['tooltip'] = array(
			'type'    => 'bool',
			'default' => true
		);;
		$settings['tooltipDirection'] = array(
			'type'    => 'string',
			'default' => 'auto',
			'allowed' => array('auto', 'right', 'left', 'top', 'bottom', 'center')
		);
		$settings['tooltipPermanent'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['tooltipSticky'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['tooltipOpacity'] = array(
			'type'    => 'absfloat',
			'default' => 0.9,
			'min'     => 0,
			'max'     => 1
		);
		$settings['popupOpenOnHover'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['popupCenterOnMap'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['popupMarkername'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['popupAddress'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['popupCoordinates'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['popupDirections'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['popupMinWidth'] = array(
			'type'    => 'absint',
			'default' => 100,
			'min'     => 0,
			'max'     => INF
		);
		$settings['popupMaxWidth'] = array(
			'type'    => 'absint',
			'default' => 300,
			'min'     => 0,
			'max'     => INF
		);
		$settings['popupMaxHeight'] = array(
			'type'    => 'absint',
			'default' => 400,
			'min'     => 0,
			'max'     => INF
		);
		$settings['popupCloseButton'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['popupAutoClose'] = array(
			'type'    => 'bool',
			'default' => true
		);

		/**
		 * Filters
		 */
		$settings['filtersAllMarkers'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['filtersGeoJson'] = array(
			'type'    => 'bool',
			'default' => false
		);

		/**
		 * List
		 */
		$settings['list'] = array(
			'type'    => 'absint',
			'default' => 1,
			'min'     => 0,
			'max'     => 3
		);
		$settings['listWidth'] = array(
			'type'    => 'absint',
			'default' => 400,
			'min'     => 0,
			'max'     => INF
		);
		$settings['listBreakpoint'] = array(
			'type'    => 'absint',
			'default' => 600,
			'min'     => 0,
			'max'     => INF
		);
		$settings['listSearch'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listIcon'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listName'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listPopup'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listAddress'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listDistance'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listDistanceUnit'] = array(
			'type'    => 'string',
			'default' => 'metric',
			'allowed' => array('metric', 'imperial', 'metric-imperial', 'imperial-metric')
		);
		$settings['listDistancePrecision'] = array(
			'type'    => 'absint',
			'default' => 1,
			'min'     => 0,
			'max'     => 6
		);
		$settings['listOrderBy'] = array(
			'type'    => 'string',
			'default' => 'name',
			'allowed' => array('id', 'name', 'address', 'distance', 'icon', 'created', 'updated')
		);
		$settings['listSortOrder'] = array(
			'type'    => 'string',
			'default' => 'asc',
			'allowed' => array('asc', 'desc')
		);
		$settings['listOrderById'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listOrderByName'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listOrderByAddress'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listOrderByDistance'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listOrderByIcon'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listOrderByCreated'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listOrderByUpdated'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listLimit'] = array(
			'type'    => 'absint',
			'default' => 10,
			'min'     => 1,
			'max'     => INF
		);
		$settings['listDir'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listFs'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['listAction'] = array(
			'type'    => 'string',
			'default' => 'popup',
			'allowed' => array('none', 'setview', 'popup')
		);

		/**
		 * Interaction
		 */
		$settings['gestureHandling'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['responsive'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['dragging'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['inertia'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['inertiaDeceleration'] = array(
			'type'    => 'absint',
			'default' => 3000,
			'min'     => 1,
			'max'     => INF
		);
		$settings['inertiaMaxSpeed'] = array(
			'type'    => 'absint',
			'default' => 6000,
			'min'     => 1,
			'max'     => INF
		);
		$settings['keyboard'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['keyboardPanDelta'] = array(
			'type'    => 'absint',
			'default' => 80,
			'min'     => 0,
			'max'     => INF
		);
		$settings['scrollWheelZoom'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['doubleClickZoom'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['touchZoom'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['boxZoom'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['bounceAtZoomLimits'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['worldCopyJump'] = array(
			'type'    => 'bool',
			'default' => true
		);

		/**
		 * GPX
		 */
		$settings['gpxUrl'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['gpxMeta'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaUnits'] = array(
			'type'    => 'string',
			'default' => 'metric',
			'allowed' => array('metric', 'imperial', 'metric-imperial', 'imperial-metric')
		);
		$settings['gpxMetaInterval'] = array(
			'type'    => 'absint',
			'default' => '15000',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxMetaName'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaDesc'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaStart'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaEnd'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaTotal'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaMoving'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxMetaDistance'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaPace'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxMetaHeartRate'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxMetaElevation'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxMetaDownload'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxShowStartIcon'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxStartIcon'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['gpxShowEndIcon'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxEndIcon'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['gpxIntervalMarkers'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxTrackSmoothFactor'] = array(
			'type'    => 'absfloat',
			'default' => '1',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxTrackColor'] = array(
			'type'    => 'string',
			'default' => '#0000ff',
			'allowed' => true
		);
		$settings['gpxTrackWeight'] = array(
			'type'    => 'absfloat',
			'default' => '5',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxTrackOpacity'] = array(
			'type'    => 'absfloat',
			'default' => '0.5',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxWaypoints'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxWaypointsRadius'] = array(
			'type'    => 'absint',
			'default' => '6',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxWaypointsStroke'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxWaypointsColor'] = array(
			'type'    => 'string',
			'default' => '#ff0000',
			'allowed' => true
		);
		$settings['gpxWaypointsWeight'] = array(
			'type'    => 'absint',
			'default' => '2',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxWaypointsFillColor'] = array(
			'type'    => 'string',
			'default' => '#0000ff',
			'allowed' => true
		);
		$settings['gpxWaypointsFillOpacity'] = array(
			'type'    => 'absfloat',
			'default' => '1',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxChart'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxChartUnits'] = array(
			'type'    => 'string',
			'default' => 'metric',
			'allowed' => array('metric', 'imperial', 'metric-imperial', 'imperial-metric')
		);
		$settings['gpxChartHeight'] = array(
			'type'    => 'absint',
			'default' => '200',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxChartReverseX'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxChartReverseY'] = array(
			'type'    => 'bool',
			'default' => false
		);
		$settings['gpxChartYMin'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['gpxChartYMax'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['gpxChartYOffset'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);
		$settings['gpxChartBgColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(255, 255, 255, 1)',
			'allowed' => true
		);
		$settings['gpxChartGridLinesColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(0, 0, 0, 0.1)',
			'allowed' => true
		);
		$settings['gpxChartTicksFontColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(102, 102, 102, 1)',
			'allowed' => true
		);
		$settings['gpxChartLineWidth'] = array(
			'type'    => 'absint',
			'default' => '1',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxChartLineColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(255, 0, 0, 1)',
			'allowed' => true
		);
		$settings['gpxChartFill'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxChartFillColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(0, 0, 255, 0.3)',
			'allowed' => true
		);
		$settings['gpxChartTooltipBgColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(0, 0, 0, 0.8)',
			'allowed' => true
		);
		$settings['gpxChartTooltipFontColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(255, 255, 255, 1)',
			'allowed' => true
		);
		$settings['gpxChartIndicatorLineWidth'] = array(
			'type'    => 'absint',
			'default' => '1',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxChartIndicatorLineColor'] = array(
			'type'    => 'string',
			'default' => 'rgba(255, 0, 0, 1)',
			'allowed' => true
		);
		$settings['gpxChartLocator'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxChartLocatorRadius'] = array(
			'type'    => 'absint',
			'default' => '10',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxChartLocatorStroke'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['gpxChartLocatorColor'] = array(
			'type'    => 'string',
			'default' => '#ff0000',
			'allowed' => true
		);
		$settings['gpxChartLocatorWeight'] = array(
			'type'    => 'absint',
			'default' => '2',
			'min'     => 0,
			'max'     => INF
		);
		$settings['gpxChartLocatorFillColor'] = array(
			'type'    => 'string',
			'default' => '#ff0000',
			'allowed' => true
		);
		$settings['gpxChartLocatorFillOpacity'] = array(
			'type'    => 'absfloat',
			'default' => '0.5',
			'min'     => 0,
			'max'     => INF
		);

		/**
		 * Draw
		 */
		$settings['drawStroke'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['drawStrokeColor'] = array(
			'type'    => 'string',
			'default' => '#ff0000',
			'allowed' => true
		);
		$settings['drawStrokeWeight'] = array(
			'type'    => 'absint',
			'default' => '3',
			'min'     => 0,
			'max'     => INF
		);
		$settings['drawStrokeOpacity'] = array(
			'type'    => 'absfloat',
			'default' => '1',
			'min'     => 0,
			'max'     => 1
		);
		$settings['drawLineCap'] = array(
			'type'    => 'string',
			'default' => 'round',
			'allowed' => array('butt', 'round', 'square')
		);
		$settings['drawLineJoin'] = array(
			'type'    => 'string',
			'default' => 'round',
			'allowed' => array('arcs', 'bevel', 'miter', 'miter-clip', 'round')
		);
		$settings['drawFill'] = array(
			'type'    => 'bool',
			'default' => true
		);
		$settings['drawFillColor'] = array(
			'type'    => 'string',
			'default' => '#0000ff',
			'allowed' => true
		);
		$settings['drawFillOpacity'] = array(
			'type'    => 'absfloat',
			'default' => '0.2',
			'min'     => 0,
			'max'     => 1
		);
		$settings['drawFillRule'] = array(
			'type'    => 'string',
			'default' => 'evenodd',
			'allowed' => array('nonzero', 'evenodd')
		);

		return $settings;
	}

	/**
	 * Returns the sanitization rules for the marker settings
	 *
	 * @since 4.0
	 */
	public function marker_settings_sanity() {
		$settings['basemap'] = array(
			'type'    => 'string',
			'default' => 'osm',
			'allowed' => true
		);
		$settings['previewOpacity'] = array(
			'type'    => 'absfloat',
			'default' => 0.5,
			'min'     => 0,
			'max'     => 1
		);
		$settings['lat'] = array(
			'type'    => 'float',
			'default' => 51.477806,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['lng'] = array(
			'type'    => 'float',
			'default' => -0.001472,
			'min'     => -INF,
			'max'     => INF
		);
		$settings['zoom'] = array(
			'type'    => 'absfloat',
			'default' => 10,
			'min'     => 0,
			'max'     => 23
		);
		$settings['icon'] = array(
			'type'    => 'string',
			'default' => '',
			'allowed' => true
		);

		return $settings;
	}

	/**
	 * Returns the sanitization rules for the maps screen settings
	 *
	 * @since 4.8
	 */
	public function maps_screen_settings_sanity() {
		$settings['hiddenColumns'] = array(
			'type'    => 'array',
			'default' => array('created_on', 'updated_by', 'updated_on'),
			'allowed' => array('markers', 'created_by', 'created_on', 'updated_by', 'updated_on', 'used_in', 'shortcode')
		);
		$settings['perPage'] = array(
			'type'    => 'absint',
			'default' => 25,
			'min'     => 1,
			'max'     => 1000
		);

		return $settings;
	}

	/**
	 * Returns the sanitization rules for the markers screen settings
	 *
	 * @since 4.8
	 */
	public function markers_screen_settings_sanity() {
		$settings['hiddenColumns'] = array(
			'type'    => 'array',
			'default' => array('created_on', 'updated_by', 'updated_on'),
			'allowed' => array('address', 'popup', 'created_by', 'created_on', 'updated_by', 'updated_on', 'assigned_to')
		);
		$settings['perPage'] = array(
			'type'    => 'absint',
			'default' => 25,
			'min'     => 1,
			'max'     => 1000
		);

		return $settings;
	}

	/**
	 * Retrieves the basemaps
	 *
	 * @since 4.0
	 * @since 4.7 Added $custom parameter
	 *
	 * @param bool $all (optional) Whether to return all or only available basemaps
	 * @param bool $custom (optional) Whether to include custom basemaps
	 */
	public function get_basemaps($all = false, $custom = true) {
		$db = MMP::get_instance('MMP\DB');

		$osm = esc_html__('Map', 'mmp') . ': &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap ' . esc_html__('contributors', 'mmp') . '</a>';

		$osm_france = esc_html__('Map', 'mmp') . ': &copy; <a href="https://www.openstreetmap.fr" target="_blank">OpenStreetMap France</a> &amp; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap ' . esc_html__('contributors', 'mmp') . '</a>';

		$osm_hot = esc_html__('Map', 'mmp') . ': &copy; <a href="https://www.openstreetmap.fr" target="_blank">OpenStreetMap France</a> &amp; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap ' . esc_html__('contributors', 'mmp') . '</a>, ' . esc_html__('Tiles courtesy of', 'mmp') . ' <a href="https://hotosm.org" target="_blank">Humanitarian OpenStreetMap Team</a>';

		$stamen = esc_html__('Map tiles by', 'mmp') . ' <a href="http://stamen.com" target="_blank">Stamen Design</a>, ' . esc_html__('under', 'mmp') . ' <a href="http://creativecommons.org/licenses/by/3.0" target="_blank">CC BY 3.0</a>. ' . esc_html__('Data by', 'mmp') . ' <a href="http://openstreetmap.org" target="_blank">OpenStreetMap</a>, ' . esc_html__('under', 'mmp') . ' <a href="http://www.openstreetmap.org/copyright" target="_blank">ODbL</a>.';

		$stamen_watercolor = esc_html__('Map tiles by', 'mmp') . ' <a href="http://stamen.com" target="_blank">Stamen Design</a>, ' . esc_html__('under', 'mmp') . ' <a href="http://creativecommons.org/licenses/by/3.0" target="_blank">CC BY 3.0</a>. ' . esc_html__('Data by', 'mmp') . ' <a href="http://openstreetmap.org" target="_blank">OpenStreetMap</a>, ' . esc_html__('under', 'mmp') . ' <a href="http://creativecommons.org/licenses/by-sa/3.0" target="_blank">CC BY SA</a>.';

		$basemap_at = esc_html__('Map', 'mmp') . ': &copy; <a href="https://www.basemap.at" target="_blank">basemap.at</a>';

		$basemaps['osm'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'OpenStreetMap',
			'url'     => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abc',
				'minNativeZoom' => 1,
				'maxNativeZoom' => 19,
				'attribution'   => $osm
			)
		);
		$basemaps['osmBw'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'OpenStreetMap (Black and White)',
			'url'     => 'https://tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abc',
				'minNativeZoom' => 1,
				'maxNativeZoom' => 18,
				'attribution'   => $osm
			)
		);
		$basemaps['osmDe'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'OpenStreetMap (DE)',
			'url'     => 'https://tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abc',
				'minNativeZoom' => 1,
				'maxNativeZoom' => 19,
				'attribution'   => $osm
			)
		);
		$basemaps['osmFrance'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'OpenStreetMap (France)',
			'url'     => 'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abc',
				'minNativeZoom' => 1,
				'maxNativeZoom' => 20,
				'attribution'   => $osm_france
			)
		);
		$basemaps['osmHot'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'OpenStreetMap (HOT)',
			'url'     => 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abc',
				'minNativeZoom' => 1,
				'maxNativeZoom' => 20,
				'attribution'   => $osm_hot
			)
		);
		$basemaps['stamenTerrain'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Terrain)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 18,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenTerrainBackground'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Terrain Background)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 18,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenTerrainLines'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Terrain Lines)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-lines/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 18,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenToner'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Toner)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 20,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenTonerBackground'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Toner Background)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-background/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 20,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenTonerHybrid'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Toner Hybrid)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-hybrid/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 17,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenTonerLines'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Toner Lines)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lines/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 18,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenTonerLite'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Toner Lite)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 0,
				'maxNativeZoom' => 20,
				'attribution'   => $stamen
			)
		);
		$basemaps['stamenWatercolor'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'Stamen (Watercolor)',
			'url'     => 'https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png',
			'options' => array(
				'subdomains'    => 'abcd',
				'minNativeZoom' => 1,
				'maxNativeZoom' => 18,
				'attribution'   => $stamen_watercolor
			)
		);
		$basemaps['basemapAt'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'basemap.at',
			'url'     => 'https://{s}.wien.gv.at/basemap/geolandbasemap/normal/google3857/{z}/{y}/{x}.png',
			'options' => array(
				'bounds'        => array([46.358770, 8.782379], [49.037872, 17.5]),
				'subdomains'    => array('maps', 'maps1', 'maps2', 'maps3', 'maps4'),
				'minNativeZoom' => 1,
				'maxNativeZoom' => 19,
				'attribution'   => $basemap_at
			)
		);
		$basemaps['basemapAtSatellite'] = array(
			'type'    => 1,
			'wms'     => 0,
			'name'    => 'basemap.at (Satellite)',
			'url'     => 'https://{s}.wien.gv.at/basemap/bmaporthofoto30cm/normal/google3857/{z}/{y}/{x}.jpeg',
			'options' => array(
				'bounds'        => array([46.358770, 8.782379], [49.037872, 17.5]),
				'subdomains'    => array('maps', 'maps1', 'maps2', 'maps3', 'maps4'),
				'minNativeZoom' => 1,
				'maxNativeZoom' => 19,
				'attribution'   => $basemap_at
			)
		);

		if ($all || MMP::$settings['googleApiKey']) {
			$basemaps['googleRoadmap'] = array(
				'type'    => 2,
				'name'    => 'Google (Roadmap)',
				'options' => array(
					'type'   => 'roadmap'
				)
			);
			$basemaps['googleSatellite'] = array(
				'type'    => 2,
				'name'    => 'Google (Satellite)',
				'options' => array(
					'type'   => 'satellite'
				)
			);
			$basemaps['googleHybrid'] = array(
				'type'    => 2,
				'name'    => 'Google (Hybrid)',
				'options' => array(
					'type'   => 'hybrid'
				)
			);
			$basemaps['googleTerrain'] = array(
				'type'    => 2,
				'name'    => 'Google (Terrain)',
				'options' => array(
					'type'   => 'terrain'
				)
			);
		}

		if ($all || MMP::$settings['bingApiKey']) {
			$basemaps['bingRoad'] = array(
				'type'    => 3,
				'name'    => 'Bing (Road)',
				'options' => array(
					'imagerySet'    => 'Road',
					'minNativeZoom' => 1,
					'maxNativeZoom' => 19
				)
			);
			$basemaps['bingAerial'] = array(
				'type'    => 3,
				'name'    => 'Bing (Aerial)',
				'options' => array(
					'imagerySet'    => 'Aerial',
					'minNativeZoom' => 1,
					'maxNativeZoom' => 19
				)
			);
			$basemaps['bingAerialLabels'] = array(
				'type'    => 3,
				'name'    => 'Bing (Aerial with Labels)',
				'options' => array(
					'imagerySet'    => 'AerialWithLabels',
					'minNativeZoom' => 1,
					'maxNativeZoom' => 19
				)
			);
			$basemaps['bingCanvasDark'] = array(
				'type'    => 3,
				'name'    => 'Bing (Canvas Dark)',
				'options' => array(
					'imagerySet'    => 'CanvasDark',
					'minNativeZoom' => 1,
					'maxNativeZoom' => 19
				)
			);
			$basemaps['bingCanvasLight'] = array(
				'type'    => 3,
				'name'    => 'Bing (Canvas Light)',
				'options' => array(
					'imagerySet'    => 'CanvasLight',
					'minNativeZoom' => 1,
					'maxNativeZoom' => 19
				)
			);
			$basemaps['bingCanvasGray'] = array(
				'type'    => 3,
				'name'    => 'Bing (Canvas Gray)',
				'options' => array(
					'imagerySet'    => 'CanvasGray',
					'minNativeZoom' => 1,
					'maxNativeZoom' => 19
				)
			);
		}

		if ($all || MMP::$settings['hereApiKey'] || (MMP::$settings['hereAppId'] && MMP::$settings['hereAppCode'])) {
			$basemaps['hereNormalDay'] = array(
				'type'    => 4,
				'name'    => 'HERE (Normal Day)',
				'options' => array(
					'scheme'        => 'normal.day',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 20
				)
			);
			$basemaps['hereNormalNight'] = array(
				'type'    => 4,
				'name'    => 'HERE (Normal Night)',
				'options' => array(
					'scheme'        => 'normal.night',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 20
				)
			);
			$basemaps['hereTerrain'] = array(
				'type'    => 4,
				'name'    => 'HERE (Terrain)',
				'options' => array(
					'scheme'        => 'terrain.day',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 20
				)
			);
			$basemaps['hereSatellite'] = array(
				'type'    => 4,
				'name'    => 'HERE (Satellite)',
				'options' => array(
					'scheme'        => 'satellite.day',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 20
				)
			);
			$basemaps['hereHybrid'] = array(
				'type'    => 4,
				'name'    => 'HERE (Hybrid)',
				'options' => array(
					'scheme'        => 'hybrid.day',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 20
				)
			);
		}

		if ($all || MMP::$settings['tomApiKey']) {
			$basemaps['tom'] = array(
				'type'    => 5,
				'name'    => 'TomTom',
				'options' => array(
					'style'         => 'main',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 22
				)
			);
			$basemaps['tomNight'] = array(
				'type'    => 5,
				'name'    => 'TomTom (Night)',
				'options' => array(
					'style'         => 'night',
					'minNativeZoom' => 0,
					'maxNativeZoom' => 22
				)
			);
		}

		if (!$all) {
			foreach ($basemaps as $key => $basemap) {
				if (in_array($key, MMP::$settings['disabledBasemaps'])) {
					unset($basemaps[$key]);
				}
			}
		}

		if ($custom) {
			foreach ($db->get_all_basemaps() as $custom) {
				$basemaps[$custom->id] = array(
					'type'    => 1,
					'wms'     => absint($custom->wms),
					'name'    => $custom->name,
					'url'     => $custom->url,
					'options' => json_decode($custom->options)
				);
			}
		}

		return $basemaps;
	}

	/**
	 * Retrieves the overlays
	 *
	 * @since 4.7
	 */
	public function get_overlays() {
		$db = MMP::get_instance('MMP\DB');

		$overlays = array();
		foreach ($db->get_all_overlays() as $custom) {
			$overlays[$custom->id] = array(
				'wms'     => absint($custom->wms),
				'name'    => $custom->name,
				'url'     => $custom->url,
				'options' => json_decode($custom->options)
			);
		}

		return $overlays;
	}
}
