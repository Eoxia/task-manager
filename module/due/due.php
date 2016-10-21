<?php
/**
 * Plugin Name: Due time
 * Description: Gestion du temps voulu
 * Version: 1.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Gestion des temps voulu des tâches
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_DUE_VERSION', 1.0 );
DEFINE( 'WPEO_DUE_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_DUE_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_DUE_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_DUE_PATH ) );

DEFINE( 'WPEO_DUE_ASSETS_DIR',  WPEO_DUE_PATH . '/asset/' );
DEFINE( 'WPEO_DUE_TEMPLATES_MAIN_DIR', WPEO_DUE_PATH . '/template/');

require_once( WPEO_DUE_PATH . '/controller/due.controller.01.php' );
require_once( WPEO_DUE_PATH . '/controller/due.action.01.php' );

?>
