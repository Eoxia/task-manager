<?php
/**
 * La définition du modèle des points dans Task Manager
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
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
	 * @version 1.6.0
	 *
	 * @param array $object Les données reçu par WordPress.
	 */
	public function __construct( $object ) {
		$this->model['status']['bydefault']    = '-34070';
		$this->model['author_id']['bydefault'] = get_current_user_id();
		$this->model['date']['bydefault']      = current_time( 'mysql' );

		$this->model['time_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'bydefault' => array(
				'elapsed'           => array(),
				'completed_point'   => array(),
				'uncompleted_point' => array(),
			),
		);

		$this->model['time_info']['elapsed'] = array(
			'type'       => 'array',
			'array_type' => 'integer',
			'meta_type'  => 'multiple',
			'since'      => '1.0.0',
			'version'    => '1.6.0',
		);

		$this->model['time_info']['completed_point'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['time_info']['uncompleted_point'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['point_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'bydefault' => array(
				'completed' => false,
			),
		);

		$this->model['point_info']['completed'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		parent::__construct( $object );
	}

}
