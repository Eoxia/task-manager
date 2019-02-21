<?php
/**
 * Fonction gérant les dates pour l'historique du temps.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package TaskManager
 * @subpackage helper
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Convertie la date au format français dd/mm/yy en format SQL
 *
 * @param  object $data Les donnnées du modèle.
 * @return object       Les donnnées du modèle avec la date au format SQL
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
function convert_history_time_due_date_to_sql( $data ) {
	if ( ! empty( $data ) && ! empty( $data->due_date ) ) {
		$data->due_date = date( 'Y-m-d', strtotime( str_replace( '/', '-', $data->due_date ) ) );
	}
	return $data;
}

/**
 * Agit sur les données retournées lors de la récupération de l'historique de temps d'une tache
 *
 * @param  object $object Les donnnées du modèle.
 *
 * @return object       Les donnnées du modèle avec la date au format SQL
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
function get_full_history_time( $object ) {
	$format = '%hh %imin';
	$dtf    = new \DateTime( '@0' );

	/** Gestion de l'affichage du temps passé en jours/heures */
	$dtt = new \DateTime( '@' . ( $object->data['estimated_time'] * 60 ) );
	if ( 1440 <= $object->data['estimated_time'] ) {
		$format = '%aj %hh %imin';
	}
	$object->data['time_info']['estimated_time_display'] = $dtf->diff( $dtt )->format( $format );

	return $object;
}
