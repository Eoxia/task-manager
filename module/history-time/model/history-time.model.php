<?php
/**
 * La définition du schéma des données de l'historique du temps.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2015-2017 Eoxia
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
	 * @version 1.4.0
	 *
	 * @param History_Time_Model $object L'objet.
	 */
	public function __construct( $object ) {
		$this->model = array_merge( $this->model, array(
			'due_date' => array(
				'meta_type' => 'multiple',
				'type' => 'wpeo_date',
			),
			'estimated_time' => array(
				'meta_type' => 'multiple',
				'type' => 'integer',
				'bydefault' => '',
			),
			'google_event_id' => array(
				'meta_type' => 'multiple',
				'type' => 'array',
				'bydefault' => array(),
			),
		) );

		parent::__construct( $object );
	}
}
