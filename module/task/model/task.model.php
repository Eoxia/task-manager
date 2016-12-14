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
				'owner_id' => array(
					'type' 		=> 'integer',
					'function'	=> '',
					'default'	=> 0,
					'required'	=> false,
				),
				'affected_id' => array(
					'type' 		=> 'array',
					'function'	=> '',
					'default'	=> array(),
					'required'	=> false,
				),
				'writer_id' => array(
					'type'		=> 'integer',
					'function'	=> '',
					'default'	=> 0,
					'required'	=> false,
				),
			),
			'time_info' => array(
				'history_time' => array(
					'type'		=> 'integer',
					'function'	=> '',
					'default'	=> 0,
					'required'	=> false,
				),
				'elapsed' => array(
					'type'		=> 'integer',
					'function'	=> '',
					'default' 	=> 0,
					'required'	=> false,
				),
			),
			'front_info' => array(
				'display_time' => array(
					'type'		=> 'boolean',
					'function'	=> '',
					'default' 	=> false,
					'required'	=> false,
				),
				'display_user' => array(
					'type'		=> 'boolean',
					'function'	=> '',
					'default' 	=> false,
					'required'	=> false,
				),
				'display_color' => array(
					'type'		=> 'string',
					'function'	=> '',
					'default' 	=> 'white',
					'required'	=> false,
				),
			),
			'task_info' => array(
				'completed' => array(
					'type'		=> 'boolean',
					'function'	=> '',
					'default' 	=> false,
					'required'	=> false,
				),
				'order_point_id' => array(
					'type'		=> 'array',
					'function' 	=> '',
					'default'	=> array(),
					'required'	=> false,
				),

			),
		) );

		parent::__construct( $object );
	}
}
