<?php
/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_WINDOW_VERSION', 1.0 );
DEFINE( 'WPEO_WINDOW_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_WINDOW_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_WINDOW_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_WINDOW_PATH ) );

DEFINE( 'WPEO_WINDOW_ASSETS_DIR',  WPEO_WINDOW_PATH . '/asset/' );
DEFINE( 'WPEO_WINDOW_TEMPLATES_MAIN_DIR', WPEO_WINDOW_PATH . '/template/');

/**	Load plugin translation	*/
if ( !class_exists( 'window_controller_01' ) ) {
	require_once( WPEO_WINDOW_PATH . '/controller/window.controller.01.php' );
	require_once( WPEO_WINDOW_PATH . '/controller/window.action.01.php' );
}
?>
