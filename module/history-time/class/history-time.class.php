<?php
/**
 * Gestion de l'historique du temps sur une tâche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.3.4
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion de l'historique du temps sur une tâche.
 */
class History_Time_Class extends \eoxia\Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\task_manager\History_Time_Model';

	/**
	 * Key to use on meta DataBase.
	 *
	 * @var string
	 */
	protected $meta_key = 'wpeo_history_time';

	/**
	 * Le type
	 *
	 * @var string
	 */
	protected $type = 'history_time';

	/**
	 * API REST base.
	 *
	 * @var string
	 */
	protected $base = 'history_time';

	/**
	 * Version of controller.
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * Statut personnalisé pour l'élément.
	 *
	 * @var string
	 */
	protected $status = '1';

	/**
	 * Définition des fonctions de callback pour l'élément.
	 *
	 * @var  array
	 */
	protected $callback_func = array(
		'after_get' => array( '\task_manager\get_full_history_time' ),
	);

	/**
	 * Charges les historiques de temps et les affiches.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @param  integer $task_id L'ID de la tâche.
	 * @return void
	 */
	public function display_histories_time( $task_id ) {
		$history_time_schema = self::g()->get(
			array(
				'schema' => true,
			),
			true
		);

		$history_times = self::g()->get(
			array(
				'post_id' => $task_id,
				'orderby' => 'ASC',
				'type'    => self::g()->get_type(),
			)
		);

		if ( ! empty( $history_times ) ) {
			foreach ( $history_times as $key => $history_time ) {
				$history_time->data['author'] = get_userdata( $history_time->data['author_id'] );
			}
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'history-time',
			'backend/main',
			array(
				'task_id'             => $task_id,
				'last_history_time'   => ! empty( $history_times[0] ) ? $history_times[0] : null,
				'history_times'       => $history_times,
				'history_time_schema' => $history_time_schema,
			)
		);
	}
}

History_Time_Class::g();
