<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Custom widgets for Elementor
 *
 * This class handles custom widgets for Elementor
 *
 * @since 1.0.0
 */
final class Popularis_Elementor_Extension {

    private static $_instance = null;

    public static function instance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Registers widgets in Elementor
     *
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets() {
        /** @noinspection PhpIncludeInspection */

        require_once POPULARIS_EXTRA_PATH . 'library/extra-elementor/elementor-widgets/posts.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Popularis_Extra_Posts());

        require_once POPULARIS_EXTRA_PATH . 'library/extra-elementor/elementor-widgets/text-block.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Popularis_Text_Block());
    }

    /**
     * Registers widgets scripts
     *
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_scripts() {
        wp_register_script(
                'popularis-animate-scripts',
                POPULARIS_EXTRA_PLUGIN_URL . 'library/extra-elementor/elementor-widgets/js/animate.min.js',
                [
                    'jquery'
                ],
                POPULARIS_EXTRA_CURRENT_VERSION,
                true
        );
    }

    /**
     * Enqueue widgets scripts in preview mode, as later calls in widgets render will not work,
     * as it happens in admin env
     *
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_scripts_preview() {
        wp_enqueue_script('popularis-animate-scripts');
    }

    /**
     * Registers widgets styles
     *
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_styles() {
        wp_register_style('popularis-extra-frontend', POPULARIS_EXTRA_PLUGIN_URL . 'lib/elementor/widgets/css/frontend.css');
    }

    /**
     * Widget constructor.
     *
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct() {
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        // Register Widget Styles
        // add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
        // Register Widget Scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
        // Enqueue ALL Widgets Scripts for preview
        add_action('elementor/preview/enqueue_scripts', [$this, 'widget_scripts_preview']);
    }

}

Popularis_Elementor_Extension::instance();
