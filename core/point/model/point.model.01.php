<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Point model
 * Les options des points ( meta, comment )
 * @version 0.1
 * @author EOXIA
 *
 */

class point_model_01 extends comment_mdl_01 {
	protected $array_option = array(
		'time_info' => array(
			'elapsed' => array(
				'type'		=> 'integer',
				'function'	=> '',
				'default' 	=> 0,
				'required'	=> false,
			),
			'completed_point' => array(
				'type' 		=> 'array',
				'function'	=> '',
				'default'	=> null,
				'required'	=> false,
			),
			'uncompleted_point' => array(
				'type' 		=> 'array',
				'function'	=> '',
				'default'	=> null,
				'required'	=> false,
			),
		),
		'point_info' => array(
			'completed' => array(
				'type'		=> 'boolean',
				'function'	=> '',
				'default' 	=> false,
				'required'	=> false,
			),
		),
	);

	public function __construct( $object, $meta_key, $cropped = false ) {
		parent::__construct( $object, $meta_key, $cropped );
	}

}
