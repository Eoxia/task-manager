<?php namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Owner_Model extends \eoxia\User_Model {

	public function __construct( $object ) {
		parent::__construct( $object );
	}

}

?>
