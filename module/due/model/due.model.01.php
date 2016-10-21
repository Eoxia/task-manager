<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Due time model
 * Les options des temps voulu ( meta, comment )
 * @version 0.1
 * @author EOXIA
 *
 */

class due_mdl_01 extends comment_mdl_01 {
	protected $array_option = array(
		'due_date' => array(
			'type'		=> 'string',
			'function'	=> '',
			'default' 	=> '',
			'required'	=> false,
		),
	);

	public function __construct( $object, $meta_key, $cropped = false ) {
		parent::__construct( $object, $meta_key, $cropped );
	}

}
