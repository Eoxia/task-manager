<?php if ( ! defined( 'ABSPATH' ) ) exit;

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

	public function get_list_comment_by_user_id_and_date( $user_id, $start_date, $end_date ) {
		if ( empty( $user_id ) || empty( $start_date ) || empty( $end_date ) )
			return null;

		global $wpdb;

		$query =
			"SELECT DISTINCT comment_ID
			FROM {$wpdb->comments}
			WHERE	comment_parent != 0 AND
					user_id = %d AND
					comment_date BETWEEN %s AND %s";

		$list_comment = $wpdb->get_results( $wpdb->prepare( $query, array( $user_id, $start_date, $end_date ) ) );

		$list_point_time = array();

		if ( !empty( $list_comment ) ) {
			foreach ( $list_comment as $comment ) {
				$list_point_time[] = $this->show( $comment->comment_ID );
			}
		}

		return $list_point_time;
	}

	public function get_count_comment() {
		global $wpdb;

		$count_comment = 0;

		$query =
		"SELECT COUNT(*)
		FROM (
		    SELECT distinct comment.comment_ID
		    FROM {$wpdb->comments} AS comment
		    JOIN {$wpdb->usermeta} AS user ON comment.user_id = user.user_id
		    WHERE comment.comment_parent != 0 AND
		    comment.comment_approved = '-34070' AND
		    comment.comment_type = '' AND
		    user.meta_key= 'wp_user_level' AND
		    user.meta_value= 0

		    UNION ALL
		    SELECT distinct c.comment_ID FROM {$wpdb->comments} as c
		    JOIN {$wpdb->users} as u ON c.user_id=u.ID
		    JOIN {$wpdb->usermeta} as um ON ( c.user_id=um.user_id AND um.meta_key='wp_user_level' AND um.meta_value=0 )
		    WHERE c.comment_parent = 0 AND
		    c.comment_approved= '-34070'
		) as x";

		$count_comment = $wpdb->get_var( $query );

		return $count_comment;
	}
}

global $time_controller;
$time_controller = new time_controller_01();

?>
