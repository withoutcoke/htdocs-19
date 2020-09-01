<?php
// Customizer tabs for slider section
if ( ! function_exists( 'hantus_slider_manager_customize_register' ) ) :
function hantus_slider_manager_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'hantus_slider_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'hantus_slider_tabs', array(
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
								'hantus_slider_upgrade_to_pro',
								'slider_opacity',
								'slider_overlay_enable',
								'slide_overlay_color',
								'slide_title_color',
								'slide_sbtitle_color',
								'slide_desc_color',
							),
						),
					),
					
				)
			)
		);
	}
}
add_action( 'customize_register', 'hantus_slider_manager_customize_register' );
endif;


// Customizer tabs blogs
function hantus_blog_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'hantus_blog_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'hantus_blog_tabs', array(
					'section' => 'blog_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'hantus' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_blog',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'hantus' ),
							'icon' => 'header',
							'controls' => array(
								'blog_title',
								'blog_description',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'hantus' ),
							'icon' => 'info',
							'controls' => array(
								'blog_display_num',
							),
						),
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'hantus_blog_customize_register' );

// Customizer tabs for header Search & cart
function hantus_search_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {
		$wp_customize->add_setting(
			'hantus_search_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'hantus_search_tabs', array(
					'section' => 'header_contact_cart',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'hantus' ),
							'icon' => 'cogs',
							'controls' => array(
								'search_header_setting',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Search', 'hantus' ),
							'icon' => 'table',
							'controls' => array(
								'header_search',
							),
						),
					),
				)
			)
		);
	}
}
add_action( 'customize_register', 'hantus_search_customize_register' );

// Customizer tabs for copyright_content

function hantus_copyright_content_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'hantus_copyrights_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'hantus_copyrights_tabs', array(
					'section' => 'footer_copyright',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Settings', 'hantus' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_copyright',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'hantus' ),
							'icon' => 'table',
							'controls' => array(
								'copyright_content'
							),
						),
					),
				)
			)
		);
	}
}
add_action( 'customize_register', 'hantus_copyright_content_customize_register' );


// Customizer tabs for Payment
function hantus_payment_content_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// Pricing Tables Tabs
		$wp_customize->add_setting(
			'hantus_payment_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'hantus_payment_tabs', array(
					'section' => 'footer_icon',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Settings', 'hantus' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_payment',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'hantus' ),
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

add_action( 'customize_register', 'hantus_payment_content_customize_register' );
?>