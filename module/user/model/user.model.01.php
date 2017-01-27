<?php
/**
 * Fichier de définition du modèle des utilisateurs / File for user model definition
 *
 * @author Evarisk development team <dev@eoxia.com>
 * @version 6.0
 * @package Model manager
 * @subpackage Custom post type
 */

/**
 * CLasse de définition du modèle des utilisateurs / Class for user model definition
 *
 * @author Evarisk development team <dev@eoxia.com>
 * @version 6.0
 * @package Model manager
 * @subpackage Custom post type
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class wpeo_user_mdl_01 extends user_mdl_01 {
	function __construct($object, $meta_key, $cropped) {
		parent::__construct($object, $meta_key, $cropped);

		/**	Création d'un code couleur pour l'utilisateur si inexistant ou utilisation du code couleur existant	*/
		if ( !empty( $this->id ) && empty( $this->option[ 'user_info']['initial'] ) ) {
			// echo $this->id;
			// echo $this->option['user_info']['initial'];
			// exit(0);
			global $wp_project_user_controller;
			$this->option[ 'user_info' ][ 'initial' ] = $this->build_user_initial( $this );
			$this->option[ 'user_info' ][ 'avatar_color' ] = $this->avatar_color[ array_rand( $this->avatar_color, 1 ) ];
			$wp_project_user_controller->update( $this );
		}
	}
}
