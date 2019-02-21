<?php
/**
 * La définition du modèle des commentaires
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
 * La définition du modèle des commentaires
 */
class Task_Comment_Model extends \eoxia\Comment_Model {

	/**
	 * Le constructeur qui permet de faire la définition du modèle.
	 *
	 * @param Task_Comment_Model $object     L'objet.
	 * @param string             $req_method Get ou Post.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['time_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(
				'elapsed'     => array(
					'type'      => 'integer',
					'meta_type' => 'multiple',
					'default'   => 15,
				),
				'old_elapsed' => array(
					'type'      => 'integer',
					'meta_type' => 'multiple',
				),
			),
		);

		parent::__construct( $object, $req_method );
	}
}
