<?php

/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEOMTM_SEARCH_VERSION', 1.1 );
DEFINE( 'WPEOMTM_SEARCH_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEOMTM_SEARCH_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEOMTM_SEARCH_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEOMTM_SEARCH_PATH ) );

DEFINE( 'WPEOMTM_SEARCH_ASSETS_DIR',  WPEOMTM_SEARCH_PATH . '/asset/' );
DEFINE( 'WPEOMTM_SEARCH_ASSETS_URL',  WPEOMTM_SEARCH_URL . '/asset/' );
DEFINE( 'WPEOMTM_SEARCH_TEMPLATES_MAIN_DIR', WPEOMTM_SEARCH_PATH . '/template/');

require_once( WPEOMTM_SEARCH_PATH . '/controller/search.action.01.php' );
?>
