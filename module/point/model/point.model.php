<?php
namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Point model
 * Les options des points ( meta, comment )
 * @version 0.1
 * @author EOXIA
 *
 */

class Point_Model extends Comment_Model {
	public function __construct( $object ) {
		$this->model = array_merge( $this->model, array(
			'time_info' => array(
				'elapsed' => array(
					'type'		=> 'integer',
					'function'	=> '',
					'default' 	=> 0,
					'required'	=> false,
				),
				'completed_point' => array(
					'type' 		=> 'array',
					'function'	=> '',
					'default'	=> null,
					'required'	=> false,
				),
				'uncompleted_point' => array(
					'type' 		=> 'array',
					'function'	=> '',
					'default'	=> null,
					'required'	=> false,
				),
			),
			'point_info' => array(
				'completed' => array(
					'type'		=> 'boolean',
					'function'	=> '',
					'default' 	=> false,
					'required'	=> false,
				),
			),
		) );
		parent::__construct( $object );
	}

}
