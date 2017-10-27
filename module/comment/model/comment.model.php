<?php
/**
 * La définition du modèle des commentaires
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
 * La définition du modèle des commentaires
 */
class Task_Comment_Model extends \eoxia\Comment_Model {

	/**
	 * Le constructeur qui permet de faire la définition du modèle.
	 *
	 * @param Task_Comment_Model $object L'objet.
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function __construct( $object ) {
		$this->model['time_info'] = array(
			'type' => 'array',
			'meta_type' => 'multiple',
			'bydefault' => array(
				'elapsed' => 15,
			),
			'elapsed' => array(
				'type' => 'integer',
				'meta_type' => 'multiple',
			),
		);

		parent::__construct( $object );
	}
}
