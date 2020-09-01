<?php
function conceptly_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
		$wp_customize->add_section(
			'slider_setting', array(
				'title' => esc_html__( 'Slider Section', 'conceptly-pro' ),
				'panel' => 'conceptly_frontpage_sections',
				'priority' => apply_filters( 'conceptly_section_priority', 1, 'slider_setting' ),
			)
		);
	
	/**
	 * Customizer Repeater for add slides
	 */
	
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'conceptly_repeater_sanitize',
			 //'transport'         => $selective_refresh,
			  'default' => conceptly_get_slides_default()
			)
		);
		
		 $wp_customize->add_control( 
			 new Conceptly_Repeater( $wp_customize, 
				 'slider', 
					 array(
						 'label'   => esc_html__('Slide','conceptly-pro'),
						 'section' => 'slider_setting',
						 'add_field_label'                   => esc_html__( 'Add New Slider', 'conceptly-pro' ),
						 'item_name'                         => esc_html__( 'Slider', 'conceptly-pro' ),
						 'priority' => 2,
						
						 'customizer_repeater_icon_control' => false,
						 'customizer_repeater_title_control' => true,
						 'customizer_repeater_subtitle_control' => true,
						 'customizer_repeater_text_control' => true,
						 'customizer_repeater_text2_control'=> true,
						 'customizer_repeater_link_control' => true,
						 'customizer_repeater_slide_align' => true,
						 'customizer_repeater_checkbox_control' => true,
						 'customizer_repeater_image_control' => true,	
					 ) 
				 ) 
			 );
	
		//Pro feature
		class Conceptly_slider__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
			?>
				<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/conceptly-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','conceptly'); ?></a>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'conceptly_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Conceptly_slider__section_upgrade(
			$wp_customize,
			'conceptly_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
					'settings'				=> 'conceptly_slider_upgrade_to_pro',
				)
			)
		);
		
	// Slider Hide/ Show Setting // 
	$wp_customize->add_setting( 
		'hide_show_slider' , 
			array(
			'default' => esc_html__( '1', 'conceptly-pro' ),
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control( new Conceptly_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_slider', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'conceptly-pro' ),
			'section'     => 'slider_setting',
			'settings'    => 'hide_show_slider',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	
	
	
	//slider opacity
	
	// Slider Text Caption // 
	$wp_customize->add_setting( 
		'slider_opacity' , 
			array(
			'default' => '',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) 
	);

	$wp_customize->add_control( 
	new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'slider_opacity', 
		array(
			'section'  => 'slider_setting',
			'settings' => 'slider_opacity',
			'label'    => __( 'Background Opacity','conceptly-pro' ),
			'input_attrs' => array(
				'min'    => 0,
				'max'    => 0.9,
				'step'   => 0.1,
				//'suffix' => 'px', //optional suffix
			),
		) ) 
	);
}
add_action( 'customize_register', 'conceptly_slider_setting' );

// slider selective refresh
function conceptly_home_slider_section_partials( $wp_customize ){

	// hide_show_slider
	$wp_customize->selective_refresh->add_partial(
		'hide_show_slider', array(
			'selector' => '.header-slider',
			'container_inclusive' => true,
			'render_callback' => 'slider_setting',
			'fallback_refresh' => true,
		)
	);
	// slider
	$wp_customize->selective_refresh->add_partial( 'slider', array(
		'selector'            => '#slider .header-slider figure',
		'settings'            => 'slider',
		'render_callback'  => 'home_section_slider_render_callback',
	
	) );
	
	}

add_action( 'customize_register', 'conceptly_home_slider_section_partials' );

// Slider
function home_section_slider_render_callback() {
	return get_theme_mod( 'slider' );
}
?>