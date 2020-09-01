<?php

// Global variables define
define('APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI', get_template_directory_uri());
define('APPOINTMENT_GREEN_TEMPLATE_DIR_URI', get_stylesheet_directory_uri());
define('APPOINTMENT_GREEN_TEMPLATE_DIR', trailingslashit(get_stylesheet_directory()));

if (!function_exists('wp_body_open')) {

    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action('wp_body_open');
    }

}

add_action('wp_enqueue_scripts', 'appointment_green_theme_css', 999);

function appointment_green_theme_css() {
    wp_enqueue_style('appointment-green-parent-style', APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI . '/style.css');
    wp_enqueue_style('bootstrap-style', APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI . '/css/bootstrap.css');
    wp_enqueue_style('appointment-green-theme-menu', APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI . '/css/theme-menu.css');
    wp_enqueue_style('appointment-green-default-css', APPOINTMENT_GREEN_TEMPLATE_DIR_URI . "/css/default.css");
    wp_enqueue_style('appointment-green-element-style', APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI . '/css/element.css');
    wp_enqueue_style('appointment-green-media-responsive', APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI . '/css/media-responsive.css');
    wp_dequeue_style('appointment-default', APPOINTMENT_GREEN_PARENT_TEMPLATE_DIR_URI . '/css/default.css');
}

function appointment_green_setup() {
    add_theme_support('title-tag');
    require( APPOINTMENT_GREEN_TEMPLATE_DIR . '/functions/customizer/customizer-copyright.php' );
    require( APPOINTMENT_GREEN_TEMPLATE_DIR . '/functions/customizer/customizer-header-layout.php');
    load_theme_textdomain('appointment-green', APPOINTMENT_GREEN_TEMPLATE_DIR . '/languages');
    require( APPOINTMENT_GREEN_TEMPLATE_DIR . '/functions/template-tag.php' );
}

add_action('after_setup_theme', 'appointment_green_setup');

/**
 * @uses appointment_green_default_data() Get default data
 */
function appointment_green_default_data() {

    $header_setting = wp_parse_args(get_option('appointment_options', array()), appointment_theme_setup_data());
//print_r($header_setting);
    if ((!has_custom_logo() && $header_setting['enable_header_logo_text'] == 'nomorenow' ) || $header_setting['enable_header_logo_text'] == 1 || $header_setting['upload_image_logo'] != '') {

        $array_new = array(
            'header_column_layout_setting' => 'default',
            'service_rotate_layout_section' => 'default',
        );
    } else {
        $array_new = array(
            'header_column_layout_setting' => 'column',
            'service_rotate_layout_section' => 'rotate',
        );
    }
    $array_old = array(
        // general settings
        'footer_copyright_text' => '<p>' . __('<a href="https://wordpress.org">Proudly powered by WordPress</a> | Theme: <a href="https://webriti.com" rel="nofollow">Appointment Green</a> by Webriti', 'appointment-green') . '</p>',
        'footer_menu_bar_enabled' => '',
        'footer_social_media_enabled' => '',
        'footer_social_media_facebook_link' => '#',
        'footer_facebook_media_enabled' => 1,
        'footer_social_media_twitter_link' => '#',
        'footer_twitter_media_enabled' => 1,
        'footer_social_media_linkedin_link' => '#',
        'footer_linkedin_media_enabled' => 1,
        'footer_social_media_googleplus_link' => '#',
        'footer_googleplus_media_enabled' => 1,
        'footer_social_media_skype_link' => '#',
        'footer_skype_media_enabled' => 1,
    );
    return $result = array_merge($array_new, $array_old);
}
