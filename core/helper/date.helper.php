<?php
/**
 * Fonction gérant les dates pour les modèles.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package TaskManager
 * @subpackage helper
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Convertie la date au format SQL vers le format français
 *
 * @param  object $data Les donnnées du modèle.
 * @return object       Les donnnées du modèle avec la date au format SQL
 */
function convert_date_display( $data ) {
	$data->date = ! empty( $data->date ) ? date( 'd/m/Y h:i:s', strtotime( $data->date ) ) : current_time( 'd/m/Y h:i:s' );

	return $data;
}
