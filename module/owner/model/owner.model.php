<?php
/**
 * Model de owner
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Model de owner
 */
class Owner_Model extends \eoxia\User_Model {

	/**
	 * Constructeur de la classe
	 *
	 * @param [type] $object [description].
	 */
	public function __construct( $object ) {
		parent::__construct( $object );
	}

}
