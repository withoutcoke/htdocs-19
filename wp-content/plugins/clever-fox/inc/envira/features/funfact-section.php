<?php
if ( ! function_exists( 'startkit_funfact_setting' ) ) :
function startkit_funfact_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	funfact Section Panel
	=========================================*/
		$wp_customize->add_section(
			'Funfact_setting', array(
				'title' => esc_html__( 'Funfact Section', 'startkit-pro' ),
				'panel' => 'startkit_frontpage_sections',
				'priority' => apply_filters( 'startkit_section_priority', 30, 'startkit_Funfact' ),
			)
		);
	/*=========================================
	Funfact Settings Section
	=========================================*/
	// Slider Hide/ Show Setting // 
	if ( class_exists( 'Startkit_Customizer_Toggle_Control' ) ) {	
	$wp_customize->add_setting( 
		'hide_show_funfact' , 
			array(
			'default' => esc_html__( '1', 'startkit' ),
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control( new Startkit_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_funfact', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'startkit' ),
			'section'     => 'Funfact_setting',
			'settings'    => 'hide_show_funfact',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	}
	
	// funfact content Section // 
	
	
	/**
	 * Customizer Repeater for add funfact
	 */
		$wp_customize->add_setting( 'funfact_contents', 
			array(
			 'sanitize_callback' => 'startkit_repeater_sanitize',
			   'default' => json_encode( 
			 array(
				array(
					'title'           => esc_html__( '9.1', 'startkit' ),
					'subtitle'		  => esc_html__( 'B', 'startkit' ),
					'text'            => esc_html__( 'Users', 'startkit' ),
					'icon_value'	=>  esc_html__( 'fa fa-users', 'startkit' ),
					'id'              => 'customizer_repeater_funfact_001',
				),
				array(
					'title'           => esc_html__( '8.2', 'startkit' ),
					'subtitle'		  => esc_html__( 'B', 'startkit' ),
					'text'            => esc_html__( 'Downloads', 'startkit' ),
					'icon_value'	=>  esc_html__( 'fal fa-download', 'startkit' ),
					'id'              => 'customizer_repeater_funfact_001',
				
				),
				array(
					'title'           => esc_html__( '2.26', 'startkit' ),
					'subtitle'		  => esc_html__( 'B', 'startkit' ),
					'text'            => esc_html__( 'Reviews', 'startkit' ),
					'icon_value'	=>  esc_html__( 'fa fa-star-half-o', 'startkit' ),
					'id'              => 'customizer_repeater_funfact_001',
			
				),
				array(
					'title'           => esc_html__( '9.1', 'startkit' ),
					'subtitle'		  => esc_html__( 'B', 'startkit' ),
					'text'            => esc_html__( 'Users', 'startkit' ),
					'icon_value'	=>  esc_html__( 'fa fa-trophy', 'startkit' ),
					'id'              => 'customizer_repeater_funfact_001',
					
				),
			)
			 )
			)
		);
		
		$wp_customize->add_control( 
			new Startkit_Repeater( $wp_customize, 
				'funfact_contents', 
					array(
						'label'   => esc_html__('Funfact','startkit'),
						'section' => 'Funfact_setting',
						'add_field_label'                   => esc_html__( 'Add New Funfact', 'startkit' ),
						'item_name'                         => esc_html__( 'Funfact', 'startkit' ),
						'priority' => 1,
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_checkbox_control' => false,
					) 
				) 
			);
			
		//Pro feature
		class Startkit_funfact__section_upgrade extends WP_Customize_Control {
			public function render_content() { ?>
			<a class="customizer_funfact_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
			<?php
			}
		}
		
		
		$wp_customize->add_setting( 'startkit_funfact_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Startkit_funfact__section_upgrade(
			$wp_customize,
			'startkit_funfact_upgrade_to_pro',
				array(
					'section'				=> 'Funfact_setting',
					'settings'				=> 'startkit_funfact_upgrade_to_pro',
				)
			)
		);
	// Funfact Background Section // 
	
	
	// Background Image // 
    $wp_customize->add_setting( 
    	'funfact_background_setting' , 
    	array(
			'default' 			=> CLEVERFOX_PLUGIN_URL . 'inc/envira/images/factbg.jpg',
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_url',	
		) 
	);
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize , 'funfact_background_setting' ,
		array(
			'label'          => __( 'Background Image', 'startkit' ),
			'section'        => 'Funfact_setting',
			'settings'   	 => 'funfact_background_setting',
		) 
	));
	$wp_customize->add_setting( 
		'funfact_background_position' , 
			array(
			'default' => __( 'fixed', 'startkit' ),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_select',
		) 
	);
	
	$wp_customize->add_control(
		'funfact_background_position' , 
			array(
				'label'          => __( 'Image Position', 'startkit' ),
				'section'        => 'Funfact_setting',
				'settings'       => 'funfact_background_position',
				'type'           => 'radio',
				'choices'        => 
				array(
					'fixed'=> __( 'Fixed', 'startkit' ),
					'scroll' => __( 'Scroll', 'startkit' )
			)  
		) 
	);
	
}
add_action( 'customize_register', 'startkit_funfact_setting' );
endif;
?>
<?php
// Customizer tabs

function startkit_funfact_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'startkit_funfact_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_funfact_tabs', array(
					'section' => 'Funfact_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_funfact',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'startkit' ),
							'icon' => 'info',
							'controls' => array(
								'funfact_contents',
								'startkit_funfact_upgrade_to_pro',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Background', 'startkit' ),
							'icon' => 'history',
							'controls' => array(
								'funfact_background_setting',
								'funfact_background_position',
							),
						),
					),
				)
			)
		);
	}
}
add_action( 'customize_register', 'startkit_funfact_customize_register' );
// funfact selective refresh
function startkit_home_funfact_section_partials( $wp_customize ){
	// hide show feature
	$wp_customize->selective_refresh->add_partial(
		'hide_show_funfact', array(
			'selector' => '#fun-fact',
			'container_inclusive' => true,
			'render_callback' => 'Funfact_setting',
			'fallback_refresh' => true,
		)
	);
	// funfact
	$wp_customize->selective_refresh->add_partial( 'funfact_contents', array(
		'selector'            => '#fun-fact .text-center',
		'settings'            => 'funfact_contents',
		'render_callback'  => 'home_section_funfact_render_callback',
	
	) );
	
	}

add_action( 'customize_register', 'startkit_home_funfact_section_partials' );

// social icons
function home_section_funfact_render_callback() {
	$funfact_contents =  get_theme_mod( 'funfact_contents' );
	funfact_contents( $funfact_contents, true );
	
}