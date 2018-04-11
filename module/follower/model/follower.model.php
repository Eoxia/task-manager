<?php
/**
 * La définition du schéma des données d'un abonnées.
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
 * La définition du schéma des données d'un abonnées.
 */
class Follower_Model extends \eoxia\User_Model {

	/**
	 * Le constructeur défini le schéma.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param Follower_Model $object     L'objet.
	 * @param string         $req_method La méthode HTTP actuellement utilisée.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['_tm_auto_elapsed_time'] = array(
			'type'      => 'boolean',
			'meta_type' => 'single',
			'field'     => '_tm_auto_elapsed_time',
			'default'   => false,
		);

		parent::__construct( $object, $req_method );
	}


}

?>
