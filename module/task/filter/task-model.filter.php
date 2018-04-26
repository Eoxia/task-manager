<?php
/**
 * Les filtres relatives aux modèles des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives aux modèles des tâches.
 */
class Task_Model_Filter {

	/**
	 * Constructeur
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		$current_type = Task_Class::g()->get_type();
		add_filter( "eo_model_{$current_type}_after_get", array( $this, 'get_full_task' ), 10, 2 );
	}

	/**
	 * Récupères toutes les données complémentaire d'une tâche
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @param Task_Model $object L'objet Task_Model.
	 * @param array      $args   Des paramètres complémentaires pour permettre d'agir sur l'élement.
	 *
	 * @return Task_Model        Les données de la tâche avec les données complémentaires.
	 */
	public function get_full_task( $object, $args ) {
		$object->data['last_history_time'] = History_Time_Class::g()->get( array(
			'post_id' => $object->data['id'],
			'number'  => 1,
		), true );

		if ( empty( $object->data['last_history_time']->data['id'] ) ) {
			$object->data['last_history_time'] = History_Time_Class::g()->get( array(
				'schema' => true,
			), true );
		} else {
			// Calcul du temps si on est en mode "répétition" mensuel.
			if ( 'recursive' === $object->data['last_history_time']->data['custom'] ) {
				$comments = Task_Comment_Class::g()->get( array(
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
				) );

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

new Task_Model_Filter();
