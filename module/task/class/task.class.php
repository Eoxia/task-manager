<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Task_Class extends Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name 	= '\task_manager\Task_Model';

	/**
 * Le post type
 *
 * @var string
 */
	protected $post_type	= 'wpeo-task';

	/**
 * La clé principale du modèle
 *
 * @var string
 */
	protected $meta_key		= 'wpeo_task';

	/**
 * La route pour accéder à l'objet dans la rest API
 *
 * @var string
 */
	protected $base = 'task-manager/task';

	/**
	 * La version de l'objet
	 *
	 * @var string
	 */
	protected $version = '0.1';

	protected $attached_taxonomy_type = 'wpeo_tag';

	protected $before_put_function = array( array( 'task_manager\Task_Helper', 'update_points' ) );

	protected function construct() {
		parent::construct();
	}

	public function render_task( $task, $class = '', $need_information = true ) {
		$disabled_filter = apply_filters( 'task_header_disabled', '' );
		View_Util::exec( 'task', 'backend/task', array( 'disabled_filter' => $disabled_filter, 'task' => $task, 'class' => $class, 'need_information' => $need_information ) );
	}
}

Task_Class::g();
