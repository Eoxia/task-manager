<?php
/**
 * Gestion des shortcodes des avatars
 *
 * @since 1.3.4.0
 * @version 1.3.6.0
 * @package Task-Manager\avatar
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des avatars
 */
class Avatar_Shortcode {
	public function __construct() {
		add_shortcode( 'task_avatar', array( $this, 'callback_task_avatar' ), 10, 1 );
	}

	public function callback_task_avatar( $param ) {
		$param = shortcode_atts( array(
			'size' => 50,
			'ids' => '',
		), $param, 'task_avatar' );

		$users = Avatar_Class::g()->get_avatars( $param );
		\eoxia\View_Util::exec( 'task-manager', 'avatar', 'avatar', array(
			'users' => $users,
			'size' => $param['size'],
		) );
	}
}

new Avatar_Shortcode();
