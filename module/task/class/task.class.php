<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Task_Class extends Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name 	= '\task_manager\Task_Model';

	/**
 * Le post type
 *
 * @var string
 */
	protected $post_type	= 'wpeo-task';

	/**
 * La clé principale du modèle
 *
 * @var string
 */
	protected $meta_key		= 'wpeo_task';

	/**
 * La route pour accéder à l'objet dans la rest API
 *
 * @var string
 */
	protected $base = 'task-manager/task';

	/**
	 * La version de l'objet
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = 'wpeo_tag';

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\task_manager\get_full_task' );

	protected function construct() {}
}

Task_Class::g();
