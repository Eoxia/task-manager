<?php

namespace taskmanager\help;

if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Bootstrap file for plugin. Do main includes and create new instance for plugin components
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.1.0.0
 */

DEFINE( 'WPEO_TASK_HELP_VERSION', '1.1.0.0' );
DEFINE( 'WPEO_TASK_HELP_DIR', basename(dirname(__FILE__)));
DEFINE( 'WPEO_TASK_HELP_PATH', dirname( __FILE__ ) );
DEFINE( 'WPEO_TASK_HELP_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', str_replace( "\\", "/", WPEO_TASK_HELP_PATH ) ) );

/**	Define the templates directories */
DEFINE( 'WPEO_TASK_HELP_TEMPLATES_MAIN_DIR', WPEO_TASK_HELP_PATH . '/template/');

require_once( WPEO_TASK_HELP_PATH . '/controller/help.controller.01.php' );
require_once( WPEO_TASK_HELP_PATH . '/controller/help.action.01.php' );

?>
