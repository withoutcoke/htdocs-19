<?php
/*
Plugin Name: Clever Fox
Plugin URI:
Description: The Clever Fox plugin adds sections functionality to the Startkit theme and Others Nayra's Themes. This plugin for only startkit themes. Clever Fox is a plugin build to enhance the functionality of WordPress Theme made by Nayra Themes.
Version: 1.4
Author: nayrathemes
Author URI: https://nayrathemes.com
Text Domain: clever-fox
*/
define( 'CLEVERFOX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CLEVERFOX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function cleverfox_activate() {
	
	/**
	 * Load Custom control in Customizer
	 */
	define( 'CLEVERFOX_DIRECTORY', plugin_dir_url( __FILE__ ) . '/inc/custom-controls/' );
	define( 'CLEVERFOX_DIRECTORY_URI', plugin_dir_url( __FILE__ ) . '/inc/custom-controls/' );
	if ( class_exists( 'WP_Customize_Control' ) ) {
		require_once('inc/custom-controls/controls/range-validator/range-control.php');	
		require_once('inc/custom-controls/controls/select/select-control.php');
		require_once('inc/custom-controls/Tabs/class/cleverfox-customize-control-tabs.php');
	}
	
	$theme = wp_get_theme(); // gets the current theme
		if ( 'StartKit' == $theme->name){	
			require_once('inc/startkit/startkit.php');
		}
		
		if ( 'StartBiz' == $theme->name){	
			require_once('inc/startbiz/startbiz.php');
		}
		
		if ('Arowana' == $theme->name){	
			 require_once('inc/arowana/arowana.php');
		}
		
		if ('Envira' == $theme->name){	
			 require_once('inc/envira/envira.php');			
		}
		
		if( 'Hantus' == $theme->name){
			require_once('inc/hantus/hantus.php');	
		}
		
		if( 'Conceptly' == $theme->name){
			require_once('inc/conceptly/conceptly.php');
		}
		
		if( 'Ameya' == $theme->name){
			require_once('inc/ameya/ameya.php');
		}
	}
add_action( 'init', 'cleverfox_activate' );

$theme = wp_get_theme();

/**
 * The code during plugin activation.
 */
function activate_cleverfox() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/cleverfox-activator.php';
	Cleverfox_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_cleverfox' );
	
function cleverfox_admin_notice() {
?>
	<div class="notice notice-info is-dismissible">
		<p>
			<?php
				
				 echo sprintf(__('The Clever Fox plugin adds sections functionality to the <a href="theme-install.php?search=startkit">Startkit theme</a> and Others <a href="theme-install.php?search=nayrathemes">Nayra"s Themes</a>', 'clever-fox'));
			?>
		</p>
	</div>	
<?php 
}
add_action( 'admin_notices', 'cleverfox_admin_notice' );
?>