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
		$this->render_list_tag( $element );
		$string .= ob_get_clean();

		return $string;
	}

	public function render_list_tag( $object ) {
		$list_tag_in_object = array();
		$list_tag_id		= array();

		if ( !empty( $object->taxonomy ) && !empty( $object->taxonomy[$this->taxonomy] ) ) {
			foreach( $object->taxonomy[$this->taxonomy] as $tag_id ) {
				$list_tag_in_object[] 	= $this->show( $tag_id );
				$list_tag_id[] 			= $tag_id;
			}
		}

		View_Util::exec( 'tag', 'backend/display-tag-selected', array( 'object' => $object, 'list_tag_in_object' => $list_tag_in_object, 'list_tag_id' => $list_tag_id ) );
	}

}

new Tag_Filter();
