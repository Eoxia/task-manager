<?php
/**
 * La définition du modèle des points dans Task Manager
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La définition du modèle des points dans Task Manager
 */
class Point_Model extends \eoxia\Comment_Model {

	/**
	 * Le Constructeur
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 *
	 * @param array  $object Les données reçu par WordPress.
	 */
	public function __construct( $object ) {
		$this->model['status']['bydefault'] = '-34070';
		$this->model['author_id']['bydefault'] = get_current_user_id();
		$this->model['date']['bydefault'] = current_time( 'mysql' );

		$this->model = array_merge( $this->model, array(
			'time_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'bydefault' => array(
					'elapsed' => 0,
					'completed_point' => array(),
					'uncompleted_point' => array(),
				),
				'elapsed' => array(
					'type' => 'integer',
					'meta_type' => 'multiple',
				),
				'completed_point' => array(
					'type' => 'array',
					'meta_type' => 'multiple',
				),
				'uncompleted_point' => array(
					'type' => 'array',
					'meta_type' => 'multiple',
				),
			),
			'point_info' => array(
				'type' => 'array',
				'meta_type' => 'multiple',
				'bydefault' => array(
					'completed' => false,
				),
				'completed' => array(
					'type' => 'boolean',
					'meta_type' => 'multiple',
				),
			),
		) );

		parent::__construct( $object );
	}

}
