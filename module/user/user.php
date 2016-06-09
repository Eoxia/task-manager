<?php

/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_USER_VERSION', 1.0 );
DEFINE( 'WPEO_USER_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_USER_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_USER_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_USER_PATH ) );

DEFINE( 'WPEO_USER_ASSETS_DIR',  WPEO_USER_PATH . '/asset/' );
DEFINE( 'WPEO_USER_ASSETS_URL',  WPEO_USER_URL . '/asset/' );
DEFINE( 'WPEO_USER_TEMPLATES_MAIN_DIR', WPEO_USER_PATH . '/template/');

/**	Load plugin translation	*/
load_plugin_textdomain( 'wpeouser-i18n', false, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
if ( !class_exists( 'user_controller_01' ) ) {
	require_once( WPEO_USER_PATH . '/controller/user.controller.01.php' );
	require_once( WPEO_USER_PATH . '/controller/user.action.01.php' );
}
?>
