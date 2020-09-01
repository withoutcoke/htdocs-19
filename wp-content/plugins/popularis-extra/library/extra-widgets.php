<?php

/**
 * Custom widgets.
 *
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('popularis_extra_load_widgets')) :

    /**
     * Load widgets.
     *
     * @since 1.0.0
     */
    function popularis_extra_load_widgets() {

        // Extended Recent Post.
        register_widget('Popularis_Extra_Extended_Recent_Posts');

        // Popular Post.
        register_widget('Popularis_Extra_Popular_Posts');

        // Social.
        register_widget('Popularis_Extra_Social_Widget');

        // About.
        register_widget('Popularis_Extra_About_Me_Widget');
    }

endif;

add_action('widgets_init', 'popularis_extra_load_widgets');

/**
 * Recent Posts Widget
 */
require_once( plugin_dir_path(__FILE__) . 'extra-widgets/recent-posts-widget.php' );

/**
 * Popular Posts Widget
 */
require_once( plugin_dir_path(__FILE__) . 'extra-widgets/popular-posts-widget.php' );

/**
 * Social Widget
 */
require_once( plugin_dir_path(__FILE__) . 'extra-widgets/social-widget.php' );

/**
 * About Me Widget
 */
require_once( plugin_dir_path(__FILE__) . 'extra-widgets/about-widget.php' );
