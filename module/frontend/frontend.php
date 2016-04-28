<?php
/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_FRONTEND_VERSION', 1.0 );
DEFINE( 'WPEO_FRONTEND_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_FRONTEND_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_FRONTEND_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_FRONTEND_PATH ) );

DEFINE( 'WPEO_FRONTEND_ASSET_DIR',  WPEO_FRONTEND_PATH . '/asset/' );
DEFINE( 'WPEO_FRONTEND_ASSET_URL',  WPEO_FRONTEND_URL . '/asset/' );
DEFINE( 'WPEO_FRONTEND_TEMPLATES_MAIN_DIR', WPEO_FRONTEND_PATH . '/template/');

/**	Load plugin translation	*/
if ( !class_exists( 'frontend_controller_01' ) ) {
	require_once( WPEO_FRONTEND_PATH . '/controller/frontend.controller.01.php' );
	require_once( WPEO_FRONTEND_PATH . '/controller/frontend.action.01.php' );
}
?>
