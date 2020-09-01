<?php
if ( ! function_exists( 'startkit_testimonial_setting' ) ) :
function startkit_testimonial_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Testimonial Section Panel
	=========================================*/
		$wp_customize->add_section(
			'testimonial_setting', array(
				'title' => esc_html__( 'Testimonial Section', 'startkit' ),
				'panel' => 'startkit_frontpage_sections',
				'priority' => apply_filters( 'startkit_section_priority', 32, 'startkit_Testimonial' ),
			)
		);
		/*=========================================
	Testimonial Settings Section
	=========================================*/
	if ( class_exists( 'Startkit_Customizer_Toggle_Control' ) ) {
	$wp_customize->add_setting( 
		'hide_show_testimonial' , 
			array(
			'default' => '1',
			'capability'     => 'edit_theme_options',
		) 
	);
	$wp_customize->add_control( new Startkit_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_testimonial', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'startkit' ),
			'section'     => 'testimonial_setting',
			'settings'    => 'hide_show_testimonial',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	}
	/*=========================================
	Testimonial Header Section
	=========================================*/
	// Testimonial Header Section // 
	// Testimonial Title // 
	$wp_customize->add_setting(
    	'testimonial_title',
    	array(
	        'default'			=> __('Testimonial','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	$wp_customize->add_control( 
		'testimonial_title',
		array(
		    'label'   => __('Title','startkit'),
		    'section' => 'testimonial_setting',
			'settings'   	 => 'testimonial_title',
			'type'           => 'text',
		)  
	);
	// Testimonial Description // 
	$wp_customize->add_setting(
    	'testimonial_description',
    	array(
	        'default'			=> __('Publishing packages and web page editors now use Lorem Ipsum as their default model text','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	$wp_customize->add_control( 
		'testimonial_description',
		array(
		    'label'   => __('Description','startkit'),
		    'section' => 'testimonial_setting',
			'settings'   	 => 'testimonial_description',
			'type'           => 'textarea',
		)  
	);
	// Testimonial Content Section // 
	/**
	 * Customizer Repeater for add Testimonial
	 */
		if ( class_exists( 'Startkit_Repeater' ) ) {
		$wp_customize->add_setting( 'testimonial_contents', 
			array(
			 'sanitize_callback' => 'startkit_repeater_sanitize',
			    'default' => json_encode( 
			 array(
				array(
					'title'           => esc_html__( 'Naiomi Watson', 'startkit' ),
					'subtitle'        => esc_html__( 'Lead Interaction Designer', 'startkit' ),
					'text'            => esc_html__( 'Established fact that a reader will be distracted by the readable content of a page when', 'startkit' ),
					'image_url'		  => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/testimonial/testimonial01.png',
					'id'              => 'customizer_repeater_testimonial_001',
				),
				array(
					'title'           => esc_html__( 'Pins kumara', 'startkit' ),
					'subtitle'        => esc_html__( 'Lead Interaction Designer', 'startkit' ),
					'text'            => esc_html__( 'Established fact that a reader will be distracted by the readable content of a page when', 'startkit' ),
					'image_url'		  => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/testimonial/testimonial02.png',
					'id'              => 'customizer_repeater_testimonial_001',
				
				),
				array(
					'title'           => esc_html__( 'Mairala Thare', 'startkit' ),
					'subtitle'        => esc_html__( 'Lead Interaction Designer', 'startkit' ),
					'text'            => esc_html__( 'Established fact that a reader will be distracted by the readable content of a page when', 'startkit' ),
					'image_url'		  => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/testimonial/testimonial03.png',
					'id'              => 'customizer_repeater_testimonial_001',
			
				),
			)
			 )
			)
		);
		$wp_customize->add_control( 
			new Startkit_Repeater( $wp_customize, 
				'testimonial_contents', 
					array(
						'label'   => esc_html__('Testimonial','startkit'),
						'section' => 'testimonial_setting',
						'add_field_label'                   => esc_html__( 'Add New Testimonial', 'startkit' ),
						'item_name'                         => esc_html__( 'Testimonial', 'startkit' ),
						'priority' => 1,
						'customizer_repeater_image_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_subtitle_control' => true,
						'customizer_repeater_text_control' => true,
						
						
				
					) 
				) 
			);
		}
		//Pro feature
		class Startkit_testimonial__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'StartKit' == $theme->name){	
			?>
					<a class="customizer_testimonial_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }elseif( 'StartBiz' == $theme->name){ ?>
					<a class="customizer_testimonial_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startbiz-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }elseif( 'Arowana' == $theme->name){ ?>	
					<a class="customizer_testimonial_upgrade_section up-to-pro" href="https://www.nayrathemes.com/arowana-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }else{ ?>		
					<a class="customizer_testimonial_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php } ?>	
			<?php
			}
		}
		
		
		$wp_customize->add_setting( 'startkit_testimonial_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Startkit_testimonial__section_upgrade(
			$wp_customize,
			'startkit_testimonial_upgrade_to_pro',
				array(
					'section'				=> 'testimonial_setting',
					'settings'				=> 'startkit_testimonial_upgrade_to_pro',
				)
			)
		);
}
add_action( 'customize_register', 'startkit_testimonial_setting' );
endif;
?>
<?php
// Testimonial selective refresh
function startkit_home_testimonial_section_partials( $wp_customize ){

	
	// title
	$wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
		'selector'            => '#testimonial .section-header h2',
		'settings'            => 'testimonial_title',
		'render_callback'  => 'home_section_testimonial_title_render_callback',
	) );
	// description
	$wp_customize->selective_refresh->add_partial( 'testimonial_description', array(
		'selector'            => '#testimonial .section-header p',
		'settings'            => 'testimonial_description',
		'render_callback'  => 'home_section_testimonial_desc_render_callback',
	) );
	// contents
	$wp_customize->selective_refresh->add_partial( 'testimonial_contents', array(
		'selector'            => '#testimonial .tst_contents',
	) );
	}
add_action( 'customize_register', 'startkit_home_testimonial_section_partials' );

// title
function home_section_testimonial_title_render_callback() {
	return get_theme_mod( 'testimonial_title' );
}
// description
function home_section_testimonial_desc_render_callback() {
	return get_theme_mod( 'testimonial_description' );
}
?>