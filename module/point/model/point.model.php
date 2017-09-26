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

class Point_Model extends \eoxia\Comment_Model {
	public function __construct( $object ) {
		$this->model['status']['bydefault'] = '-34070';
		$this->model['author_id']['bydefault'] = get_current_user_id();
		$this->model['date']['bydefault'] = current_time( 'mysql' );

		$this->model = array_merge( $this->model, array(
			'time_info' => array(
				'type'			=> 'array',
				'meta_type' => 'multiple',
				'bydefault' => array( 'elapsed' => 0, 'completed_point' => array(), 'uncompleted_point' => array() ),
				'elapsed' => array(
					'type'			=> 'integer',
					'meta_type'	=> 'multiple',
				),
				'completed_point' => array(
					'type'			=> 'array',
					'meta_type'	=> 'multiple',
				),
				'uncompleted_point' => array(
					'type'			=> 'array',
					'meta_type'	=> 'multiple',
				),
			),
			'point_info' => array(
				'type'			=> 'array',
				'meta_type'	=> 'multiple',
				'bydefault' => array( 'completed' => false ),
				'completed' => array(
					'type'			=> 'boolean',
					'meta_type'	=> 'multiple',
				),
			),
		) );
		parent::__construct( $object );
	}

}
