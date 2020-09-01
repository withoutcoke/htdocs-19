<?php
/**
 * @package   Arowana
 */
 
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-slider.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-info.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-service.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-testimonial.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/navigation.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/features/section-typography.php';
require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/typography_style.php';


if ( ! function_exists( 'cleverfox_arowana_frontpage_sections' ) ) :
	function cleverfox_arowana_frontpage_sections() {	
		require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/sections/section-slider.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/arowana/sections/section-flash.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/arowana/sections/section-service.php';
		require CLEVERFOX_PLUGIN_DIR . 'inc/startkit/sections/section-testimonial.php';
    }
	add_action( 'startkit_sections', 'cleverfox_arowana_frontpage_sections' );
endif;
