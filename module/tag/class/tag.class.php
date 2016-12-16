<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Tag_Class extends Term_Class {
	public $list_tag = array();

	/**
	* Nom du modèle a utiliser / Name of model to use
	* @var string
	*/
	protected $model_name = '\task_manager\Tag_Model';

	/**
	 * Nom de la meta stockant les données / Meta name for data storage
	 * @var string
	 */
	protected $meta_key = 'wpeo_tag';

	/**
	 * Nom de la taxinomie par défaut / Name of default taxonomie
	 * @var string
	 */
	protected $taxonomy = 'wpeo_tag';

	/**
	 * Base de l'url pour la récupération au travers de l'API / Base slug for retriving through API
	 * @var string
	 */
	protected $base = 'tag';

	/**
	 * Numéro de la version courante pour l'API / Current version number for API
	 * @var string
	 */
	protected $version = '0.1';

	protected function construct() {}

}

Tag_Class::g();
