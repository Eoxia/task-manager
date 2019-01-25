<?php
/**
 * Les actions relatives à l'historique de temps.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La définition du schéma des données de l'hitorique du temps .
 */
class History_Time_Model extends \eoxia\Comment_Model {

	/**
	 * Le constructeur défini le schéma.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param History_Time_Model $object     L'objet.
	 * @param string             $req_method La méthode.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['due_date'] = array(
			'meta_type' => 'multiple',
			'type'      => 'wpeo_date',
			'context'   => array( 'GET' ),
		);

		$this->schema['estimated_time'] = array(
			'meta_type' => 'multiple',
			'type'      => 'integer',
			'default'   => 0,
		);

		$this->schema['custom'] = array(
			'meta_type'   => 'single',
			'field'       => '_tm_custom',
			'type'        => 'string',
			'default'     => false,
			'since'       => '1.6.0',
			'version'     => '1.6.0',
			'description' => 'Type de l\'historique',
		);

		parent::__construct( $object, $req_method );
	}
}
