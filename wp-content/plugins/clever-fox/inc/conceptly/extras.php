<?php
/**
 * Social Icon Default
 *
 */
 function conceptly_get_social_icon_default() {
	return apply_filters(
		'conceptly_get_social_icon_default', json_encode(
			array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),
					'id'              => 'customizer_repeater_social_001',
					
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-linkedin', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),
					'id'              => 'customizer_repeater_social_002',
				
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),
					'id'              => 'customizer_repeater_social_003',
			
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-google-plus', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),								
				)
			)
		)
	);
}
/**
 * Slider Section Default
 *
 */
 
 function conceptly_get_slides_default() {
	return apply_filters(
		'conceptly_get_slides_default', json_encode(
		 array(
			 
            /*Repeater's first item*/
            array("image_url" => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/sliders/slider01.jpg' ,"link" => "#", "title" => "We Build Your", "subtitle" => "Business IDEA", "text" => "There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.", "text2" => "Read More","slide_align" => "left", "id" => "customizer_repeater_00070" ), 
			
            /*Repeater's second item*/
            array("image_url" => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/sliders/slider02.jpg' ,"link" => "#", "title" => "We Build Your", "subtitle" => "Business IDEA", "text" => "There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.", "text2" => "Read More","slide_align" => "center", "id" => "customizer_repeater_00071" ), 
			
            /*Repeater's third item*/
            array("image_url" => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/sliders/slider03.jpg' ,"link" => "#", "title" => "We Build Your", "subtitle" => "Business IDEA", "text" => "There are many variations of passages of Lorem Ipsum available but the majority have suffered injected humour dummy now.", "text2" => "Read More","slide_align" => "right", "id" => "customizer_repeater_00072" ), 
            )
		)
	);
}


/**
 * Service Section Default
 *
 */
function conceptly_get_service_default() {
	return apply_filters(
		'conceptly_get_service_default', json_encode(
			 array(
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/service/service02.jpg',
					'icon_value'	=>  esc_html__( 'fa-cubes', 'conceptly' ),
					'title'           => esc_html__( 'Explore', 'conceptly' ),
					'subtitle'           => esc_html__( ' Customer Assistance ', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations of dummy that passages of Lorem Ipsum available but an the majority have suffered that is  dummy alteration in some.', 'conceptly' ),
					'text2'	  =>  esc_html__( 'Read More ', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/service/service003.jpg',
					'icon_value'	=>  esc_html__( 'fa-american-sign-language-interpreting', 'conceptly' ),
					'title'           => esc_html__( 'Explore', 'conceptly' ),
					'subtitle'           => esc_html__( ' Innovative Ideas', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations of dummy that passages of Lorem Ipsum available but an the majority have suffered that is  dummy alteration in some.', 'conceptly' ),
					'text2'	  =>  esc_html__( 'Read More', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),
					'id'              => 'customizer_repeater_service_002',
				
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/service/service02.jpg',
					'icon_value'	=>  esc_html__( 'fa-laptop', 'conceptly' ),
					'title'           => esc_html__( 'Explore', 'conceptly' ),
					'subtitle'           => esc_html__( 'Global Presence', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations of dummy that passages of Lorem Ipsum available but an the majority have suffered that is  dummy alteration in some.', 'conceptly' ),
					'text2'	  =>  esc_html__( 'Read More', 'conceptly' ),
					'link'	  =>  esc_html__( '#', 'conceptly' ),
					'id'              => 'customizer_repeater_service_003',
			
				),
			)
		)
	);
}

/**
 * Feature Section Default
 *
 */
function conceptly_get_feature_default() {
	return apply_filters(
		'conceptly_get_feature_default', json_encode(
			 array(
				array(
					'title'           => esc_html__( 'Business Growth', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations passages of Lorem Ipsum available but the abo majority have suffered.', 'conceptly' ),
					'icon_value'	=>  esc_html__( 'fa-cubes', 'conceptly' ),
					'id'              => 'customizer_repeater_features_001',
				),
				array(
					'title'           => esc_html__( 'Sustainability', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations passages of Lorem Ipsum available but the abo majority have suffered.', 'conceptly' ),
					'icon_value'	=>  esc_html__( 'fa-cog', 'conceptly' ),
					'id'              => 'customizer_repeater_features_002',
				
				),
				array(
					'title'           => esc_html__( 'Performance', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations passages of Lorem Ipsum available but the abo majority have suffered.', 'conceptly' ),
					'icon_value'	=>  esc_html__( 'fa-american-sign-language-interpreting', 'conceptly' ),
					'id'              => 'customizer_repeater_features_003',
			
				),
				array(
					'title'           => esc_html__( 'Organization', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations passages of Lorem Ipsum available but the abo majority have suffered.', 'conceptly' ),
					'icon_value'	=>  esc_html__( 'fa-area-chart', 'conceptly' ),
					'id'              => 'customizer_repeater_features_004',
					
				),
				array(
					'title'           => esc_html__( 'Saving Strategy', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations passages of Lorem Ipsum available but the abo majority have suffered.', 'conceptly' ),
					'icon_value'	=>  esc_html__( 'fa-coffee', 'conceptly' ),
					'id'              => 'customizer_repeater_features_005',
					
				),
				array(
					'title'           => esc_html__( 'Retirement Planning', 'conceptly' ),
					'text'            => esc_html__( 'There are many variations passages of Lorem Ipsum available but the abo majority have suffered.', 'conceptly' ),
					'icon_value'	=>  esc_html__( 'fa-child', 'conceptly' ),
					'id'              => 'customizer_repeater_features_006',
					
				),
			)
		)
	);
}



/**
 * Sponsor Section Default
 *
 */
 function conceptly_get_sponsers_default() {
	return apply_filters(
		'conceptly_get_sponsers_default', json_encode(
			 array(
				array(
					'link'            => esc_html__( '#', 'conceptly' ),
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/partner/partner01.png',
					'id'              => 'customizer_repeater_sponsers_001',
				),
				array(
					'link'           => esc_html__( '#', 'conceptly' ),
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/partner/partner02.png',
					'id'              => 'customizer_repeater_sponsers_002',
				
				),
				array(
					'link'           => esc_html__( '#', 'conceptly' ),
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/partner/partner03.png',
					'id'              => 'customizer_repeater_sponsers_003',
			
				),
				array(
					'link'           => esc_html__( '#', 'conceptly' ),
					'image_url'       => CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/partner/partner04.png',
					'id'              => 'customizer_repeater_sponsers_004',
					
				)
			) 
		)
	);
}