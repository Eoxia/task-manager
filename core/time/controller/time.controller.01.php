<?php

/**
 * Pour avoir l'object name des points
 * @author Jimmy Latour
 * @version 0.1
 */

class time_controller_01 extends comment_ctr_01 {
	protected $model_name 	= 'time_mdl_01';
	protected $meta_key		= 'wpeo_time';

	/** Défini la route par défaut permettant d'accèder aux temps pointés depuis WP Rest API  / Define the default route for accessing to point time from WP Rest API */
	protected $base = 'time';
	protected $version = '0.1';

	public function __construct() {
 		include_once( WPEO_TIME_PATH . '/model/time.model.01.php' );

		add_filter( 'task_window_add_point_controller', array( $this, 'callback_window_add' ), 10, 2 );
		add_filter( 'window_point_content', array( $this, 'callback_window_point_content' ), 10, 3 );

		add_filter( 'task_window_time_date', array( $this, 'callback_task_window_time_date' ), 10, 2 );
		add_filter( 'task_window_time', array( $this, 'callback_task_window_time' ), 10, 2 );
	}

	public function callback_window_add( $string, $element ) {
		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend/window', 'add' ) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_window_point_content( $string, $task_id, $comment_id ) {
		$list_time = $this->index( $task_id, array( 'parent' => $comment_id, 'status' => -34070 ) );

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'list', 'time' ) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_window_time_date( $string ) {
		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'filter-date' ) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_window_time( $string ) {
		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'filter-time' ) );
		$string .= ob_get_clean();

		return $string;
	}

	public function create( $data ) {
		$object = parent::create( $data ) ;
    global $point_controller;

    /** Update time in point */
    $task = $point_controller->increase_time( $object->parent_id, $object->option['time_info']['elapsed'] );

    return array( 'task' => $task, 'time' => $object );
	}

	public function update( $data, $update_time = true ) {
	  $object = parent::update( $data );
	  $task = null;

	  if( $update_time ) {
	    global $point_controller;
	    $task = $point_controller->decrease_time( $data->parent_id, $data->option['time_info']['old_elapsed'] );
	    $task = $point_controller->increase_time( $data->parent_id, $data->option['time_info']['elapsed'] );
	  }

	  return array('task' => $task, 'time' => $object, 'edit' => 'true' );
  }

  public function delete( $id ) {
    global $point_controller;
    $point_time     = $this->show( $id );

    $task = $point_controller->decrease_time( $point_time->parent_id, $point_time->option['time_info']['elapsed'] );

    parent::delete( $id );

    return $task;
  }
}

global $time_controller;
$time_controller = new time_controller_01();

?>
