<?php
/**
 * La définition du schéma des données d'une tâche.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La définition du schéma des données d'une tâche.
 */
class Task_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur défini le schéma.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param Task_Model $object L'objet.
	 */
	public function __construct( $object ) {
		$this->model['user_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'bydefault' => array(
				'owner_id'    => get_current_user_id(),
				'affected_id' => array(),
			),
		);

		$this->model['user_info']['owner_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Le responsable de la tâche',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['user_info']['affected_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Les utilisateurs (admin) affectés à la tâche',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['user_info']['writer_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Plus utilisé depuis 2016.',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['time_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(),
			'bydefault' => array(
				'history_time' => array(),
				'elapsed'      => 0,
			),
		);

		$this->model['time_info']['child']['history_time'] = array(
			'type'        => 'array',
			'array_type'  => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'L\'ID des historiques de temps.',
			'todo'        => 'Cet index est sans doute mal nommé (03/01/2018, Jimmy)',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['time_info']['child']['elapsed'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Le temps passé sur la tâche en minute',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['time_info']['child']['elapsed'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Le temps passé sur la tâche en minute',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['front_info'] = array(
			'type'        => 'array',
			'meta_type'   => 'multiple',
			'description' => 'Les options dans le frontend de WordPress',
			'todo'        => 'Cette donnée n\'est plus utilisé depuis 2016 (03/01/2018, Jimmy)',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->model['front_info']['display_time'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['front_info']['display_user'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['front_info']['display_color'] = array(
			'type'      => 'string',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['task_info'] = array(
			'type'        => 'array',
			'meta_type'   => 'multiple',
			'description' => 'Les informations de la tâche',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
			'bydefault'   => array(
				'completed'      => false,
				'order_point_id' => array(),
			),
		);

		$this->model['task_info']['completed'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['task_info']['order_point_id'] = array(
			'type'       => 'array',
			'array_type' => 'integer',
			'meta_type'  => 'multiple',
			'since'      => '1.0.0',
			'version'    => '1.6.0',
		);

		$this->model['count_completed_points'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_tm_count_completed_points',
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->model['count_uncompleted_points'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_tm_count_uncompleted_points',
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->model['taxonomy'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'child'     => array(
				'wpeo_tag' => array(
					'meta_type'  => 'multiple',
					'array_type' => 'integer',
					'type'       => 'array',
				),
			),
		);

		parent::__construct( $object );
	}
}
