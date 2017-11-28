<?php namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Follower_Model extends \eoxia\User_Model {


	public function __construct( $object ) {
		$this->model['_tm_auto_elapsed_time'] = array(
			'type'			=> 'boolean',
			'meta_type' => 'single',
			'field' 		=> '_tm_auto_elapsed_time',
		);

		parent::__construct( $object );
	}


}

?>
