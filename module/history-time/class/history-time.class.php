<?php
/**
 * Gestion de l'historique du temps sur une tâche.
 *
 * @since 1.3.4.0
 * @version 1.3.6.0
 * @package Task-Manager\history-time
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Manage all history_time.
 * History time define due time and estimated time on task.
 */
class History_Time_Class extends \eoxia\Comment_Class {
	/**
	 * Class name of model.
	 *
	 * @var string
	 */
	protected $model_name	= '\task_manager\History_Time_Model';
	/**
	 * Key to use on meta DataBase.
	 *
	 * @var string
	 */
	protected $meta_key	= 'wpeo_history_time';
	/**
	 * Type to use on DataBase.
	 *
	 * @var string
	 */
	protected $comment_type	= 'history_time';
	/**
	 * API REST base.
	 *
	 * @var string
	 */
	protected $base		= 'history_time';
	/**
	 * Version of controller.
	 *
	 * @var string
	 */
	protected $version	= '0.1';

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
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {
		parent::construct();
	}
}

History_Time_Class::g();
