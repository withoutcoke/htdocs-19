<?php
function conceptly_sponsers_setting( $wp_customize ) {

$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Sponsers Section Panel
	=========================================*/
		$wp_customize->add_section(
			'sponsers_setting', array(
				'title' => esc_html__( 'Sponsor Section', 'conceptly-pro' ),
				'panel' => 'conceptly_frontpage_sections',
				'priority' => apply_filters( 'conceptly_section_priority', 120, 'conceptly_Sponsers' ),
			)
		);
	/*=========================================
	Sponsers Settings Section
	=========================================*/
	// Slider Hide/ Show Setting // 
	$wp_customize->add_setting( 
		'hide_show_sponser' , 
			array(
			'default' => esc_html__( '1', 'conceptly-pro' ),
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control( new Conceptly_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_sponser', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'conceptly-pro' ),
			'section'     => 'sponsers_setting',
			'settings'    => 'hide_show_sponser',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	
	// Sponsers content Section // 
	
	
	/**
	 * Customizer Repeater for add Sponsers
	 */
		$wp_customize->add_setting( 'sponser_contents', 
			array(
			 'sanitize_callback' => 'conceptly_repeater_sanitize',
			 'transport'         => $selective_refresh,
			   'default' => conceptly_get_sponsers_default()
			)
		);
		
		$wp_customize->add_control( 
			new Conceptly_Repeater( $wp_customize, 
				'sponser_contents', 
					array(
						'label'   => esc_html__('Sponsor','conceptly-pro'),
						'section' => 'sponsers_setting',
						'add_field_label'                   => esc_html__( 'Add New Sponsor', 'conceptly-pro' ),
						'item_name'                         => esc_html__( 'Sponsor', 'conceptly-pro' ),
						'priority' => 1,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);
		//Pro feature
		class Conceptly_sponsors__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_sponsors_upgrade_section up-to-pro" href="https://www.nayrathemes.com/conceptly-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','conceptly'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'conceptly_sponsor_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Conceptly_sponsors__section_upgrade(
			$wp_customize,
			'conceptly_sponsor_upgrade_to_pro',
				array(
					'section'				=> 'sponsers_setting',
					'settings'				=> 'conceptly_sponsor_upgrade_to_pro',
				)
			)
		);
		
	// Sponsers Background Section // 
	// Background Image // 
    $wp_customize->add_setting( 
    	'sponsers_background_setting' , 
    	array(
			'default' 			=> CLEVERFOX_PLUGIN_URL .'inc/conceptly/images/bg/partner-bg.jpg',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'conceptly_sanitize_url',	
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'sponsers_background_setting' ,
		array(
			'label'          => __( 'Background Image', 'conceptly-pro' ),
			'section'        => 'sponsers_setting',
			'settings'   	 => 'sponsers_background_setting',
		) 
	));
	

	//background  position 
	$wp_customize->add_setting( 
		'sponsers_background_position' , 
			array(
			'default' => __( 'scroll', 'conceptly-pro' ),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'conceptly_sanitize_select',
		) 
	);
	
	$wp_customize->add_control(
		'sponsers_background_position' , 
			array(
				'label'          => __( 'Image Position', 'conceptly-pro' ),
				'section'        => 'sponsers_setting',
				'settings'       => 'sponsers_background_position',
				'type'           => 'radio',
				'choices'        => 
				array(
					'fixed'=> __( 'Fixed', 'conceptly-pro' ),
					'scroll' => __( 'Scroll', 'conceptly-pro' )
			)  
		) 
	);
}
add_action( 'customize_register', 'conceptly_sponsers_setting' );

// Sponsers selective refresh
function conceptly_home_sponsers_section_partials( $wp_customize ){
	// hide_show_sponser
	$wp_customize->selective_refresh->add_partial(
		'hide_show_sponser', array(
			'selector' => '#our-partners',
			'container_inclusive' => true,
			'render_callback' => 'sponsers_setting',
			'fallback_refresh' => true,
		)
	);
	// Sponsers
	$wp_customize->selective_refresh->add_partial( 'sponser_contents', array(
		'selector'            => '#our-partners .partner-carousel',
	) );
	
	}

add_action( 'customize_register', 'conceptly_home_sponsers_section_partials' );