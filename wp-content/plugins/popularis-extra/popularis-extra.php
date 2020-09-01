<?php

/*
 * Plugin Name: Popularis Extra
 * Plugin URI: https://populariswp.com/
 * Description: Extra addon for Popularis Theme
 * Version: 1.0.11
 * Author: Themes4WP
 * Author URI: https://themes4wp.com/
 * License: GPL-2.0+
 * WC requires at least: 3.3.0
 * WC tested up to: 3.9.2
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('add_action')) {
    die('Nothing to do...');
}

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$plugin_version = $plugin_data['Version'];
// Define POPULARIS_EXTRA_CURRENT_VERSION.
if (!defined('POPULARIS_EXTRA_CURRENT_VERSION')) {
    define('POPULARIS_EXTRA_CURRENT_VERSION', $plugin_version);
}

//plugin constants
define('POPULARIS_EXTRA_PATH', plugin_dir_path(__FILE__));
define('POPULARIS_EXTRA_PLUGIN_BASE', plugin_basename(__FILE__));
define('POPULARIS_EXTRA_PLUGIN_URL', plugins_url('/', __FILE__));


add_action('plugins_loaded', 'popularis_extra_load_textdomain');

function popularis_extra_load_textdomain() {
    load_plugin_textdomain('popularis-extra', false, basename(dirname(__FILE__)) . '/languages/');
}

function popularis_extra_scripts() {
    wp_enqueue_style('popularis-extra', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), POPULARIS_EXTRA_CURRENT_VERSION);
}

add_action('wp_enqueue_scripts', 'popularis_extra_scripts');


require_once( POPULARIS_EXTRA_PATH . 'library/extra-shortcodes/shortcodes.php' );

include_once( POPULARIS_EXTRA_PATH . 'library/extra-widgets.php' );

/**
 * Check Elementor plugin
 */
function popularis_extra_check_for_elementor() {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return is_plugin_active('elementor/elementor.php');
}

/**
 * Check Elementor PRO plugin
 */
function popularis_extra_check_for_elementor_pro() {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return is_plugin_active('elementor-pro/elementor-pro.php');
}

/**
 * Register Elementor features
 */
if (popularis_extra_check_for_elementor()) {
    include_once( POPULARIS_EXTRA_PATH . 'library/extra-elementor/elementor-widgets.php' );
    if (!popularis_extra_check_for_elementor_pro()) {
        include_once( POPULARIS_EXTRA_PATH . 'library/extra-elementor/elementor-shortcode.php' );
    }
}

/**
 * Check Popularis PRO plugin
 */
function popularis_extra_check_for_popularis_pro() {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return is_plugin_active('popularis-pro/popularis-pro.php');
}

/**
 * Register Popularis PRO features
 */
if (!popularis_extra_check_for_popularis_pro()) {
    include_once( POPULARIS_EXTRA_PATH . 'includes/panel/demos-pro.php' );
}
/**
 * Register demo import
 */
$theme = wp_get_theme();
if ('Popularis' == $theme->name || 'popularis' == $theme->template ) {
    require_once( POPULARIS_EXTRA_PATH . 'includes/panel/demos.php' );
    require_once( POPULARIS_EXTRA_PATH . 'includes/wizard/wizard.php' );
    require_once( POPULARIS_EXTRA_PATH . 'includes/notify/notify.php' );
}

/**
 * Add Metadata on plugin activation.
 */
function popularis_extra_activate() {
    add_site_option('popularis_extra_active_time', time());
    add_option('popularis_plugin_do_activation_redirect', true);
}

register_activation_hook(__FILE__, 'popularis_extra_activate');

/**
 * Remove Metadata on plugin Deactivation.
 */
function popularis_extra_deactivate() {
    delete_option('popularis_extra_active_time');
}

register_deactivation_hook(__FILE__, 'popularis_extra_deactivate');



add_action('admin_init', 'popularis_extra_plugin_redirect');

/**
 * Redirect after plugin activation
 */
function popularis_extra_plugin_redirect() {
    if (get_option('popularis_plugin_do_activation_redirect', false)) {
        delete_option('popularis_plugin_do_activation_redirect');
        if (!is_network_admin() || !isset($_GET['activate-multi'])) {
            wp_redirect('themes.php?page=popularis-extra-panel-install-demos');
        }
    }
}

/**
 * Plugin page links
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'popularis_extra_action_links');

function popularis_extra_action_links($links) {
    $links['install_demos'] = sprintf('<a href="%1$s" class="install-demos">%2$s</a>', esc_url(admin_url('themes.php?page=popularis-extra-panel-install-demos')), esc_html__('Install Demos', 'popularis-extra'));
    if (!popularis_extra_check_for_popularis_pro()) {
        $links['go_pro'] = sprintf('<a href="%1$s" target="_blank" class="elementor-plugins-gopro">%2$s</a>', esc_url('https://populariswp.com/product/popularis-pro/'), esc_html__('Go Pro', 'popularis-extra'));
    }
    return $links;
}

remove_filter( 'wp_import_post_meta', 'Elementor\Compatibility::on_wp_import_post_meta');
remove_filter( 'wxr_importer.pre_process.post_meta', 'Elementor\Compatibility::on_wxr_importer_pre_process_post_meta');