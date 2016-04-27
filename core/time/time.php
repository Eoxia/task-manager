<?php
/**
 * Plugin Name: Time
 * Description: Gestion des commentaires avec leurs temps
 * Version: 1.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Gestion des commentaires avec leurs temps
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

/** Define */
DEFINE( 'WPEO_TIME_VERSION', 1.0 );
DEFINE( 'WPEO_TIME_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_TIME_PATH', str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_TIME_URL', str_replace( str_replace( "\\", "/", ABSPATH), site_url() . '/', WPEO_TIME_PATH ) );

DEFINE( 'WPEO_TIME_ASSETS_DIR',  WPEO_TIME_PATH . '/asset/' );
DEFINE( 'WPEO_TIME_TEMPLATES_MAIN_DIR', WPEO_TIME_PATH . '/template/');

require_once( WPEO_TIME_PATH . '/controller/time.controller.01.php' );
require_once( WPEO_TIME_PATH . '/controller/time.action.01.php' );
?>
