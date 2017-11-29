<?php
/**
 * Plugin Name: Task Manager
 * Description: Quick and easy to use, manage all your tasks and your time with the Task Manager plugin.
 * Version: 1.5.1
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager
 * Domain Path: /language
 *
 * @package TaskManager\Plugin
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

DEFINE( 'PLUGIN_TASK_MANAGER_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_DIR', basename( __DIR__ ) );

require_once( 'core/external/eo-framework/eo-framework.php' );

\eoxia\Init_util::g()->exec( PLUGIN_TASK_MANAGER_PATH, basename( __FILE__, '.php' ) );

\eoxia\Config_Util::$init['eo-framework']->hour_equal_one_day = 7;
