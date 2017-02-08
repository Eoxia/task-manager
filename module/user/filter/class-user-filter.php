<?php
/**
 * Users filters
 *
 * @since 0.1
 * @version 1.3.6.0
 */

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Users filters
 */
class User_Filter {

	/**
	 * Constructor
	 *
	 * @since 0.1
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_filter( 'task_avatar', array( $this, 'callback_task_avatar' ), 10, 4 );
		add_filter( 'task_footer', array( $this, 'callback_task_footer' ), 10, 2 );
	}

	/**
	 * Display user avatar
	 *
	 * @param  string  $string       The current view.
	 * @param  integer $id           The user ID.
	 * @param  integer $size         The size.
	 * @param  string  $display_name The display name.
	 * @return string                The updated view.
	 *
	 * @since 0.1
	 * @version 1.3.6.0
	 */
	public function callback_task_avatar( $string, $id, $size, $display_name ) {
		$user = $this->get_user_by_id( $id );

		ob_start();
		View_Util::exec( 'user', 'user-gravatar', array(
			'id' => $id,
			'size' => $size,
			'display_name' => $display_name,
			'user' => $user,
		) );
		$string .= ob_get_clean();

		return $string;
	}

	/**
	 * Rendering users at the bottom of a task.
	 *
	 * @param  string     $string  The current view.
	 * @param  Task_Model $element The Task Data.
	 * @return string              The Updated view.
	 *
	 * @since 0.1
	 * @version 1.3.6.0
	 */
	public function callback_task_footer( $string, $element ) {

		ob_start();
		View_Util::exec( 'user', 'display-user', array(
			'element' => $element,
		) );
		$string .= ob_get_clean();

		return $string;
	}
}

new User_Filter();
