<?php
namespace MMP;

use MMP\Maps_Marker_Pro as MMP;

class Geocoding {
	/**
	 * Geocodes an address
	 *
	 * @since 4.0
	 *
	 * @param string $address Address to geocode
	 * @param string $provider Provider to geocode with
	 */
	public function getLatLng($address, $provider = null) {
		$address = remove_accents($address);
		$provider = ($provider) ? $provider : MMP::$settings['geocodingProvider'];

		switch ($provider) {
			case 'photon':
				return $this->photon($address);
			case 'locationiq':
				return $this->locationiq($address);
			case 'mapquest':
				return $this->mapquest($address);
			case 'google':
				return $this->google($address);
			case 'tomtom':
				return $this->tomtom($address);
			default:
			case 'algolia':
				return $this->algolia($address);
		}
	}

	/**
	 * Geocodes an address using Algolia
	 *
	 * @since 4.0
	 *
	 * @param string $address Address to geocode
	 */
	private function algolia($address) {
		$params = array(
			'query'             => $address,
			'hitsPerPage'       => 1,
			'aroundLatLngViaIP' => MMP::$settings['geocodingAlgoliaAroundLatLngViaIp'],
			'language'          => (MMP::$settings['geocodingAlgoliaLanguage']) ? MMP::$settings['geocodingAlgoliaLanguage'] : substr(get_locale(), 0, 2)
		);
		if (MMP::$settings['geocodingAlgoliaAroundLatLng']) {
			$params['aroundLatLng'] = MMP::$settings['geocodingAlgoliaAroundLatLng'];
		}
		if (MMP::$settings['geocodingAlgoliaCountries']) {
			$params['countries'] = MMP::$settings['geocodingAlgoliaCountries'];
		}
		$url = 'https://places-dsn.algolia.net/1/places/query?' . http_build_query($params, '', '&');

		$response = wp_remote_get($url, array(
			'sslverify' => false,
			'timeout'   => 10,
			'headers'   => array(
				'X-Algolia-Application-Id' => MMP::$settings['geocodingAlgoliaAppId'],
				'X-Algolia-API-Key'        => MMP::$settings['geocodingAlgoliaApiKey']
			)
		));

		if (is_wp_error($response)) {
			return array(
				'success' => false,
				'message' => $response->get_error_message()
			);
		}

		if ($response['response']['code'] !== 200) {
			return array(
				'success' => false,
				'message' => $response['response']['code'] . ' ' . $response['response']['message']
			);
		}

		$body = json_decode($response['body'], true);
		if (!count($body['hits'])) {
			return array(
				'success' => false,
				'message' => esc_html__('No results', 'mmp')
			);
		}

		return array(
			'success' => true,
			'lat'     => $body['hits'][0]['_geoloc']['lat'],
			'lon'     => $body['hits'][0]['_geoloc']['lng'],
			'address' => $this->format_address('algolia', $body['hits'][0])
		);
	}

	/**
	 * Geocodes an address using Photon
	 *
	 * @since 4.0
	 *
	 * @param string $address Address to geocode
	 */
	private function photon($address) {
		if (MMP::$settings['geocodingPhotonLanguage'] === 'automatic') {
			$locale = strtolower(substr(get_locale(), 0, 2));
			$language = (in_array($locale, array('de', 'fr', 'it'))) ? $locale : 'en';
		} else {
			$language = MMP::$settings['geocodingPhotonLanguage'];
		}
		$params = array(
			'q'       => $address,
			'limit'   => 1,
			'lang'    => $language
		);
		if (MMP::$settings['geocodingPhotonBiasLat']) {
			$params['lat'] = MMP::$settings['geocodingPhotonBiasLat'];
		}
		if (MMP::$settings['geocodingPhotonBiasLon']) {
			$params['lon'] = MMP::$settings['geocodingPhotonBiasLon'];
		}
		if (MMP::$settings['geocodingPhotonBounds']) {
			$params['bbox'] = implode(',', array(
				MMP::$settings['geocodingPhotonBoundsLon1'],
				MMP::$settings['geocodingPhotonBoundsLat1'],
				MMP::$settings['geocodingPhotonBoundsLon2'],
				MMP::$settings['geocodingPhotonBoundsLat2']
			));
		}
		if (MMP::$settings['geocodingPhotonFilter']) {
			$params['osm_tag'] = MMP::$settings['geocodingPhotonFilter'];
		}
		$url = 'https://photon.mapsmarker.com/pro/api?'. http_build_query($params, '', '&');

		$response = wp_remote_get($url, array(
			'sslverify' => false,
			'timeout'   => 10
		));

		if (is_wp_error($response)) {
			return array(
				'success' => false,
				'message' => $response->get_error_message()
			);
		}

		if ($response['response']['code'] !== 200) {
			return array(
				'success' => false,
				'message' => $response['response']['code'] . ' ' . $response['response']['message']
			);
		}

		$body = json_decode($response['body'], true);
		if (!count($body['features'])) {
			return array(
				'success' => false,
				'message' => esc_html__('No results', 'mmp')
			);
		}

		return array(
			'success' => true,
			'lat'     => $body['features'][0]['geometry']['coordinates'][1],
			'lon'     => $body['features'][0]['geometry']['coordinates'][0],
			'address' => $this->format_address('photon', $body['features'][0])
		);
	}

	/**
	 * Geocodes an address using LocationIQ
	 *
	 * @since 4.0
	 *
	 * @param string $address Address to geocode
	 */
	private function locationiq($address) {
		if (!MMP::$settings['geocodingLocationIqApiKey']) {
			return array(
				'success' => false,
				'message' => esc_html__('API key missing', 'mmp')
			);
		}

		$params = array(
			'format'          => 'json',
			'key'             => MMP::$settings['geocodingLocationIqApiKey'],
			'q'               => $address,
			'limit'           => 1,
			'addressdetails'  => 1,
			'normalizecity'   => 1,
			'accept-language' => (MMP::$settings['geocodingLocationIqLanguage']) ? MMP::$settings['geocodingLocationIqLanguage'] : substr(get_locale(), 0, 2)
		);
		if (MMP::$settings['geocodingLocationIqBounds']) {
			$params['viewbox'] = implode(',', array(
				MMP::$settings['geocodingLocationIqBoundsLon1'],
				MMP::$settings['geocodingLocationIqBoundsLat1'],
				MMP::$settings['geocodingLocationIqBoundsLon2'],
				MMP::$settings['geocodingLocationIqBoundsLat2']
			));
		}
		$url = 'https://us1.locationiq.com/v1/search.php?'. http_build_query($params, '', '&');

		$response = wp_remote_get($url, array(
			'sslverify' => false,
			'timeout'   => 10
		));

		if (is_wp_error($response)) {
			return array(
				'success' => false,
				'message' => $response->get_error_message()
			);
		}

		if ($response['response']['code'] !== 200) {
			return array(
				'success' => false,
				'message' => $response['response']['code'] . ' ' . $response['response']['message']
			);
		}

		$body = json_decode($response['body'], true);
		if (!count($body)) {
			return array(
				'success' => false,
				'message' => esc_html__('No results', 'mmp')
			);
		}

		return array(
			'success' => true,
			'lat'     => $body[0]['lat'],
			'lon'     => $body[0]['lon'],
			'address' => $this->format_address('locationiq', $body[0]['address'])
		);
	}

	/**
	 * Geocodes an address using MapQuest
	 *
	 * @since 4.0
	 *
	 * @param string $address Address to geocode
	 */
	private function mapquest($address) {
		if (!MMP::$settings['geocodingMapQuestApiKey']) {
			return array(
				'success' => false,
				'message' => esc_html__('API key missing', 'mmp')
			);
		}

		$params = array(
			'key'        => MMP::$settings['geocodingMapQuestApiKey'],
			'location'   => $address,
			'maxResults' => 1
		);
		if (MMP::$settings['geocodingMapQuestBounds']) {
			$params['boundingBox'] = implode(',', array(
				MMP::$settings['geocodingMapQuestBoundsLat1'],
				MMP::$settings['geocodingMapQuestBoundsLon1'],
				MMP::$settings['geocodingMapQuestBoundsLat2'],
				MMP::$settings['geocodingMapQuestBoundsLon2']
			));
		}
		$url = 'https://www.mapquestapi.com/geocoding/v1/address?'. http_build_query($params, '', '&');

		$response = wp_remote_get($url, array(
			'sslverify' => false,
			'timeout'   => 10
		));

		if (is_wp_error($response)) {
			return array(
				'success' => false,
				'message' => $response->get_error_message()
			);
		}

		if ($response['response']['code'] !== 200) {
			return array(
				'success' => false,
				'message' => $response['response']['code'] . ' ' . $response['response']['message']
			);
		}

		$body = json_decode($response['body'], true);
		if (!count($body['results'])) {
			return array(
				'success' => false,
				'message' => esc_html__('No results', 'mmp')
			);
		}

		return array(
			'success' => true,
			'lat'     => $body['results'][0]['locations'][0]['displayLatLng']['lat'],
			'lon'     => $body['results'][0]['locations'][0]['displayLatLng']['lng'],
			'address' => $this->format_address('mapquest', $body['results'][0]['locations'][0])
		);
	}

	/**
	 * Geocodes an address using Google
	 *
	 * @since 4.0
	 *
	 * @param string $address Address to geocode
	 */
	private function google($address) {
		if (MMP::$settings['geocodingGoogleAuthMethod'] === 'clientid-signature' && (!MMP::$settings['geocodingGoogleClient'] || !MMP::$settings['geocodingGoogleSignature'] || !MMP::$settings['geocodingGoogleChannel'])) {
			return array(
				'success' => false,
				'message' => esc_html__('Credentials missing or incomplete', 'mmp')
			);
		} else if (!MMP::$settings['geocodingGoogleApiKey']) {
			return array(
				'success' => false,
				'message' => esc_html__('API key missing', 'mmp')
			);
		}

		$params = array(
			'address' => $address
		);
		if (MMP::$settings['geocodingGoogleAuthMethod'] === 'clientid-signature') {
			$params['client'] = MMP::$settings['geocodingGoogleClient'];
			$params['signature'] = MMP::$settings['geocodingGoogleSignature'];
			$params['channel'] = MMP::$settings['geocodingGoogleChannel'];
		} else {
			$params['key'] = MMP::$settings['geocodingGoogleApiKey'];
		}
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?'. http_build_query($params, '', '&');

		$response = wp_remote_get($url, array(
			'sslverify' => false,
			'timeout'   => 10
		));

		if (is_wp_error($response)) {
			return array(
				'success' => false,
				'message' => $response->get_error_message()
			);
		}

		if ($response['response']['code'] !== 200) {
			return array(
				'success' => false,
				'message' => $response['response']['code'] . ' ' . $response['response']['message']
			);
		}

		$body = json_decode($response['body'], true);
		if ($body['status'] !== 'OK') {
			return array(
				'success' => false,
				'message' => $body['status']
			);
		}

		return array(
			'success' => true,
			'lat'     => $body['results'][0]['geometry']['location']['lat'],
			'lon'     => $body['results'][0]['geometry']['location']['lng'],
			'address' => $body['results'][0]['formatted_address']
		);
	}

	/**
	 * Geocodes an address using TomTom
	 *
	 * @since 4.6
	 *
	 * @param string $address Address to geocode
	 */
	private function tomtom($address) {
		if (!MMP::$settings['geocodingTomTomApiKey']) {
			return array(
				'success' => false,
				'message' => esc_html__('API key missing', 'mmp')
			);
		}

		$language = (MMP::$settings['geocodingTomTomLanguage']) ? MMP::$settings['geocodingTomTomLanguage'] : str_replace('_', '-', get_locale());
		if (!in_array($language, array('NGT', 'NGT-Latn', 'af-ZA', 'ar', 'eu-ES', 'bg-BG', 'ca-ES', 'zh-CN', 'zh-TW', 'cs-CZ', 'da-DK', 'nl-BE', 'nl-NL', 'en-AU', 'en-NZ', 'en-GB', 'en-US', 'et-EE', 'fi-FI', 'fr-CA', 'fr-FR', 'gl-ES', 'de-DE', 'el-GR', 'hr-HR', 'he-IL', 'hu-HU', 'id-ID', 'it-IT', 'kk-KZ', 'lv-LV', 'lt-LT', 'ms-MY', 'No-NO', 'nb-NO', 'pl-PL', 'pt-BR', 'pt-PT', 'ro-RO', 'ru-RU', 'ru-Latn-RU', 'ru-Cyrl-RU', 'sr-RS', 'sk-SK', 'sl-SI', 'es-ES', 'es-419', 'sv-SE', 'th-TH', 'tr-TR', 'uk-UA', 'vi-VN'))) {
			$language = '';
		}
		$params = array(
			'key'      => MMP::$settings['geocodingTomTomApiKey'],
			'limit'    => 1
		);
		if ($language) {
			$params['language'] = $language;
		}
		if (MMP::$settings['geocodingTomTomLat']) {
			$params['lat'] = MMP::$settings['geocodingTomTomLat'];
		}
		if (MMP::$settings['geocodingTomTomLon']) {
			$params['lon'] = MMP::$settings['geocodingTomTomLon'];
		}
		if (MMP::$settings['geocodingTomTomRadius']) {
			$params['radius'] = MMP::$settings['geocodingTomTomRadius'];
		}
		if (MMP::$settings['geocodingTomTomCountrySet']) {
			$params['countrySet'] = MMP::$settings['geocodingTomTomCountrySet'];
		}
		$url = "https://api.tomtom.com/search/2/geocode/{$address}.JSON?" . http_build_query($params, '', '&');

		$response = wp_remote_get($url, array(
			'sslverify' => false,
			'timeout'   => 10
		));

		if (is_wp_error($response)) {
			return array(
				'success' => false,
				'message' => $response->get_error_message()
			);
		}

		if ($response['response']['code'] !== 200) {
			return array(
				'success' => false,
				'message' => $response['response']['code'] . ' ' . $response['response']['message']
			);
		}

		$body = json_decode($response['body'], true);
		if (!count($body['results'])) {
			return array(
				'success' => false,
				'message' => esc_html__('No results', 'mmp')
			);
		}

		return array(
			'success' => true,
			'lat'     => $body['results'][0]['position']['lat'],
			'lon'     => $body['results'][0]['position']['lon'],
			'address' => $this->format_address('tomtom', $body['results'][0])
		);
	}

	/**
	 * Formats an address
	 *
	 * @since 4.0
	 *
	 * @param string $provider Provider the data is from
	 * @param mixed $data Geocoding response data
	 */
	private function format_address($provider, $data) {
		switch ($provider) {
			case 'algolia':
				$language = (MMP::$settings['geocodingAlgoliaLanguage']) ? MMP::$settings['geocodingAlgoliaLanguage'] : substr(get_locale(), 0, 2);
				$administrative = $data['administrative'];
				$city = $data['city'];
				$country = $data['country'];
				$hit = $data;
				if (isset($hit['_highlightResult']['locale_names'][0])) {
					$name = $hit['_highlightResult']['locale_names'][0]['value'] . ',';
				} else if (isset($hit['_highlightResult']['locale_names'][$language][0])) {
					$name = $hit['_highlightResult']['locale_names'][$language][0]['value'] . ',';
				} else {
					$name = '';
				}
				$city = ($city) ? $hit['_highlightResult']['city'][0]['value'] : null;
				$administrative = ($administrative && isset($hit['_highlightResult']['administrative'])) ? $hit['_highlightResult']['administrative'][0]['value'] : null;
				$country = ($country)? $hit['_highlightResult']['country']['value'] : null;
				return strip_tags($name) . ' ' . (($administrative) ? $administrative . ',' : '') . ' ' . (($country) ? '' . $country : '');
			case 'photon':
				$country = (isset($data['properties']['country'])) ? $data['properties']['country'] : null;
				$city = (isset($data['properties']['city'])) ? $data['properties']['city'] : null;
				$housenumber = (isset($data['properties']['housenumber'])) ? $data['properties']['housenumber'] : null;
				$street = (isset($data['properties']['street'])) ? $data['properties']['street'] : null;
				$postcode = (isset($data['properties']['postcode'])) ? $data['properties']['postcode'] : null;
				$state = (isset($data['properties']['state'])) ? $data['properties']['state'] : null;
				$name = (isset($data['properties']['name'])) ? $data['properties']['name'] . ',' : null;
				return $name . ' ' . (($street) ? $street . (($housenumber) ? ' ' . $housenumber : '') . ', ' : '') . (($state) ? $state . ', ' : '') . (($country) ? '' . $country : '');
			case 'locationiq':
				$country = (isset($data['country'])) ? $data['country'] : null;
				$city = (isset($data['city'])) ? $data['city'] : null;
				$house_number = (isset($data['house_number'])) ? $data['house_number'] : null;
				$street = (isset($data['street'])) ? $data['street'] : null;
				$postcode = (isset($data['postcode'])) ? $data['postcode'] : null;
				$state = (isset($data['state'])) ? $data['state'] : null;
				$name = (isset($data['name'])) ? $data['name'] . ',' : null;
				return $name . ' ' . (($street) ? $street . (($house_number) ? ' ' . $house_number : '') . ', ' : '') . (($state) ? $state . ', ' : '') . (($country) ? '' . $country : '');
			case 'mapquest':
				$address = '';
				$address .= (isset($data['adminArea5']) && $data['adminArea5']) ? $data['adminArea5'] . ', ' : '';
				$address .= (isset($data['adminArea4']) && $data['adminArea4']) ? $data['adminArea4'] . ', ' : '';
				$address .= (isset($data['adminArea3']) && $data['adminArea3']) ? $data['adminArea3'] . ', ' : '';
				$address .= (isset($data['adminArea2']) && $data['adminArea2']) ? $data['adminArea2'] . ', ' : '';
				$address .= (isset($data['adminArea1']) && $data['adminArea1']) ? $data['adminArea1'] : '';
				return $address;
			case 'tomtom':
				$address = $data['address']['freeformAddress'];
				$address .= (isset($data['address']['country']) && $address !== $data['address']['country']) ? ', ' . $data['address']['country'] : '';
				return $address;
			default:
				return '';
		}
	}
}
