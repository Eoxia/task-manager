<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Task model
 * Les options des tâches ( meta, comment )
 * @version 0.1
 * @author EOXIA
 *
 */

class Task_Model extends Post_Model {
	public function __construct( $object ) {
		$this->model = array_merge( $this->model, array(
			'user_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'owner_id' => array(
					'type' 			=> 'integer',
					'meta_type'	=> 'multiple',
				),
				'affected_id' => array(
					'type' 			=> 'array',
					'meta_type'	=> 'multiple',
					'by_default'	=> array(),
				),
				'writer_id' => array(
					'type' 			=> 'integer',
					'meta_type'	=> 'multiple',
				),
			),
			'time_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'history_time' => array(
					'type' 			=> 'integer',
					'meta_type'	=> 'multiple',
				),
				'elapsed' => array(
					'type' 			=> 'integer',
					'meta_type'	=> 'multiple',
				),
			),
			'front_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'display_time' => array(
					'type' 			=> 'boolean',
					'meta_type'	=> 'multiple',
				),
				'display_user' => array(
					'type' 			=> 'boolean',
					'meta_type'	=> 'multiple',
				),
				'display_color' => array(
					'type' 			=> 'string',
					'meta_type'	=> 'multiple',
				),
			),
			'task_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'completed' => array(
					'type' 			=> 'boolean',
					'meta_type'	=> 'multiple',
				),
				'order_point_id' => array(
					'type' => 'array',
					'meta_type' => 'multiple',
				),
			),
		) );

		parent::__construct( $object );
	}
}
