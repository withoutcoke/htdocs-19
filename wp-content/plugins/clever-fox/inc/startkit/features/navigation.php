<?php
// Customizer tabs for slider section
if ( ! function_exists( 'startkit_slider_manager_customize_register' ) ) :
function startkit_slider_manager_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'startkit_slider_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_slider_tabs', array(
					'section' => 'slider_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_slider',
								
							),
						),
						'Content' => array(
							'nicename' => esc_html__( 'Default', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'slider',
								'startkit_slider_upgrade_to_pro',
								'slider_opacity',
							),
						),
					),
					
				)
			)
		);
	}
}
add_action( 'customize_register', 'startkit_slider_manager_customize_register' );
endif;

// Customizer tabs for header social media & address

function startkit_lite_social_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		
		$wp_customize->add_setting(
			'startkit_social_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_social_tabs', array(
					'section' => 'header_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_social_icon',
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Social Icons', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'social_icons',
								
								
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Address', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'startkit_address_icon',
								'startkit_address',
								
							),
						),
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_lite_social_customize_register' );



// Customizer tabs for header social media & address

function startkit_lite_contact_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		
		$wp_customize->add_setting(
			'startkit_contact_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_contact_tabs', array(
					'section' => 'header_contact',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_contact_infot',
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Email', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'header_email_icon',
								'header_email',
								
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Phone', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'header_phone_icon',
								'header_phone_number',
							),
						),
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_lite_contact_customize_register' );


// Customizer tabs for header Search & cart

function startkit_lite_search_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		
		$wp_customize->add_setting(
			'startkit_search_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_search_tabs', array(
					'section' => 'header_contact_cart',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'cart_header_setting',
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Search', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'header_search',
								
								
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Cart', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'header_cart',
								
							),
						),
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_lite_search_customize_register' );

// book now button control tabs

function startkit_lite_booknow_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		
		$wp_customize->add_setting(
			'startkit_booknow_tabs', array(
				'sanitize_callback' => 'startkit_tabs_title',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_booknow_tabs', array(
					'section' => 'header_booknow',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'booknow_setting',
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Book Now', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'header_btn_icon',
								'header_btn_lbl',
								'header_btn_link',
							),
						),
						
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_lite_booknow_customize_register' );
// Customizer tabs for copyright_content

function startkit_lite_copyright_content_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'startkit_copyrights_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_copyrights_tabs', array(
					'section' => 'footer_copyright',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Settings', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_copyright',		
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'copyright_content',
							),
						),
						
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_lite_copyright_content_customize_register' );


// Customizer tabs for Payment
function startkit_lite_payment_content_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'startkit_copyright_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_copyright_tabs', array(
					'section' => 'footer_icon',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Settings', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_payment',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'startkit' ),
							'icon' => 'table',
							'controls' => array(
								'footer_Payment_icon',
							),
						),
						
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_lite_payment_content_customize_register' );

// Customizer tabs service

function startkit_servicess_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'startkit_servicess_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_servicess_tabs', array(
					'section' => 'service_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_service',
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'startkit' ),
							'icon' => 'header',
							'controls' => array(
								'service_title',
								'service_description',
								
								
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'startkit' ),
							'icon' => 'info',
							'controls' => array(
								'service_contents',	
								'startkit_service_upgrade_to_pro',	
							),
						),
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_servicess_customize_register' );

// Customizer testimonial
function startkit_testimonial_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'startkit_testimonial_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'startkit_testimonial_tabs', array(
					'section' => 'testimonial_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'startkit' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_testimonial',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'startkit' ),
							'icon' => 'header',
							'controls' => array(
								'testimonial_title',
								'testimonial_description',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'startkit' ),
							'icon' => 'info',
							'controls' => array(
								'testimonial_contents',
								'startkit_testimonial_upgrade_to_pro',
							),
						),	
						
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'startkit_testimonial_customize_register' );