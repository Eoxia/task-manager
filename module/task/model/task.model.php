<?php
/**
 * La définition du schéma des données d'une tâche.
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
 * La définition du schéma des données d'une tâche.
 */
class Task_Model extends \eoxia\Post_Model {

	/**
	 * Le constructeur défini le schéma.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param Task_Model $object     L'objet.
	 * @param string     $req_method La méthode HTTP actuellement utilisée.
	 */
	public function __construct( $object, $req_method = null ) {
		$this->schema['user_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(),
		);

		$this->schema['user_info']['child']['owner_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Le responsable de la tâche',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
			'default'     => get_current_user_id(),
		);

		$this->schema['user_info']['child']['affected_id'] = array(
			'type'        => 'array',
			'array_type'  => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Les utilisateurs (admin) affectés à la tâche',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
			'default'     => array(),
		);

		$this->schema['user_info']['child']['writer_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Plus utilisé depuis 2016.',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->schema['time_info'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'child'     => array(),
		);

		$this->schema['time_info']['child']['history_time'] = array(
			'type'        => 'array',
			'array_type'  => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'L\'ID des historiques de temps.',
			'todo'        => 'Cet index est sans doute mal nommé (03/01/2018, Jimmy)',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->schema['time_info']['child']['elapsed'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Le temps passé sur la tâche en minute',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->schema['time_info']['child']['elapsed'] = array(
			'type'        => 'integer',
			'meta_type'   => 'multiple',
			'description' => 'Le temps passé sur la tâche en minute',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
		);

		$this->schema['front_info'] = array(
			'type'        => 'array',
			'meta_type'   => 'multiple',
			'description' => 'Les options dans le frontend de WordPress',
			'todo'        => 'Cette donnée n\'est plus utilisé depuis 2016 (03/01/2018, Jimmy)',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
			'child'       => array(),
		);

		$this->schema['front_info']['child']['display_time'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->schema['front_info']['child']['display_user'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->schema['front_info']['child']['display_color'] = array(
			'type'      => 'string',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->schema['task_info'] = array(
			'type'        => 'array',
			'meta_type'   => 'multiple',
			'description' => 'Les informations de la tâche',
			'since'       => '1.0.0',
			'version'     => '1.6.0',
			'child'       => array(),
		);

		$this->schema['task_info']['child']['completed'] = array(
			'type'      => 'boolean',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
		);

		$this->schema['task_info']['child']['order_point_id'] = array(
			'type'       => 'array',
			'array_type' => 'integer',
			'meta_type'  => 'multiple',
			'since'      => '1.0.0',
			'version'    => '1.6.0',
		);

		$this->schema['task_info']['child']['state'] = array(
			'type'      => 'string',
			'meta_type' => 'multiple',
			'since'     => '1.13.0',
			'version'   => '1.13.0',
		);

		$this->schema['last_update'] = array(
			'meta_type' => 'multiple',
			'type'      => 'wpeo_date',
			'context'   => array( 'GET' ),
		);


		$this->schema['count_completed_points'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_tm_count_completed_points',
			'default'   => 0,
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->schema['count_uncompleted_points'] = array(
			'type'      => 'integer',
			'meta_type' => 'single',
			'field'     => '_tm_count_uncompleted_points',
			'default'   => 0,
			'since'     => '1.6.0',
			'version'   => '1.6.0',
		);

		$this->schema['taxonomy'] = array(
			'type'      => 'array',
			'meta_type' => 'multiple',
			'since'     => '1.0.0',
			'version'   => '1.6.0',
			'child'     => array(
				Tag_Class::g()->get_type() => array(
					'meta_type'  => 'multiple',
					'array_type' => 'integer',
					'type'       => 'array',
				),
			),
		);

		$this->schema['associated_document_id'] = array(
			'since'     => '6.0.0',
			'version'   => '6.0.0',
			'type'      => 'array',
			'meta_type' => 'multiple',
		);

		$this->schema['associated_document_id']['child']['image'] = array(
			'since'      => '6.0.0',
			'version'    => '6.0.0',
			'type'       => 'array',
			'array_type' => 'integer',
			'meta_type'  => 'multiple',
		);

		parent::__construct( $object, $req_method );
	}
}
