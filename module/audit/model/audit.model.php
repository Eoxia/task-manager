<?php
/**
 * La définition du schéma des données d'un audit.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La définition du schéma des données d'un audit.
 */
class Audit_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur défini le schéma.
	 *
	 * @since 1.9.0
	 * @version 1.9.0
	 *
	 * @param Audit_Model $object     L'objet.
	 * @param string     $req_method La méthode HTTP actuellement utilisée.
	 */
	public function __construct( $object, $req_method = null ) {

		$this->schema[ 'deadline' ] = array(
			'type'      => 'wpeo_date',
			'meta_type' => 'single',
			'field'     => 'post_deadline',
			'since'     => '1.10.0',
			'version'   => '1.10.0',
			'context'   => array(
				'GET'
			)
		);

		$this->schema['status_audit'] = array(
		 'type'        => 'string',
		 'meta_type'   => 'single',
		 'field'       => 'post_status_audit',
		 'description' => 'Is the status of the audit. Value can be "OK" or "KO".',
		 'since'       => '1.11.0',
		 'version'     => '1.11.0',
 		);

		parent::__construct( $object, $req_method );
	}
}
