<?php
/**
 * Gestion des tâches, ajouter, editer, supprimer.
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

DEFINE( 'WPEO_TASK_VERSION', 1.0 );
DEFINE( 'WPEO_TASK_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_TASK_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_TASK_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_TASK_PATH ) );
DEFINE( 'WPEO_TASK_ASSETS_DIR',  WPEO_TASK_PATH . '/asset/' );
DEFINE( 'WPEO_TASK_TEMPLATES_MAIN_DIR', WPEO_TASK_PATH . '/template/');

if ( !class_exists( 'task_controller_01' ) ) {
	require_once( WPEO_TASK_PATH . '/controller/task.controller.01.php' );
	require_once( WPEO_TASK_PATH . '/controller/task.action.01.php' );
}
?>
