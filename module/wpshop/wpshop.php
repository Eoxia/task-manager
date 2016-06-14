<?php
/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_TASK_WPSHOP_VERSION', 1.0 );
DEFINE( 'WPEO_TASK_WPSHOP_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_TASK_WPSHOP_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_TASK_WPSHOP_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_TASK_WPSHOP_PATH ) );

DEFINE( 'WPEO_TASK_WPSHOP_ASSETS_DIR',  WPEO_TASK_WPSHOP_PATH . '/asset/' );
DEFINE( 'WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR', WPEO_TASK_WPSHOP_PATH . '/template/');

/**	Load plugin translation	*/
if ( !class_exists( 'task_wpshop_controller_01' ) && taskmanager\util\wpeo_util::is_plugin_active( 'wpshop/wpshop.php' ) ) {
	require_once( WPEO_TASK_WPSHOP_PATH . '/controller/task_wpshop.controller.01.php' );
	require_once( WPEO_TASK_WPSHOP_PATH . '/controller/task_wpshop.action.01.php' );
}
?>
