<?php
/**
 * Gestion de l'historique du temps sur une tâche.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
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
	protected $comment_type = 'history_time';

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
	 * La fonction appelée automatiquement après l'insertion de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $before_post_function = array();

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_model_get_function = array( '\task_manager\get_full_history_time' );

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
		$history_time_schema = self::g()->get( array(
			'schema' => true,
		), true );

		$history_times = self::g()->get( array(
			'post_id'          => $task_id,
			'orderby'          => 'ASC',
			'comment_approved' => '-34070',
			'type'             => self::g()->get_type(),
		) );

		if ( ! empty( $history_times ) ) {
			foreach ( $history_times as $key => $history_time ) {
				$history_time->author = get_userdata( $history_time->author_id );
			}
		}

		\eoxia\View_Util::exec( 'task-manager', 'history-time', 'backend/main', array(
			'task_id'             => $task_id,
			'last_history_time'   => $history_times[0],
			'history_times'       => $history_times,
			'history_time_schema' => $history_time_schema,
		) );
	}
}

History_Time_Class::g();
