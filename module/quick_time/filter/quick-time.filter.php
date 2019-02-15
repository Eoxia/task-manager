<?php
/**
 * Gestion des filtres relatives aux temps rapides
 *
 * @since 1.9.0
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Gestion des filtres relatives aux temps rapides
 */
class Quick_Time_Filter {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_filter( 'tm_comment_edit_class', array( $this, 'callback_tm_comment_edit_class' ) );
		add_filter( 'tm_comment_edit_before', array( $this, 'callback_tm_comment_edit_before' ) );
    add_filter( 'tm_comment_edit_quicktimemode', array( $this, 'callback_tm_comment_edit_quicktimemode' ) );
	}

	public function callback_tm_comment_edit_class( $class ) {
		if ( isset( $_GET['quicktimemode'] ) ) {
			$class = 'edit';
		}
		return $class;
	}

	public function callback_tm_comment_edit_before( $comment ) {
		$quicktime_index = ! empty( $_GET['quicktimemode'] ) ? (int) $_GET['quicktimemode'] : -1; // WPCS: CSRF ok.

		if ( $quicktime_index != -1 ) {

			$quicktime = $quicktime_index - 1;
			$quicktimes = Quick_Time_Class::g()->get_quicktimes();
			$content = $quicktimes[ $quicktime ][ 'content' ];

			$comment->data['content'] = $content;

			$time_elapsed = Task_Comment_Class::g()->get(
				array(
					'schema' => true,
				),
				true
			);

			if( ! isset( $comment->data['time_info']['calculed_elapsed'] ) ){
				$comment->data['time_info']['calculed_elapsed'] = $time_elapsed->data[ 'time_info' ][ 'elapsed' ];
			}else{

				$comment->data['time_info']['calculed_elapsed'] = $time_elapsed->data[ 'time_info' ][ 'calculed_elapsed' ];
			}
		}
		return $comment;
	}

	public function callback_tm_comment_edit_quicktimemode(){
		if( isset( $_GET[ 'quicktimemode' ] ) ){
			return true;
		}

		return false;
	}
}

new Quick_Time_Filter();
