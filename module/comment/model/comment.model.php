<?php
/**
 * La définition du modèle des commentaires
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package comment
 * @subpackage model
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * La définition du modèle des commentaires
 */
class Task_Comment_Model extends Comment_Model {

	/**
	 * Le constructeur qui permet de faire la définition du modèle.
	 *
	 * @param Task_Comment_Model $object L'objet.
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct( $object ) {
		$this->model['status']['bydefault'] = '-34070';
		$this->model['author_id']['bydefault'] = get_current_user_id();

		$this->model['time_info'] = array(
			'type'			=> 'array',
			'meta_type' => 'multiple',
			'elapsed' => array(
				'type'			=> 'integer',
				'meta_type'	=> 'multiple',
			),
		);

		parent::__construct( $object );
	}
}
