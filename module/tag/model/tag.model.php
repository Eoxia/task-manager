<?php

namespace task_manager;

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
class Tag_Model extends \eoxia\Term_Model {

	public function __construct( $object ) {
		/**	Instanciation du constructeur de modèle principal / Instanciate the main model constructor	*/
		parent::__construct( $object );
	}

}
