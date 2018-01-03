<?php
/**
 * La définition du schéma des données de l'historique du temps.
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
 * La définition du schéma des données de l'hitorique du temps.
 */
class History_Time_Model extends \eoxia\Comment_Model {

	/**
	 * Le constructeur défini le schéma.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param History_Time_Model $object L'objet.
	 */
	public function __construct( $object ) {
		$this->model['due_date'] = array(
			'meta_type' => 'multiple',
			'type'      => 'wpeo_date',
		);

		$this->model['estimated_time'] = array(
			'meta_type' => 'multiple',
			'type'      => 'integer',
			'bydefault' => 0,
		);

		$this->model['repeat'] = array(
			'meta_type'   => 'single',
			'field'       => '_tm_repeat',
			'type'        => 'boolean',
			'bydefault'   => false,
			'since'       => '1.6.0',
			'version'     => '1.6.0',
			'description' => 'Permet de faire une récurrence mensuelle',
		);

		parent::__construct( $object );
	}
}
