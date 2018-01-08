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
			'child'     => array(),
		);

		$this->model['time_info']['child']['elapsed'] = array(
			'type'       => 'array',
			'array_type' => 'integer',
			'meta_type'  => 'multiple',
			'since'      => '1.0.0',
			'version'    => '1.6.0',
			'bydefault'  => array( 0 ),
		);

		$this->model['time_info']['child']['completed_point'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'bydefault' => array(),
		);

		$this->model['time_info']['child']['uncompleted_point'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'bydefault' => array(),
		);

		$this->model['point_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'child'     => array(),
		);

		// @todo: A supprimer après la mise à jour 1600.
		$this->model['point_info']['child']['completed'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->model['completed'] = array(
			'type'      => 'boolean',
			'field'     => '_tm_completed',
			'meta_type' => 'single',
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->model['order'] = array(
			'type'      => 'integer',
			'field'     => '_tm_order',
			'meta_type' => 'single',
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->model['count_comments'] = array(
			'type'      => 'integer',
			'field'     => '_tm_count_comment',
			'meta_type' => 'single',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		parent::__construct( $object );
	}

}
