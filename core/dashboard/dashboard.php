<?php

/**
 * Tableau de board des tâches
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

DEFINE( 'WPEO_DASHBOARD_VERSION', 1.0 );
DEFINE( 'WPEO_DASHBOARD_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_DASHBOARD_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_DASHBOARD_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_DASHBOARD_PATH ) );
DEFINE( 'WPEO_DASHBOARD_ASSETS_DIR',  WPEO_DASHBOARD_PATH . '/asset/' );
DEFINE( 'WPEO_DASHBOARD_TEMPLATES_MAIN_DIR', WPEO_DASHBOARD_PATH . '/template/');

if ( !class_exists( 'dashboard_controller_01' ) ) {
	require_once( WPEO_DASHBOARD_PATH . '/controller/dashboard.controller.01.php' );
}
?>
