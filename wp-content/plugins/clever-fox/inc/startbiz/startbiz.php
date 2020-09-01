<?php
/**
 * @package   StartBiz
 */
 
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-slider.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-info.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-service.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-testimonial.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/navigation.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-typography.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/typography_style.php';


if ( ! function_exists( 'cleverfox_startbiz_frontpage_sections' ) ) :
	function cleverfox_startbiz_frontpage_sections() {	
		require CLEVERFOX_PLUGIN_DIR . 'inc/startbiz/sections/section-slider.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/startbiz/sections/section-flash.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/startbiz/sections/section-service.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/sections/section-testimonial.php';
    }
	add_action( 'startkit_sections', 'cleverfox_startbiz_frontpage_sections' );
endif;
