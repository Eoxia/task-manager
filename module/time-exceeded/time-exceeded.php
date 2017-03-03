<?php
/**
 * Plugin Name: Time exceeded
 * Description: Gestion du temps dépassé
 * Version: 1.0.0.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package TimeExceeded
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Define
 */
DEFINE( 'WPEO_TIME_EXCEEDED_VERSION', 1.0 );
DEFINE( 'WPEO_TIME_EXCEEDED_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_TIME_EXCEEDED_PATH', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_TIME_EXCEEDED_URL', str_replace( str_replace( '\\', '/', ABSPATH ), site_url() . '/', WPEO_TIME_EXCEEDED_PATH ) );

DEFINE( 'WPEO_TIME_EXCEEDED_ASSETS_DIR',  WPEO_TIME_EXCEEDED_PATH . '/asset/' );
DEFINE( 'WPEOMTM_TIME_EXCEEDED_ASSETS_URL',  WPEO_TIME_EXCEEDED_URL . '/asset/' );
DEFINE( 'WPEO_TIME_EXCEEDED_TEMPLATES_MAIN_DIR', WPEO_TIME_EXCEEDED_PATH . '/template/' );

require_once( WPEO_TIME_EXCEEDED_PATH . '/controller/time-exceeded.controller.01.php' );
require_once( WPEO_TIME_EXCEEDED_PATH . '/controller/time-exceeded.action.01.php' );
