<?php

/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEOMTM_TAG_VERSION', 1.1 );
DEFINE( 'WPEOMTM_TAG_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEOMTM_TAG_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEOMTM_TAG_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEOMTM_TAG_PATH ) );

DEFINE( 'WPEOMTM_TAG_ASSETS_DIR',  WPEOMTM_TAG_PATH . '/asset/' );
DEFINE( 'WPEOMTM_TAG_ASSETS_URL',  WPEOMTM_TAG_URL . '/asset/' );
DEFINE( 'WPEOMTM_TAG_TEMPLATES_MAIN_DIR', WPEOMTM_TAG_PATH . '/template/');

/**	Load plugin translation	*/
load_plugin_textdomain( 'wpeotag-i18n', false, dirname( plugin_basename( __FILE__ ) ) . '/language/' );

require_once( WPEOMTM_TAG_PATH . '/model/tag.model.01.php' );
require_once( WPEOMTM_TAG_PATH . '/controller/tag.controller.01.php' );
require_once( WPEOMTM_TAG_PATH . '/controller/tag.action.01.php' );
?>
