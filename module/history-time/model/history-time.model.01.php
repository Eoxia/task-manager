<?php
/**
 * Model file.
 *
 * @package HistoryTime
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * History time model
 * Les options des temps voulu ( meta, comment )
 *
 * @version 0.1
 * @author EOXIA
 */
class History_time_mdl_01 extends comment_mdl_01 {
	/**
	 * Model definition
	 *
	 * @var array
	 */
	protected $array_option = array(
		'due_date' => array(
			'type'		=> 'string',
			'function'	=> '',
			'default' 	=> '',
			'required'	=> false,
		),
		'estimated_time' => array(
			'type'		=> 'integer',
			'function'	=> '',
			'default' 	=> '',
			'required'	=> false,
		),
	);
}
