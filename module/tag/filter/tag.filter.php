<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Tag_Filter {

	public function __construct() {
		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_dashboard_filter' ), 13, 1 );
		add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 12, 2 );

		add_filter( 'task_footer', array( $this, 'callback_task_footer' ), 5, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_footer' ), 11, 2 );
	}

	public function callback_dashboard_filter( $string ) {
		ob_start();
		View_Util::exec( 'tag', 'backend/filter' );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_manager_dashboard_search( $string ) {
		ob_start();
		View_Util::exec( 'tag', 'backend/tag-search' );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_footer( $string, $element ) {
		ob_start();
		$list_tag = Tag_Class::g()->get( array( 'post_id' => $element->id ) );
		View_Util::exec( 'tag', 'backend/display-tag-selected', array( 'object' => $element, 'list_tag' => $list_tag ) );
		$string .= ob_get_clean();

		return $string;
	}

}

new Tag_Filter();
