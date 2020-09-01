<?php
function hantus_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service Settings Section
	=========================================*/
		$wp_customize->add_section(
			'service_setting', array(
				'title' => esc_html__( 'Service Section', 'hantus' ),
				'priority' => apply_filters( 'hantus_section_priority', 25, 'hantus_service' ),
				'panel' => 'hantus_frontpage_sections',
			)
		);
	// service Hide/ Show Setting // 
	if ( class_exists( 'Hantus_Customizer_Toggle_Control' ) ) {	
	$wp_customize->add_setting( 
		'hide_show_service' , 
			array(
			'default' => esc_html__( '1', 'hantus' ),
			'sanitize_callback' => 'sanitize_text_field',
			'capability' => 'edit_theme_options',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control( new Hantus_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_service', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'hantus' ),
			'section'     => 'service_setting',
			'settings'    => 'hide_show_service',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	}
	// Service Header Section // 
	
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
	        'default'			=> __('Our Services','hantus'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'hantus_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','hantus'),
		    'section' => 'service_setting',
			'settings'   	 => 'service_title',
			'type'           => 'text',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('These are the services we provide, these makes us stand apart.','hantus'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'hantus_sanitize_text',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','hantus'),
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
			 'sanitize_callback' => 'hantus_repeater_sanitize',
			 'transport'         => $selective_refresh,
			 'default' => json_encode( 
			 array(
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL . 'inc/hantus/images/service/service01.png',
					'title'           => esc_html__( 'Oil Massage', 'hantus' ),
					'subtitle'            => esc_html__( '$57.99', 'hantus' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of  the printing and typesetting.', 'hantus' ),
					'text2'	  =>  esc_html__( 'Book now', 'hantus' ),
					'link'	  =>  esc_html__( '#', 'hantus' ),
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL . 'inc/hantus/images/service/service02.png',
					'title'           => esc_html__( 'Skin Care', 'hantus' ),
					'subtitle'            => esc_html__( '$57.99', 'hantus' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of  the printing and typesetting.', 'hantus' ),
					'text2'	  =>  esc_html__( 'Book now', 'hantus' ),
					'link'	  =>  esc_html__( '#', 'hantus' ),
					'id'              => 'customizer_repeater_service_002',
				
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL . 'inc/hantus/images/service/service03.png',
					'title'           => esc_html__( 'Natural Relaxation', 'hantus' ),
					'subtitle'            => esc_html__( '$57.99', 'hantus' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of  the printing and typesetting.', 'hantus' ),
					'text2'	  =>  esc_html__( 'Book now', 'hantus' ),
					'link'	  =>  esc_html__( '#', 'hantus' ),
					'id'              => 'customizer_repeater_service_003',
			
				),
				array(
					'image_url'       => CLEVERFOX_PLUGIN_URL . 'inc/hantus/images/service/service04.png',
					'title'           => esc_html__( 'Nails Design', 'hantus' ),
					'subtitle'            => esc_html__( '$57.99', 'hantus' ),
					'text'            => esc_html__( 'Lorem Ipsum is simply dummy text of  the printing and typesetting.', 'hantus' ),
					'text2'	  =>  esc_html__( 'Book now', 'hantus' ),
					'link'	  =>  esc_html__( '#', 'hantus' ),
					'id'              => 'customizer_repeater_service_004',
					
				),
			)
			 )
			)
		);
		
		$wp_customize->add_control( 
			new hantus_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','hantus'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'hantus' ),
						'item_name'                         => esc_html__( 'Service', 'hantus' ),
						'priority' => 1,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
			
			//Pro feature
		class Hantus_service__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_service_upgrade_section up-to-pro" href="https://www.nayrathemes.com/hantus-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','hantus'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'hantus_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Hantus_service__section_upgrade(
			$wp_customize,
			'hantus_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
					'settings'				=> 'hantus_service_upgrade_to_pro',
				)
			)
		);
}

add_action( 'customize_register', 'hantus_service_setting' );
?>
<?php
// Customizer tabs

function hantus_servicess_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'hantus_servicess_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'hantus_servicess_tabs', array(
					'section' => 'service_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'hantus' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_service',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'hantus' ),
							'icon' => 'header',
							'controls' => array(
								'service_title',
								'service_description',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'hantus' ),
							'icon' => 'info',
							'controls' => array(
								'service_contents',
								'hantus_service_upgrade_to_pro'
							),
						),
						
					),
				)
			)
		);
	}
}
add_action( 'customize_register', 'hantus_servicess_customize_register' );
// service selective refresh
function hantus_home_service_section_partials( $wp_customize ){
		// hide_show_service
	$wp_customize->selective_refresh->add_partial(
		'hide_show_service', array(
			'selector' => '#services',
			'container_inclusive' => true,
			'render_callback' => 'service_setting',
			'fallback_refresh' => true,
		)
	);
	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '#services .service-section h2',
		'settings'            => 'service_title',
		'render_callback'  => 'home_section_service_title_render_callback',
	
	) );
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '#services .service-section p',
		'settings'            => 'service_description',
		'render_callback'  => 'home_section_service_desc_render_callback',
	
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '.servicesss',
	
	) );
	
	}

add_action( 'customize_register', 'hantus_home_service_section_partials' );

// service title
function home_section_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}


// service description
function home_section_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}