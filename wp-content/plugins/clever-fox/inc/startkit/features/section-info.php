<?php 
if ( ! function_exists( 'startkit_info_setting' ) ) :
function startkit_info_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
		$wp_customize->add_section(
			'info_setting', array(
				'title' => esc_html__( 'Info Section', 'startkit' ),
				'panel' => 'startkit_frontpage_sections',
				'priority' => apply_filters( 'startkit_section_priority', 10, 'startkit_info' ),
			)
		);
		// info Hide/ Show Setting // 
	if ( class_exists( 'Startkit_Customizer_Toggle_Control' ) ) {		
	$wp_customize->add_setting( 
		'hide_show_info' , 
			array(
			'default' => '1',
			'capability' => 'edit_theme_options',
		) 
	);
	
	$wp_customize->add_control( new Startkit_Customizer_Toggle_Control( $wp_customize, 
	'hide_show_info', 
		array(
			'label'	      => esc_html__( 'Hide / Show Section', 'startkit' ),
			'section'     => 'info_setting',
			'settings'    => 'hide_show_info',
			'type'        => 'ios', // light, ios, flat
		) 
	));
	}
	/*=========================================
	Info contents Section first
	=========================================*/
	// info icon //
	if ( class_exists( 'Startkit_Customizer_Icon_Picker_Control' ) ) {
	$wp_customize->add_setting(
    	'info_icons',
    	array(
	        'default'			=> __('fa-envelope','startkit'),
			'capability'     	=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(new Startkit_Customizer_Icon_Picker_Control($wp_customize,   
		'info_icons',
		array(
		    'label'   => __('Icon','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_icons',
			'iconset' => 'fa',
		))  
	);
	}
	// info title //
	$wp_customize->add_setting(
    	'info_title',
    	array(
	        'default'			=> __('Design For Business','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);
	$wp_customize->add_control( 
		'info_title',
		array(
		    'label'   => __('Title','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_title',
			'type' => 'text',
		)  
	);
	// info Description //
	$wp_customize->add_setting(
    	'info_description',
    	array(
	        'default'			=> __('The chunk standard of Lorem Ipsum used since the 900s is reproduced below','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);
	$wp_customize->add_control( 
		'info_description',
		array(
		    'label'   => __('Description','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_description',
			'type' => 'textarea',
		)  
	);
	/*=========================================
	Info contents Section second
	=========================================*/
	// info icon //
	if ( class_exists( 'Startkit_Customizer_Icon_Picker_Control' ) ) {
	$wp_customize->add_setting(
    	'info_icons2',
    	array(
	        'default'			=> __('fa-cart-plus','startkit'),
			'capability'     	=> 'edit_theme_options',
		)
	);
	
	$wp_customize->add_control(new Startkit_Customizer_Icon_Picker_Control($wp_customize,   
		'info_icons2',
		array(
		    'label'   => __('Icon','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_icons2',
			'iconset' => 'fa',
		))  
	);
	}
	// info title //
	$wp_customize->add_setting(
    	'info_title2',
    	array(
	        'default'			=> __('Develop For Work','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);
	
	$wp_customize->add_control( 
		'info_title2',
		array(
		    'label'   => __('Title','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_title2',
			'type' => 'text',
		)  
	);
	
	// info Description //
	$wp_customize->add_setting(
    	'info_description2',
    	array(
	        'default'			=> __('The chunk standard of Lorem Ipsum used since the 900s is reproduced below','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);
	
	$wp_customize->add_control( 
		'info_description2',
		array(
		    'label'   => __('Description','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_description2',
			'type' => 'textarea',
		)  
	);
	/*=========================================
	Info contents Section third
	=========================================*/

	// info icon //
	if ( class_exists( 'Startkit_Customizer_Icon_Picker_Control' ) ) {
	$wp_customize->add_setting(
    	'info_icons3',
    	array(
	        'default'			=> __('fa-life-saver','startkit'),
			'capability'     	=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(new Startkit_Customizer_Icon_Picker_Control($wp_customize, 
		'info_icons3',
		array(
		    'label'   => __('Icon','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_icons3',
			'iconset' => 'fa',
		))  
	);
	}
	// info title //
	$wp_customize->add_setting(
    	'info_title3',
    	array(
	        'default'			=> __('Maketing For Blast','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);
	$wp_customize->add_control( 
		'info_title3',
		array(
		    'label'   => __('Title','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_title3',
			'type' => 'text',
		)  
	);
	
	// info Description //
	$wp_customize->add_setting(
    	'info_description3',
    	array(
	        'default'			=> __('The chunk standard of Lorem Ipsum used since the 900s is reproduced below','startkit'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'startkit_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);
	$wp_customize->add_control( 
		'info_description3',
		array(
		    'label'   => __('Description','startkit'),
		    'section' => 'info_setting',
			'settings'=> 'info_description3',
			'type' => 'textarea',
		)  
	);
	}
add_action( 'customize_register', 'startkit_info_setting' );
endif;
?>
<?php
/**
 * Add selective refresh for Front page section section controls.
 */
function startkit_home_info_section_partials( $wp_customize ){
	//info  section first
	$wp_customize->selective_refresh->add_partial( 'info_title', array(
		'selector'            => '#features-list .first h4',
		'settings'            => 'info_title',
		'render_callback'  => 'info_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'info_icons', array(
		'selector'            => '#features-list .first .icon',
		'settings'            => 'info_icons',
		'render_callback'  => 'home_service_section_icon_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'info_description', array(
		'selector'            => '#features-list .first p',
		'settings'            => 'info_description',
		'render_callback'  => 'home_service_section_description_render_callback',
	
	) );
// info second	
	$wp_customize->selective_refresh->add_partial( 'info_title2', array(
		'selector'            => '#features-list .second h4',
		'settings'            => 'info_title2',
		'render_callback'  => 'info_second_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'info_icons2', array(
		'selector'            => '#features-list .second .icon',
		'settings'            => 'info_icons2',
		'render_callback'  => 'info_second_icon_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'info_description2', array(
		'selector'            => '#features-list .second p',
		'settings'            => 'info_description2',
		'render_callback'  => 'info_second_description_render_callback',
	
	) );
	
	// info third	
	$wp_customize->selective_refresh->add_partial( 'info_title3', array(
		'selector'            => '#features-list .third h4',
		'settings'            => 'info_title3',
		'render_callback'  => 'info_third_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'info_icons3', array(
		'selector'            => '#features-list .third .icon',
		'settings'            => 'info_icons3',
		'render_callback'  => 'info_third_icon_render_callback',
	) );
	
	$wp_customize->selective_refresh->add_partial( 'info_description3', array(
		'selector'            => '#features-list .third p',
		'settings'            => 'info_description3',
		'render_callback'  => 'info_third_description_render_callback',
	
	) );
	
}
add_action( 'customize_register', 'startkit_home_info_section_partials' );
// info first
function info_section_title_render_callback() {
	return get_theme_mod( 'info_title' );
}
function home_service_section_icon_render_callback() {
	return get_theme_mod( 'info_icons' );
}

function home_service_section_description_render_callback() {
	return get_theme_mod( 'info_description' );
}


// info second
function info_second_title_render_callback() {
	return get_theme_mod( 'info_title2' );
}
function info_second_icon_render_callback() {
	return get_theme_mod( 'info_icons2' );
}

function info_second_description_render_callback() {
	return get_theme_mod( 'info_description2' );
}
	
// info third
function info_third_title_render_callback() {
	return get_theme_mod( 'info_title3' );
}
function info_third_icon_render_callback() {
	return get_theme_mod( 'info_icons3' );
}

function info_third_description_render_callback() {
	return get_theme_mod( 'info_description3' );
}

?>