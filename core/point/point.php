<?php
/**
 * Gestion des points et commentaires
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_POINT_VERSION', 1.0 );
DEFINE( 'WPEO_POINT_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_POINT_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_POINT_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_POINT_PATH ) );

DEFINE( 'WPEO_POINT_ASSETS_DIR',  WPEO_POINT_PATH . '/asset/' );
DEFINE( 'WPEO_POINT_TEMPLATES_MAIN_DIR', WPEO_POINT_PATH . '/template/');



require_once( WPEO_POINT_PATH . '/controller/point.controller.01.php' );
require_once( WPEO_POINT_PATH . '/controller/point.action.01.php' );
?>
