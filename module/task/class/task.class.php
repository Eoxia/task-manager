<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Task_Class extends Post_Class {
	protected $model_name 	= '\task_manager\Task_Model';
	protected $post_type	= 'wpeo-task';
	protected $meta_key		= 'wpeo_task';

	/** Défini la route par défaut permettant d'accèder aux tâches depuis WP Rest API  / Define the default route for accessing to task from WP Rest API */
	protected $base = 'task';
	protected $version = '0.1';

	protected function construct() {
		parent::construct();
	}

	public function render_task( $task, $class = '', $need_information = true ) {
		$disabled_filter = apply_filters( 'task_header_disabled', '' );
		View_Util::exec( 'task', 'backend/task', array( 'disabled_filter' => $disabled_filter, 'task' => $task, 'class' => $class, 'need_information' => $need_information ) );
	}
}

Task_Class::g();
