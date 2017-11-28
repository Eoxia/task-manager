<?php
/**
 * Gestion des actions relatives aux commentaires
 *
 * @since 1.3.6
 * @version 1.5.0
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Gestion des actions relatives aux commentaires
 */
class Task_Comment_Action {

	/**
	 * Constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.3.6
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_comments', array( $this, 'callback_load_comments' ) );
		add_action( 'wp_ajax_edit_comment', array( $this, 'callback_edit_comment' ) );
		add_action( 'wp_ajax_load_edit_view_comment', array( $this, 'callback_load_edit_view_comment' ) );
		add_action( 'wp_ajax_delete_task_comment', array( $this, 'callback_delete_task_comment' ) );

		add_action( 'wp_ajax_load_front_comments', array( $this, 'callback_load_front_comments' ) );
		add_action( 'wp_ajax_nopriv_load_front_comments', array( $this, 'callback_load_front_comments' ) );

		add_action( 'wp_ajax_edit_comment_front', array( $this, 'callback_edit_comment_front' ) );
		add_action( 'wp_ajax_nopriv_edit_comment_front', array( $this, 'callback_edit_comment_front' ) );
	}

	/**
	 * Utilises la méthode "display" pour récupérer la vue puis l'envoie à la réponse ajax.
	 *
	 * @since 1.3.6
	 * @version 1.5.0
	 *
	 * @return void
	 * @todo nonce
	 */
	public function callback_load_comments() {
		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		ob_start();
		Task_Comment_Class::g()->display( $task_id, $point_id );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'namespace' => 'taskManager',
			'module' => 'comment',
			'callback_success' => 'loadedCommentsSuccess',
		) );
	}

	/**
	 * Ajoutes un commentaires et l'attache à un point
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.4.0-ford
	 */
	public function callback_edit_comment() {
		check_ajax_referer( 'edit_comment' );

		$comment_id = ! empty( $_POST['comment_id'] ) ? (int) $_POST['comment_id'] : 0;
		$post_id = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$date = ! empty( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
		$content = ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : '';
		$time = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;
		$value_changed = ! empty( $_POST['value_changed'] ) ? (bool) $_POST['value_changed'] : 0;

		$content = str_replace( '<div>', '<br>', trim( $content ) );
		$content = wp_kses( $content, array(
			'br' => array(),
			'tooltip' => array(
				'class' => array(),
			)
		) );

		if ( empty( $value_changed ) ) {
			$date = current_time( 'mysql' );
		}

		$comment = Task_Comment_Class::g()->update( array(
			'id' => $comment_id,
			'post_id' => $post_id,
			'parent_id' => $parent_id,
			'date' => $date,
			'content' => $content,
			'time_info' => array(
				'elapsed' => $time,
			),
		) );

		$comments = Task_Comment_Class::g()->get_comments( $parent_id );

		$comment_schema = Task_Comment_Class::g()->get( array(
			'schema' => true,
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/main', array(
			'task_id' => $post_id,
			'point_id' => $parent_id,
			'comments' => $comments,
			'comment_schema' => $comment_schema,
			'comment_selected_id' => 0,
		) );
		$view = ob_get_clean();

		$task = Task_Class::g()->get( array(
			'id' => $comment->post_id,
		), true );

		do_action( 'tm_edit_comment', $task, $comment->point, $comment );

		wp_send_json_success( array(
			'time' => array(
				'point' => $comment->point->time_info['elapsed'],
				'task' => \eoxia\Date_Util::g()->convert_to_custom_hours( $task->time_info['elapsed'] ),
			),
			'view' => $view,
			'namespace' => 'taskManager',
			'module' => 'comment',
			'callback_success' => 'addedCommentSuccess',
			'comment' => $comment,
		) );
	}

	/**
	 * Passes le commentaire en mode édition au niveau de l'affichage.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_load_edit_view_comment() {
		check_ajax_referer( 'load_edit_view_comment' );

		$comment_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $comment_id ) ) {
			wp_send_json_error();
		}

		$comment = Task_Comment_Class::g()->get( array(
			'comment__in' => array( $comment_id ),
			'status' => -34070,
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/edit', array(
			'task_id' => $comment->post_id,
			'point_id' => $comment->parent_id,
			'comment' => $comment,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'comment',
			'callback_success' => 'loadedEditViewComment',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Met le commentaire en status "trash".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_delete_task_comment() {
		check_ajax_referer( 'delete_task_comment' );

		$comment_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $comment_id ) ) {
			wp_send_json_error();
		}

		$comment = Task_Comment_Class::g()->get( array(
			'comment__in' => array( $comment_id ),
			'status' => '-34070',
		), true );

		$comment->status = 'trash';

		$comment = Task_Comment_Class::g()->update( $comment );

		$task = Task_Class::g()->get( array(
			'id' => $comment->post_id,
		), true );

		wp_send_json_success( array(
			'time' => array(
				'point' => $comment->point->time_info['elapsed'],
				'task' => $task->time_info['time_display'] . ' (' . $task->time_info['elapsed'] . 'min)',
			),
			'namespace' => 'taskManager',
			'module' => 'comment',
			'callback_success' => 'deletedCommentSuccess',
		) );
	}

	public function callback_load_front_comments() {
		// check_ajax_referer( 'load_front_comments' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		$comments = \task_manager\Task_Comment_Class::g()->get( array(
			'parent' => $point_id,
			'status' => '-34070',
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->author = get_userdata( $comment->author_id );
			}
		}

		$comment_schema = \task_manager\Task_Comment_Class::g()->get( array(
			'schema' => true,
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'comment', 'frontend/main', array(
			'task_id' => $task_id,
			'point_id' => $point_id,
			'comments' => $comments,
			'comment_schema' => $comment_schema,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'view' => $view,
			'namespace' => 'taskManagerFrontend',
			'module' => 'comment',
			'callback_success' => 'loadedFrontComments',
		));
	}

	public function callback_edit_comment_front() {
		check_ajax_referer( 'edit_comment_front' );
		if ( false === is_user_logged_in() ) {
			wp_send_json_error();
		}

		$comment_id = ! empty( $_POST['comment_id'] ) ? (int) $_POST['comment_id'] : 0;
		$post_id = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content = ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : '';
		$time = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;

		$content = str_replace( '<div>', '<br>', $content );
		$content = wp_kses( $content, array(
			'br' => array(),
			'tooltip' => array(
				'class' => array(),
			)
		) );

		$comment = \task_manager\Task_Comment_Class::g()->update( array(
			'id' => $comment_id,
			'post_id' => $post_id,
			'parent_id' => $parent_id,
			'date' => current_time( 'mysql' ),
			'content' => $content,
			'time_info' => array(
				'elapsed' => $time,
			),
		) );

		$comment->author = get_userdata( $comment->author_id );

		// Ajoutes une demande dans la donnée compilé.
		if ( in_array( 'customer', $comment->author->roles, true ) ) {
			do_action( 'tm_customer_add_entry_customer_ask', $comment->id );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'comment', 'frontend/comment', array(
			'comment' => $comment,
		) );

		wp_send_json_success( array(
			'time' => array(
				'point' => $comment->point->time_info['elapsed'],
				'task' => $comment->task->time_info['elapsed'],
			),
			'view' => ob_get_clean(),
			'namespace' => 'taskManagerFrontend',
			'module' => 'comment',
			'callback_success' => ! empty( $comment_id ) ? 'editedCommentSuccess' : 'addedCommentSuccess',
			'comment' => $comment,
		) );
	}

}

new Task_Comment_Action();
