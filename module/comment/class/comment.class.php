<?php
/**
 * Gestion des commentaires
 *
 * @since 1.3.4.0
 * @version 1.3.6.0
 * @package Task-Manager\comment
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Gestion des commentaires
 */
class Task_Comment_Class extends Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name 	= 'task_manager\Task_Comment_Model';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key		= 'wpeo_time';

	/**
	 * La route pour la rest API
	 *
	 * @var string
	 */
	protected $base = 'task_manager/time';

	/**
	 * La version pour la rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet depuis la base de donnée.
	 *
	 * @var array
	 */
	protected $after_model_get_function = array( '\task_manager\convert_date_display' );

	/**
	 * La fonction appelée automatiquement après l'insertion de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_post_function = array( '\task_manager\compile_time' );

	/**
	 * La fonction appelée automatiquement après la modification de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_put_function = array( '\task_manager\compile_time' );

	/**
	 * Constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	protected function construct() {}
}

Task_Comment_Class::g();
