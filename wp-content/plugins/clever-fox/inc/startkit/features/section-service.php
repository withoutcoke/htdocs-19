<?php
if ( ! function_exists( 'startkit_service_setting' ) ) :
function startkit_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service Settings Section
	=========================================*/
		$wp_customize->add_section(
			'service_setting', array(
				'title' => esc_html__( 'Service Section', 'startkit' ),
				'priority' => apply_filters( 'startkit_section_priority', 26, 'startkit_pricing' ),
				'panel' => 'startkit_frontpage_sections',
			)
		);
		// service Hide/ Show Setting // 
	if ( class_exists( 'Startkit_Customizer_Toggle_Control' ) ) {		
	$wp_customize->add_setting( 
		'hide_show_service' , 
			array(
			'default' => '1',
			'capability' => 'edit_theme_options',
		) 
	);
	
	$wp_customize->add_control( new Startkit_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_service', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'startkit' ),
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
	        'default'			=> __('Services','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','startkit'),
		    'section' => 'service_setting',
			'settings'   	 => 'service_title',
			'type'           => 'text',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
	        'default'			=> __('Publishing packages and web page editors now use Lorem Ipsum as their default model text','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','startkit'),
		    'section' => 'service_setting',
			'settings'   	 => 'service_description',
			'type'           => 'textarea',
		)  
	);

	// Service content Section // 
	/**
	 * Customizer Repeater for add service
	 */
		if ( class_exists( 'Startkit_Repeater' ) ) {	
		$wp_customize->add_setting( 'service_contents', 
			array(
			 'sanitize_callback' => 'startkit_repeater_sanitize',
			  'default' => json_encode( 
			 array(
				array(
					'title'           => esc_html__( 'Idea Provide', 'startkit' ),
					'text'            => esc_html__( 'Idea is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit..', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-lightbulb-o', 'startkit' ),
					'choice'    => 'customizer_repeater_icon',
					'id'              => 'customizer_repeater_service_001',
					
				),
				array(
					'title'           => esc_html__( 'People Research', 'startkit' ),
					'text'            => esc_html__( 'People is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit.', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-users', 'startkit' ),
					'choice'    => 'customizer_repeater_icon',
					'id'              => 'customizer_repeater_service_002',
				
				),
				array(
					'title'           => esc_html__( 'Business Develop', 'startkit' ),
					'text'            => esc_html__( 'People is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit.', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-briefcase', 'startkit' ),
					'choice'    => 'customizer_repeater_icon',
					'id'              => 'customizer_repeater_service_003',
			
				),
				array(
					'title'           => esc_html__( 'Marketing', 'startkit' ),
					'text'            => esc_html__( 'People is the ipsum consecte tempor incididuntan andolore tumber tur adipisicing elit.', 'startkit' ),
					'icon_value'	  =>  esc_html__( 'fa-user-md', 'startkit' ),
					'choice'    => 'customizer_repeater_icon',
					'id'              => 'customizer_repeater_service_004',
					
				),
			)
			 )
			)
		);
		
		$wp_customize->add_control( 
			new Startkit_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','startkit'),
						'section' => 'service_setting',
						'add_field_label'                   => esc_html__( 'Add New Service', 'startkit' ),
						'item_name'                         => esc_html__( 'Service', 'startkit' ),
						'priority' => 1,
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_checkbox_control' => false,
					) 
				) 
			);
			
		
		}
		//Pro feature
		class Startkit_services__section_upgrade extends WP_Customize_Control {
			public function render_content() { 
				$theme = wp_get_theme(); // gets the current theme
				if ( 'StartKit' == $theme->name){	
			?>
				<a class="customizer_service_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }elseif( 'StartBiz' == $theme->name){ ?>
					<a class="customizer_service_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startbiz-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }elseif( 'Arowana' == $theme->name){ ?>	
					<a class="customizer_service_upgrade_section up-to-pro" href="https://www.nayrathemes.com/arowana-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php }else{ ?>		
					<a class="customizer_service_upgrade_section up-to-pro" href="https://www.nayrathemes.com/startkit-pro/" target="_blank" style="display: none;"><?php _e('Upgrade to Pro','startkit'); ?></a>
				<?php } ?>	
			<?php
			}
		}
		
		
		$wp_customize->add_setting( 'startkit_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
			'sanitize_callback'	=> 'wp_filter_nohtml_kses',
		));
		$wp_customize->add_control(
			new Startkit_services__section_upgrade(
			$wp_customize,
			'startkit_service_upgrade_to_pro',
				array(
					'section'				=> 'service_setting',
					'settings'				=> 'startkit_service_upgrade_to_pro',
				)
			)
		);
}

add_action( 'customize_register', 'startkit_service_setting' );
endif;
?>
<?php
// service selective refresh
function startkit_home_service_section_partials( $wp_customize ){	
	// service title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '#services .section-header h2',
		'settings'            => 'service_title',
		'render_callback'  => 'home_section_service_title_render_callback',
	
	) );
	// service description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '#services .section-header p',
		'settings'            => 'service_description',
		'render_callback'  => 'home_section_service_desc_render_callback',
	
	) );
	// service content
	$wp_customize->selective_refresh->add_partial( 'service_contents', array(
		'selector'            => '#services .servicesss',
	) );
	
	}

add_action( 'customize_register', 'startkit_home_service_section_partials' );

// service title
function home_section_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}
// service description
function home_section_service_desc_render_callback() {
	return get_theme_mod( 'service_description' );
}
?>