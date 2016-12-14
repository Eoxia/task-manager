<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Tag_Class extends Term_Class {
	public $list_tag = array();

	/**
	* Nom du modèle a utiliser / Name of model to use
	* @var string
	*/
	protected $model_name = 'tag_model_01';

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

	public function render_list_tag( $object ) {
		$list_tag_in_object = array();
		$list_tag_id		= array();

		if ( !empty( $object->taxonomy ) && !empty( $object->taxonomy[$this->taxonomy] ) ) {
			foreach( $object->taxonomy[$this->taxonomy] as $tag_id ) {
				$list_tag_in_object[] 	= $this->show( $tag_id );
				$list_tag_id[] 			= $tag_id;
			}
		}

		require( wpeo_template_01::get_template_part( WPEOMTM_TAG_DIR, WPEOMTM_TAG_TEMPLATES_MAIN_DIR, 'backend', 'display', 'tag-selected' ) );
	}

}

Tag_Class::g();
