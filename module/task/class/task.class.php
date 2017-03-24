<?php
/**
 * Gestion des tâches
 *
 * @package Task Manager
 * @subpackage Module/task
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des tâches.
 */
class Task_Class extends Post_Class {

	/**
	 * Toutes les couleurs disponibles pour une t$ache
	 *
	 * @var array
	 */
	public $colors = array(
		'white',
		'red',
		'yellow',
		'green',
		'blue',
		'purple',
	);

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

	/**
	 * La fonction appelée automatiquement après la création de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_post_function = array( '\task_manager\get_full_task' );

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {
		parent::construct();
	}
}

Task_Class::g();
