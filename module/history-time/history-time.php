<?php
/**
 * Plugin Name: History time
 * Description: Gestion du temps voulu
 * Version: 1.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package HistoryTime
 */

/**
 * Gestion des temps voulu des tâches
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Define
 */
DEFINE( 'WPEO_HISTORY_TIME_VERSION', 1.0 );
DEFINE( 'WPEO_HISTORY_TIME_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'WPEO_HISTORY_TIME_PATH', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );
DEFINE( 'WPEO_HISTORY_TIME_URL', str_replace( str_replace( '\\', '/', ABSPATH ), site_url() . '/', WPEO_HISTORY_TIME_PATH ) );

DEFINE( 'WPEO_HISTORY_TIME_ASSETS_DIR',  WPEO_HISTORY_TIME_PATH . '/asset/' );
DEFINE( 'WPEOMTM_HISTORY_TIME_ASSETS_URL',  WPEO_HISTORY_TIME_URL . '/asset/' );
DEFINE( 'WPEO_HISTORY_TIME_TEMPLATES_MAIN_DIR', WPEO_HISTORY_TIME_PATH . '/template/' );

require_once( WPEO_HISTORY_TIME_PATH . '/controller/history-time.controller.01.php' );
require_once( WPEO_HISTORY_TIME_PATH . '/controller/history-time.action.01.php' );
