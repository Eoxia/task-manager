<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Estimated time model
 * Les options des temps estimé ( meta, comment )
 * @version 0.1
 * @author EOXIA
 *
 */

class estimated_mdl_01 extends comment_mdl_01 {
	protected $array_option = array(
		'estimated_time' => array(
			'type'		=> 'integer',
			'function'	=> '',
			'default' 	=> '',
			'required'	=> false,
		),
	);

	public function __construct( $object, $meta_key, $cropped = false ) {
		parent::__construct( $object, $meta_key, $cropped );
	}

}
