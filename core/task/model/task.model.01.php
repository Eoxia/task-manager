<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Task model
 * Les options des tâches ( meta, comment )
 * @version 0.1
 * @author EOXIA
 *
 */

class task_model_01 extends post_mdl_01 {
	protected $array_option = array(
		'user_info' => array(
			'owner_id' => array(
				'type' 		=> 'integer',
				'function'	=> '',
				'default'	=> 0,
				'required'	=> false,
			),
			'affected_id' => array(
				'type' 		=> 'array',
				'function'	=> '',
				'default'	=> array(),
				'required'	=> false,
			),
			'writer_id' => array(
				'type'		=> 'integer',
				'function'	=> '',
				'default'	=> 0,
				'required'	=> false,
			),
		),
		'time_info' => array(
			'history_time' => array(
				'type'		=> 'integer',
				'function'	=> '',
				'default'	=> 0,
				'required'	=> false,
			),
			'elapsed' => array(
				'type'		=> 'integer',
				'function'	=> '',
				'default' 	=> 0,
				'required'	=> false,
			),
		),
		'front_info' => array(
			'display_time' => array(
				'type'		=> 'boolean',
				'function'	=> '',
				'default' 	=> false,
				'required'	=> false,
			),
			'display_user' => array(
				'type'		=> 'boolean',
				'function'	=> '',
				'default' 	=> false,
				'required'	=> false,
			),
			'display_color' => array(
				'type'		=> 'string',
				'function'	=> '',
				'default' 	=> 'white',
				'required'	=> false,
			),
		),
		'task_info' => array(
			'completed' => array(
				'type'		=> 'boolean',
				'function'	=> '',
				'default' 	=> false,
				'required'	=> false,
			),
			'order_point_id' => array(
				'type'		=> 'array',
				'function' 	=> '',
				'default'	=> array(),
				'required'	=> false,
			),

		),
	);

	public function __construct( $object, $meta_key, $cropped = false ) {
		$model = $this->get_model();
		$type = $model['type']['field'];

		if ( !empty( $object->$type ) ) {
			$taxonomy_objects = get_object_taxonomies( $object->$type, 'objects' );
			foreach ( $taxonomy_objects as $taxonomy => $taxonomy_def ) {
				$this->model['taxonomy'][$taxonomy] = array(
						'type' => 'array',
						'function'	=> 'post_ctr_01::eo_get_object_terms',
						'field'	=> '',
						'default'	=> array(),
						'required'	=> false,
				);
			}
		}

		parent::__construct( $object, $meta_key, $cropped );
	}
}
