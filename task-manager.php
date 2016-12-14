<?php
/**
 * Fichier boot du plugin
 *
 * @package TaskManager\Plugin
 */

namespace task_manager;

/**
 * Plugin Name: Task Manager
 * Description: Quick and easy to use, manage all your tasks and your time with the Task Manager plugin.
 * Version: 1.3.4.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager
 * Domain Path: /language
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

DEFINE( 'PLUGIN_TASK_MANAGER_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_TASK_MANAGER_DIR', basename( __DIR__ ) );

require_once 'core/wpeo_check.01.php';
require_once 'core/wpeo_template.01.php';
require_once 'core/wpeo_util.01.php';
require_once 'core/external/wpeo_util/singleton.util.php';
require_once 'core/external/wpeo_util/init.util.php';
require_once 'core/external/wpeo_model/wpeo_model.php';
require_once 'core/external/wpeo_logs/controller/log.controller.01.php';

// log_class::g()->start_ms( 'task_manager_boot' );
Init_util::g()->exec();
// log_class::g()->exec( 'task_manager_boot', 'task_manager_boot', 'Boot l\'application Task Manager' );
