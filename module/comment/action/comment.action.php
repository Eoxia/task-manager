<?php
/**
 * Gestion des actions relatives aux commentaires
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 * @package Task-Manager\comment
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Gestion des actions relatives aux commentaires
 */
class Task_Comment_Action {

	/**
	 * Constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_comments', array( $this, 'callback_load_comments' ) );
		add_action( 'wp_ajax_edit_comment', array( $this, 'callback_edit_comment' ) );
		add_action( 'wp_ajax_load_edit_view_comment', array( $this, 'callback_load_edit_view_comment' ) );
		add_action( 'wp_ajax_delete_comment', array( $this, 'callback_delete_comment' ) );
	}

	/**
	 * Charges les commentaires d'un point et renvoie la vue à la réponse ajax.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 * @todo nonce
	 */
	public function callback_load_comments() {
		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		$comments = Task_Comment_Class::g()->get( array(
			'post_id' => $task_id,
			'parent' => $point_id,
			'status' => '-34070',
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->author = get_userdata( $comment->author_id );
			}
		}

		$comment_schema = Task_Comment_Class::g()->get( array(
			'schema' => true,
		), true );

		ob_start();
		View_Util::exec( 'comment', 'backend/main', array(
			'task_id' => $task_id,
			'point_id' => $point_id,
			'comments' => $comments,
			'comment_schema' => $comment_schema,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'view' => $view,
			'module' => 'comment',
			'callback_success' => 'loadedCommentsSuccess',
		) );
	}

	/**
	 * Ajoutes un commentaires et l'attache à un point
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_edit_comment() {
		check_ajax_referer( 'edit_comment' );

		$comment_id = ! empty( $_POST['comment_id'] ) ? (int) $_POST['comment_id'] : 0;
		$post_id = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$date = ! empty( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
		$content = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';
		$time = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;

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

		$comment->author = get_userdata( $comment->author_id );

		ob_start();
		View_Util::exec( 'comment', 'backend/comment', array(
			'comment' => $comment,
		) );

		$task = Task_Class::g()->get( array(
			'post__in' => array(
				$comment->post_id,
			),
		), true );

		wp_send_json_success( array(
			'time' => array(
				'point' => $comment->point->time_info['elapsed'],
				'task' => $task->time_info['time_display'] . ' (' . $task->time_info['elapsed'] . 'min)',
			),
			'view' => ob_get_clean(),
			'module' => 'comment',
			'callback_success' => ! empty( $comment_id ) ? 'editedCommentSuccess' : 'addedCommentSuccess',
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
		View_Util::exec( 'comment', 'backend/edit', array(
			'task_id' => $comment->post_id,
			'point_id' => $comment->parent_id,
			'comment' => $comment,
		) );

		wp_send_json_success( array(
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
	public function callback_delete_comment() {
		check_ajax_referer( 'delete_comment' );

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
			'post__in' => array(
				$comment->post_id,
			),
		), true );

		wp_send_json_success( array(
			'time' => array(
				'point' => $comment->point->time_info['elapsed'],
				'task' => $task->time_info['time_display'] . ' (' . $task->time_info['elapsed'] . 'min)',
			),
			'module' => 'comment',
			'callback_success' => 'deletedCommentSuccess',
		) );
	}
}

new Task_Comment_Action();
