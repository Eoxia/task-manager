<?php
/**
 * Plugin Name: Task Manager
 * Description: Quick and easy to use, manage all your tasks and your time with this plugin. / Rapide et facile à utiliser, gérer toutes vos tâches et votre temps avec cette extension.
 * Version: 1.3.0.1
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: task-manager
 * Domain Path: /language
 */

/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.3.0.1
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_TASKMANAGER_VERSION', '1.3.0.1' );
DEFINE( 'WPEO_TASKMANAGER_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_TASKMANAGER_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_TASKMANAGER_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_TASKMANAGER_PATH ) );

DEFINE( 'WPEO_TASKMANAGER_EXPORT_URL', WPEO_TASKMANAGER_URL . '/core/asset/export/' );
DEFINE( 'WPEO_TASKMANAGER_ASSET_URL', WPEO_TASKMANAGER_URL . '/core/asset/');
DEFINE( 'WPEO_TASKMANAGER_EXPORT_DIR',  WPEO_TASKMANAGER_PATH . '/core/asset/export/' );
DEFINE( 'WPEO_TASKMANAGER_ASSETS_DIR',  WPEO_TASKMANAGER_PATH . '/core/asset/' );

DEFINE( 'WPEO_TASKMANAGER_TEMPLATES_MAIN_DIR', WPEO_TASKMANAGER_PATH . '/template/' );

DEFINE( 'WPEO_TASKMANAGER_DEBUG', false );

/** Ajout des langues */
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'task-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/core/asset/language/' );
} );

require_once( WPEO_TASKMANAGER_PATH . 'core/wpeo_util.01.php' );
require_once( WPEO_TASKMANAGER_PATH . 'core/wpeo_template.01.php' );
require_once( WPEO_TASKMANAGER_PATH . 'core/wpeo_check.01.php' );

wpeo_util::install_module( 'wpeo_logs' );
wpeo_util::install_module( 'wpeo_model' );

require_once( WPEO_TASKMANAGER_PATH . '/core/taskmanager/taskmanager.controller.01.php' );

wpeo_util::install_in( 'core' );
?>
