<?php 
if ( ! function_exists( 'startkit_slider_setting' ) ) :
function startkit_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
		$wp_customize->add_section(
			'slider_setting', array(
				'title' => esc_html__( 'Slider Section', 'startkit' ),
				'panel' => 'startkit_frontpage_sections',
				'priority' => apply_filters( 'startkit_section_priority', 1, 'slider_setting' ),
			)
		);
	// Slider Hide/ Show Setting //
	if ( class_exists( 'Startkit_Customizer_Toggle_Control' ) ) {	
	$wp_customize->add_setting( 
		'hide_show_slider' , 
			array(
			'default' => '1',
			'capability' => 'edit_theme_options',
		) 
	);
	
	$wp_customize->add_control( new Startkit_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_slider', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'startkit' ),
			'section'     => 'slider_setting',
			'settings'    => 'hide_show_slider',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	}
	/**
	 * Customizer Repeater for add slides
	 */
		if ( class_exists( 'Startkit_Repeater' ) ) {	
		$wp_customize->add_setting( 'slider', 
			array(
			 'sanitize_callback' => 'startkit_repeater_sanitize',
			 'default' => json_encode( 
			 array(
					array("image_url" => CLEVERFOX_PLUGIN_URL .'inc/startkit/images/slider/slider02.jpg',
					"link" => "#", 
					"title" => "Strengths of Successful Businesses",
					"text" => "Randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anembarrassing hidden in the middle of text.", 
					"text2" => "Explore More",
					"id" => "customizer_repeater_00071" ), 
				)
			 )
			)
		);
		
		$wp_customize->add_control( 
			new Startkit_Repeater( $wp_customize, 
				'slider', 
					array(
						'label'   => esc_html__('Slide','startkit'),
						'section' => 'slider_setting',
						'add_field_label'                   => esc_html__( 'Add New Slider', 'startkit' ),
						'item_name'                         => esc_html__( 'Slider', 'startkit' ),
						'priority' => 2,
						
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_text2_control'=> true,
						'customizer_repeater_link_control' => true,
						'customizer_repeater_slide_align' => true,
						'customizer_repeater_image_control' => true,	
					) 
				) 
			);
		}	
		
		//Pro feature
		class Startkit_slider__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'StartKit' == $theme->name){	
			?>
					<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }elseif( 'StartBiz' == $theme->name){ ?>
					<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startbiz-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }elseif( 'Arowana' == $theme->name){ ?>	
					<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/arowana-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }else{ ?>		
					<a class="customizer_slider_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php } ?>	
			<?php
			}
		}
		
		
		$wp_customize->add_setting( 'startkit_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Startkit_slider__section_upgrade(
			$wp_customize,
			'startkit_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_setting',
					'settings'				=> 'startkit_slider_upgrade_to_pro',
				)
			)
		);
	//slider opacity
	$wp_customize->add_setting( 
		'slider_opacity' , 
			array(
			'default' => '0.3',
			'capability'     => 'edit_theme_options',
		) 
	);
	
	$wp_customize->add_control( 
	new Cleverfox_Customizer_Range_Slider_Control( $wp_customize, 'slider_opacity', 
		array(
			'section'  => 'slider_setting',
			'settings' => 'slider_opacity',
			'label'    => __( 'Background Opacity','startkit' ),
			'input_attrs' => array(
				'min'    => 0,
				'max'    => 0.9,
				'step'   => 0.1,
				//'suffix' => 'px', //optional suffix
			),
		) ) 
	);	
}
add_action( 'customize_register', 'startkit_slider_setting' );	
endif;
?>