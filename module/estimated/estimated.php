<?php
/**
 * Plugin Name: Estimated time
 * Description: Gestion du temps estimé
 * Version: 1.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Gestion des temps estimé des tâches
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_ESTIMATED_VERSION', 1.0 );
DEFINE( 'WPEO_ESTIMATED_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_ESTIMATED_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_ESTIMATED_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_ESTIMATED_PATH ) );

DEFINE( 'WPEO_ESTIMATED_ASSETS_DIR',  WPEO_ESTIMATED_PATH . '/asset/' );
DEFINE( 'WPEO_ESTIMATED_TEMPLATES_MAIN_DIR', WPEO_ESTIMATED_PATH . '/template/');

require_once( WPEO_ESTIMATED_PATH . '/controller/estimated.controller.01.php' );
require_once( WPEO_ESTIMATED_PATH . '/controller/estimated.action.01.php' );

?>
