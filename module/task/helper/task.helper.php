<?php
/**
 * Fonctions "helper" des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fonctions "helper" des tâches.
 */
class Task_Helper {

	/**
	 * Récupères toutes les données essentielles d'une tâche
	 * -Tache
	 * -Dernier history time
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @param Task_Model $object Les données de la tâche.
	 *
	 * @return Task_Model         Les données de la tâche modifié.
	 */
	public function get_full_task( $object ) {
		$object->data['last_history_time'] = History_Time_Class::g()->get(
			array(
				'post_id' => $object->data['id'],
				'number'  => 1,
			),
			true
		);

		$object->data['parent'] = null;

		if ( ! empty( $object->data['parent_id'] ) ) {
			$object->data['parent'] = get_post( $object->data['parent_id'] );
		}

		if ( empty( $object->data['last_history_time']->data['id'] ) ) {
			$object->data['last_history_time'] = History_Time_Class::g()->get(
				array(
					'schema' => true,
				),
				true
			);
		} else {
			// Calcul du temps si on est en mode "répétition" mensuel.
			if ( 'recursive' === $object->data['last_history_time']->data['custom'] ) {
				$comments = Task_Comment_Class::g()->get(
					array(
						'date_query'   => array(
							'after' => array(
								'year'  => current_time( 'Y' ),
								'month' => current_time( 'm' ),
								'day'   => '01',
							),
						),
						'status'       => 1,
						'post_id'      => $object->data['id'],
						'type__not_in' => array( 'history_time' ),
					)
				);

				$object->data['time_info']['elapsed'] = 0;

				if ( ! empty( $comments ) ) {
					foreach ( $comments as $comment ) {
						$object->data['time_info']['elapsed'] += $comment->data['time_info']['elapsed'];
					}
				}
			}
		}

		$object->data['count_all_points'] = $object->data['count_uncompleted_points'] + $object->data['count_completed_points'];

		return $object;
	}
}
