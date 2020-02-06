<?php
/**
 * La définition du modèle des points dans Task Manager
 *
 * @author Eoxia <dev@eoxia.com>
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
	 * @param array  $object     Les données reçu par WordPress.
	 * @param string $req_method La méthode HTTP actuellement utilisée.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['author_id']['default'] = get_current_user_id();
		$this->schema['date']['default']      = current_time( 'mysql' );

		$this->schema['time_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'child'     => array(),
		);

		$this->schema['time_info']['child']['elapsed'] = array(
			'type'      => 'integer',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'default'   => 0,
		);

		$this->schema['time_info']['child']['completed_point'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'default'   => array(),
		);

		$this->schema['time_info']['child']['uncompleted_point'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'default'   => array(),
		);

		$this->schema['point_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'child'     => array(),
		);

		// @todo: A supprimer après la mise à jour 1600.
		$this->schema['point_info']['child']['completed'] = array(
			'type'       => 'boolean',
			'meta_type'  => 'multiple',
			'since'      => '1.0.0',
			'version'    => '1.6.0',
			'deprecated' => '>1.6.0',
		);

		$this->schema['completed'] = array(
			'type'      => 'boolean',
			'field'     => '_tm_completed',
			'meta_type' => 'single',
			'default'   => false,
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->schema['order'] = array(
			'type'      => 'integer',
			'field'     => '_tm_order',
			'meta_type' => 'single',
			'since'     => '1.6.0',
			'version'   => '1.6.0',
			'default'   => 0,
		);

		$this->schema['count_comments'] = array(
			'type'      => 'integer',
			'field'     => '_tm_count_comment',
			'meta_type' => 'single',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'default'   => 0,
		);

		$this->schema['waiting_for'] = array(
			'type'      => 'array',
			'meta_type' => 'single',
			'field'     => '_tm_point_waiting_for_id',
			'default'   => array(),
		);

		parent::__construct( $object, $req_method );
	}

}
