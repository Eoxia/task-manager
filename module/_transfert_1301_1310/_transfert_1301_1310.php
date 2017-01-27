<?php
/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_TRANSFERT_1301_1310_VERSION', 1.0 );
DEFINE( 'WPEO_TRANSFERT_1301_1310_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_TRANSFERT_1301_1310_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_TRANSFERT_1301_1310_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_TRANSFERT_1301_1310_PATH ) );

DEFINE( 'WPEO_TRANSFERT_1301_1310_ASSETS_DIR',  WPEO_TRANSFERT_1301_1310_PATH . '/asset/' );
DEFINE( 'WPEO_TRANSFERT_1301_1310_TEMPLATES_MAIN_DIR', WPEO_TRANSFERT_1301_1310_PATH . '/template/');

if( !class_exists( 'transfert_controller_01' ) ) {
  require_once( WPEO_TRANSFERT_1301_1310_PATH . '/controller/transfert.controller.01.php' );
}

?>
