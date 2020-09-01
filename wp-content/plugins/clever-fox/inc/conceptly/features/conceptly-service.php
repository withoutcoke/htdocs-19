<?php
function conceptly_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service Settings Section
	=========================================*/
		$wp_customize->add_section(
			'service_setting', array(
				'title' => esc_html__( 'Service Section', 'conceptly-pro' ),
				'priority' => apply_filters( 'conceptly_section_priority', 20, 'conceptly_service' ),
				'panel' => 'conceptly_frontpage_sections',
			)
		);
	// service Hide/ Show Setting // 
	$wp_customize->add_setting( 
		'hide_show_service' , 
			array(
			'default' => esc_html__( '1', 'conceptly-pro' ),
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control( new Conceptly_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_service', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'conceptly-pro' ),
			'section'     => 'service_setting',
			'settings'    => 'hide_show_service',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	
	// Service Header Section // 
	
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('Our Services','conceptly-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'conceptly_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','conceptly-pro'),
		    'section' => 'service_setting',
			'settings'   	 => 'service_title',
			'type'           => 'text',
		)  
	);
	
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('These are the services we provide, these makes us stand apart.','conceptly-pro'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'conceptly_sanitize_text',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','conceptly-pro'),
		    'section' => 'service_setting',
			'settings'   	 => 'service_description',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	
	
	/**
	 * Customizer Repeater for add service
	 */
	
		$wp_customize->add_setting( 'service_contents', 
			array(
			 'sanitize_callback' => 'conceptly_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'default' =>  conceptly_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Conceptly_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','conceptly-pro'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'conceptly-pro' ),
						'item_name'                         => esc_html__( 'Service', 'conceptly-pro' ),
						'priority' => 1,
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
		//Pro feature
		class Conceptly_service__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_service_upgrade_section up-to-pro" href="https://www.nayrathemes.com/conceptly-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','conceptly'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'conceptly_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Conceptly_service__section_upgrade(
			$wp_customize,
			'conceptly_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
					'settings'				=> 'conceptly_service_upgrade_to_pro',
				)
			)
		);
}

add_action( 'customize_register', 'conceptly_service_setting' );

// service selective refresh
function conceptly_home_service_section_partials( $wp_customize ){
		// hide_show_service
	$wp_customize->selective_refresh->add_partial(
		'hide_show_service', array(
			'selector' => '.home-service',
			'container_inclusive' => true,
			'render_callback' => 'service_setting',
			'fallback_refresh' => true,
		)
	);
	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.home-service .section-title h2',
		'settings'            => 'service_title',
		'render_callback'  => 'home_section_service_title_render_callback',
	
	) );
	
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.home-service .section-title p',
		'settings'            => 'service_description',
		'render_callback'  => 'home_section_service_desc_render_callback',
	
	) );
	// service content
		
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.home-service #service-contents'
	) );
	
	}

add_action( 'customize_register', 'conceptly_home_service_section_partials' );

// service title
function home_section_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}

// service description
function home_section_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}