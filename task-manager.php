<?php
/**
 * Plugin Name: Task Manager
 * Description: Quick and easy to use, manage all your tasks and your time with the Task Manager plugin.
 * Version: 3.0.2
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager
 * Domain Path: /language
 *
 * @package TaskManager\Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

DEFINE( 'PLUGIN_TASK_MANAGER_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_DIR', basename( __DIR__ ) );
DEFINE( 'PLUGIN_TASK_MANAGER_DEV_MODE', false );

if ( ! PLUGIN_TASK_MANAGER_DEV_MODE ) {
	require_once 'core/external/eo-framework/eo-framework.php';
}

\eoxia\Init_Util::g()->exec( PLUGIN_TASK_MANAGER_PATH, basename( __FILE__, '.php' ) );
