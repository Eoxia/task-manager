<?php
/**
 * Gestion des points
 *
 * @since 1.3.4.0
 * @version 1.3.4.0
 * @package Task-Manager\point
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Gestion des points
 */
class Point_Class extends Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name 	= 'task_manager\Point_Model';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key		= 'wpeo_point';

	/**
	 * La route pour la rest API
	 *
	 * @var string
	 */
	protected $base = 'point';

	/**
	 * La version pour la rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * Constructeur qui inclus le modèle des points et également des les scripts
	 * JS et CSS nécessaire pour le fonctionnement des points
	 *
	 * @return void
	 */
	protected function construct() {}
}

Point_Class::g();
