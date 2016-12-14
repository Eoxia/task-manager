<?php

namespace task_manager;
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

class Task_Manager_User_Model extends User_Model {
	public function __construct( $object ) {
		parent::__construct( $object );
	}
}
