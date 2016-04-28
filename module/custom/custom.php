<?php
/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_CUSTOM_VERSION', 1.0 );
DEFINE( 'WPEO_CUSTOM_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_CUSTOM_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_CUSTOM_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_CUSTOM_PATH ) );

DEFINE( 'WPEO_CUSTOM_ASSET_DIR',  WPEO_CUSTOM_PATH . '/asset/' );
DEFINE( 'WPEO_CUSTOM_ASSET_URL',  WPEO_CUSTOM_URL . '/asset/' );
DEFINE( 'WPEO_CUSTOM_TEMPLATES_MAIN_DIR', WPEO_CUSTOM_PATH . '/template/');

/**	Load plugin translation	*/
if ( !class_exists( 'custom_controller_01' ) ) {
	require_once( WPEO_CUSTOM_PATH . '/controller/custom.controller.01.php' );
}
?>
