<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Fichier de définition du modèle des taxinomies / File for term model definition
*
* @author Evarisk development team <dev@evarisk.com>
* @version 6.0
* @package Digirisk model manager
* @subpackage Taxonomies
*/

/**
 * Classe de définition du modèle des taxinomies / Class for term model definition
*
* @author Evarisk development team <dev@evarisk.com>
* @version 6.0
* @package Digirisk model manager
* @subpackage Taxonomies
*/
class tag_model_01 extends term_mdl_01 {
	/**
	 * Nom de la meta stockant les données / Meta name for data storage
	 * @var string
	 */
	protected $meta_key = '_wpeo_term';
		
	/**
	 * Construction de l'objet taxinomie par remplissage du modèle / Build taxonomy through fill in the model
	 *
	 * @param object $object L'object avec lequel il faut construire le modèle / The object which one to build
	 * @param string $meta_key Le nom de la "meta" contenant la définition complète de l'object sous forme json / The "meta" name containing the complete definition of object under json format
	 * @param boolean $cropped Permet de choisir si on construit le modèle complet ou uniquement les champs principaux / Allows to choose if the entire model have to be build or only main model
	*/
	public function __construct( $object, $meta_key, $cropped ) {
		/**	Instanciation du constructeur de modèle principal / Instanciate the main model constructor	*/
		parent::__construct( $object, $meta_key, $cropped );
	}

}