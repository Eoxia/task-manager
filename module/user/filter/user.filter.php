<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class User_Filter {
	public function __construct() {
		add_filter( 'task_avatar', array( $this, 'callback_task_avatar' ), 10, 4 );

		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_task_manager_dashboard_filter' ), 10, 2 );
		add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 10, 2 );
		add_filter( 'task_footer', array( $this, 'callback_task_footer' ), 10, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_footer' ), 12, 2 );
	}

	public function callback_task_avatar( $string, $id, $size, $display_name ) {
		$user = $this->get_user_by_id( $id );
		ob_start();
		View_Util::exec( 'user', 'backend/user-gravatar', array( 'id' => $id, 'size' => $size, 'display_name' => $display_name, 'user' => $user ) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_manager_dashboard_filter( $string ) {
		ob_start();
		View_Util::exec( 'user', 'backend/filter' );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_manager_dashboard_search( $string ) {
		ob_start();
		View_Util::exec( 'user', 'backend/choosen' );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_footer( $string, $element ) {
		/** On récupère le responsable de la tâche */
		$owner_id = ( !empty( $element ) && !empty( $element->user_info ) && !empty( $element->user_info['owner_id'] ) ) ? $element->user_info['owner_id']: 0;
		$owner_user = Task_Manager_User_Class::g()->get_user_by_id( $owner_id );
		$size = 50;

		ob_start();
		View_Util::exec( 'user', 'backend/display-user', array( 'owner_id' => $owner_id, 'element' => $element, 'owner_user' => $owner_user, 'size' => $size ) );
		$string .= ob_get_clean();

		return $string;
	}
}

new User_Filter();
