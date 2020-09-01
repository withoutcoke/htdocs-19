<?php
// Customizer tabs

function Cleverfox_conceptly_tabs_customize_register( $wp_customize ) {
	if ( class_exists( 'Cleverfox_Customize_Control_Tabs' ) ) {

		// feature Tables Tabs
		$wp_customize->add_setting(
			'conceptly_feature_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_feature_tabs', array(
					'section' => 'features_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_feature'
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'conceptly-pro' ),
							'icon' => 'header',
							'controls' => array(
								'features_title',
								'features_description',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'feature_content',
								'conceptly_feature_upgrade_to_pro'
							),
						)						
					),
				)
			)
		);
		
		// info section Tabs
		$wp_customize->add_setting(
			'conceptly_info_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_info_tabs', array(
					'section' => 'info_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_info',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'First', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'infos_first_icon_setting',
								'info_title',	
								'info_description',	
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Second', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'infos_second_icon_setting',
								'info_title2',	
								'info_description2',	
							),
						),
						'third' => array(
							'nicename' => esc_html__( 'Third', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'infos_third_icon_setting',
								'info_title3',	
								'info_description3',	
							),
						),
					),
				)
			)
		);
		
		// CTA Tables Tabs
		$wp_customize->add_setting(
			'conceptly_call_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_call_tabs', array(
					'section' => 'call_action_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_cta',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'header',
							'controls' => array(
								'call_to_action_title',
								'call_to_action_description',
								'cta_icon',
								'call_action_button_label',
								'call_action_button_link',
								'call_action_button_target',
							),
						),
						'third' => array(
							'nicename' => esc_html__( 'Bg', 'conceptly-pro' ),
							'icon' => 'history',
							'controls' => array(
								'call_action_background_setting',
								'cta_background_position'
							),
						),
						
					),
				)
			)
		);
		
		// Service Tabs
		$wp_customize->add_setting(
			'conceptly_servicess_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_servicess_tabs', array(
					'section' => 'service_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_service',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'conceptly-pro' ),
							'icon' => 'header',
							'controls' => array(
								'service_title',
								'service_description',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'service_contents',
								'conceptly_service_upgrade_to_pro'
							),
						),
						
					),
				)
			)
		);
		
		// Slider Tabs
		$wp_customize->add_setting(
			'conceptly_slider_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_slider_tabs', array(
					'section' => 'slider_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_slider',
								
							),
						),
						'Content' => array(
							'nicename' => esc_html__( 'Default', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'slider',
								'conceptly_slider_upgrade_to_pro',
								'slider_opacity'
							),
						),
					),
					
				)
			)
		);
		
		// Sponsers Tabs
		$wp_customize->add_setting(
			'conceptly_sponser_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_sponser_tabs', array(
					'section' => 'sponsers_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_sponser',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'sponser_contents',
								'conceptly_sponsor_upgrade_to_pro'
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Background', 'conceptly-pro' ),
							'icon' => 'histry',
							'controls' => array(
								'sponsers_background_setting',
								'sponsers_background_position',
							),
						),
					),
				)
			)
		);
		
		//Latest News Tabs
		$wp_customize->add_setting(
			'conceptly_blog_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_blog_tabs', array(
					'section' => 'blog_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_blog',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Header', 'conceptly-pro' ),
							'icon' => 'header',
							'controls' => array(
								'blog_title',
								'blog_description',
							),
						),
						'second' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'info',
							'controls' => array(
								'blog_category_id',
								'blog_display_num',
								'blog_display_col'
							),
						),
					),
				)
			)
		);
		
		// Footer copyright section Tabs
		$wp_customize->add_setting(
			'conceptly_copyrights_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_copyrights_tabs', array(
					'section' => 'footer_copyright',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Settings', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_copyright',
								
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'copyright_content',
							),
						),
					),
					
				)
			)
		);
		
		// footer payment Tabs
		$wp_customize->add_setting(
			'conceptly_copyright_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_copyright_tabs', array(
					'section' => 'footer_icon',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Settings', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_payment',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Content', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'footer_Payment_icon',
							),
						),
						
						
					),
				)
			)
		);
		
		$wp_customize->add_setting(
			'conceptly_contacts_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_contacts_tabs', array(
					'section' => 'header_contact',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Phone', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'hide_show_contact_infot',
								'header_phone_icon',
								'header_phone_number',
							),
						),
									
						'first' => array(
							'nicename' => esc_html__( 'Email', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'hide_show_email_infot',
								'header_email_icon',
								'header_email',
								
							),
						),
						'second' => array(
						'nicename' => esc_html__( 'FAQ', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'hide_show_faq',
								'header_faq_icon',
								'header_faq',
								
							),
						),
					),
				)
			)
		);
		
		$wp_customize->add_setting(
			'conceptly_social_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_social_tabs', array(
					'section' => 'header_setting',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'cogs',
							'controls' => array(
								'hide_show_social_icon',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Social-Icon', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'social_icons',
							),
						),
						
					),
				)
			)
		);
	
		$wp_customize->add_setting(
			'conceptly_get_btn_tabs', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Cleverfox_Customize_Control_Tabs(
				$wp_customize, 'conceptly_get_btn_tabs', array(
					'section' => 'header_get_button',
					'tabs' => array(
						'general' => array(
							'nicename' => esc_html__( 'Setting', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'hide_show_get_button',
							),
						),
						'first' => array(
							'nicename' => esc_html__( 'Button', 'conceptly-pro' ),
							'icon' => 'table',
							'controls' => array(
								'header_get',
								'header_btn_link'
							),
						),
												
					),
				)
			)
		);
	}
}

add_action( 'customize_register', 'Cleverfox_conceptly_tabs_customize_register' );