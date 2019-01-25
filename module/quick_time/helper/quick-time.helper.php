<?php
/**
 * Fonctions helpers des quick times.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Formattes les données d'un quicktime.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param  Quick_Time_Model $data Les données de l'objet.
 *
 * @return Quick_Time_Model       Les données de l'objet modifié.
 */
function quicktime_format_data( $data ) {
	$data['displayed'] = array(
		'task'               => Task_Class::g()->get(
			array(
				'id' => $data['task_id'],
			),
			true
		),
		'point'              => Point_Class::g()->get(
			array(
				'id' => $data['point_id'],
			),
			true
		),
		'point_fake_content' => '',
	);

	$data['displayed']['point_fake_content'] = '';

	$data['displayed']['point_fake_content'] = '#' . $data['displayed']['point']->data['id'] . ' ' . $data['displayed']['point']->data['content'];

	if ( strlen( $data['displayed']['point']->data['content'] ) > 15 ) {
		$data['displayed']['point_fake_content'] = substr( $data['displayed']['point']->data['content'], 0, 15 );
		$data['displayed']['point_fake_content'] = '#' . $data['displayed']['point']->data['id'] . ' ' . $data['displayed']['point_fake_content'] . '...';
	}

	return $data;
}
