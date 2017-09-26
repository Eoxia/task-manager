<?php
/**
 * La définition du schéma des données d'une tâche.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.4.0-ford
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
	 * @param Task_Model $object L'objet.
	 */
	public function __construct( $object ) {
		$this->model = array_merge( $this->model, array(
			'user_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'bydefault' => array(
					'owner_id' => get_current_user_id(),
					'affected_id' => array(),
				),
				'owner_id' => array(
					'type' => 'integer',
					'meta_type' => 'multiple',
				),
				'affected_id' => array(
					'type' => 'array',
					'meta_type' => 'multiple',
				),
				'writer_id' => array(
					'type' => 'integer',
					'meta_type' => 'multiple',
				),
			),
			'time_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'bydefault' => array(
					'history_time' => array(),
					'elapsed' => 0,
				),
				'history_time' => array(
					'type' => 'array',
					'array_type' => 'integer',
					'meta_type' => 'multiple',
				),
				'elapsed' => array(
					'type' => 'integer',
					'meta_type' => 'multiple',
				),
			),
			'front_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'bydefault' => array(
					'display_time' => false,
					'display_user' => false,
					'display_color' => 'white',
				),
				'display_time' => array(
					'type' => 'boolean',
					'meta_type' => 'multiple',
				),
				'display_user' => array(
					'type' => 'boolean',
					'meta_type' => 'multiple',
				),
				'display_color' => array(
					'type' => 'string',
					'meta_type' => 'multiple',
				),
			),
			'task_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'bydefault' => array(
					'completed' => false,
					'order_point_id' => array(),
				),
				'completed' => array(
					'type' => 'boolean',
					'meta_type' => 'multiple',
				),
				'order_point_id' => array(
					'type' => 'array',
					'array_type' => 'integer',
					'meta_type' => 'multiple',
				),
			),
		) );

		$this->model['taxonomy'] = array(
			'type' => 'array',
			'meta_type' => 'multiple',
			'child' => array(
				'wpeo_tag' => array(
					'meta_type' => 'multiple',
					'array_type' => 'integer',
					'type' => 'array',
				),
			),
		);

		parent::__construct( $object );
	}
}
