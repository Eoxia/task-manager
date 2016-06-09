<?php

/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEOMTM_TIMELINE_VERSION', 1.0 );
DEFINE( 'WPEOMTM_TIMELINE_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEOMTM_TIMELINE_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEOMTM_TIMELINE_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEOMTM_TIMELINE_PATH ) );

DEFINE( 'WPEOMTM_TIMELINE_ASSETS_DIR',  WPEOMTM_TIMELINE_PATH . '/asset/' );
DEFINE( 'WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR', WPEOMTM_TIMELINE_PATH . '/template/');

/**	Load plugin translation	*/
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'wpeotimeline-i18n', false, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
} );

require_once( WPEOMTM_TIMELINE_PATH . '/controller/timeline.controller.01.php' );
require_once( WPEOMTM_TIMELINE_PATH . '/controller/timeline.action.01.php' );
?>
