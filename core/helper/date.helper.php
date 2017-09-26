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
 * Convertie la date au format français dd/mm/yy en format SQL
 *
 * @param  object $data Les donnnées du modèle.
 * @return object       Les donnnées du modèle avec la date au format SQL
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
function convert_date_to_sql( $data ) {
	if ( strlen( $data->date ) === 10 ) {
		$data->date .= ' ' . current_time( 'H:i:s' );
	}

	$data->date = str_replace( '/', '-', $data->date );
	$data->date = date( 'Y-m-d H:i:s', strtotime( $data->date ) );

	return $data;
}

/**
 * Convertie la date au format SQL vers le format français
 *
 * @param  object $data Les donnnées du modèle.
 * @return object       Les donnnées du modèle avec la date au format SQL
 *
 * @version 1.0.0.0
 * @version 1.3.6.0
 */
function convert_date_display( $data ) {
	$format = '\L\e d F Y à H\hi';

	$data->date_input = mysql2date( 'd/m/Y H:i', $data->date );

	$data->date_human_readable = mysql2date( $format, $data->date );
	return $data;
}
