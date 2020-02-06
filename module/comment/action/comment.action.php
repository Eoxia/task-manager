<?php
/**
 * Gestion des actions relatives aux commentaires
 *
 * @since 1.3.6
 * @version 1.6.0
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
		add_action( 'wp_ajax_pagination_update_commments', array( $this, 'callback_pagination_update_commments' ) );
	}

	/**
	 * Utilises la métheode "display" pour récupérer la vue puis l'envoie à la réponse ajax.
	 *
	 * @since 1.3.6
	 */
	public function callback_load_comments() {
		$parent_id = ! empty( $_POST[ 'parent_id' ] ) ? (int) $_POST[ 'parent_id' ] : 0;
		$id        = ! empty( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : 0;
		$frontend  = ( isset( $_POST[ 'frontend' ] ) && 'true' == $_POST[ 'frontend' ] ) ? true : false;
		$number    = ! empty( $_POST[ 'number' ] ) && $_POST[ 'number' ] > 0 ? (int) $_POST[ 'number' ] : 10; // On affiche 10 Commentaires / point
		$offset    = ! empty( $_POST[ 'offset' ] ) && $_POST[ 'offset' ] >= 0 ? (int) $_POST[ 'offset' ] : 0; // On commence à l'élément 0 (triè par date par défault)

		$args = array(
			'number' => $number,
			'offset' => $offset
		);

		$followers = Follower_Class::g()->get( // Auto complete
			array(
				'role' => array(
					'administrator',
				),
			)
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'comment',
			'backend/admin-tag-autocomplete',
			array(
				'followers' => $followers,
			)
		);

		$follower_view = ob_get_clean(); // - - - - - -
		ob_start();
		Task_Comment_Class::g()->display( $parent_id, $id, $frontend, $args );
		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => $frontend ? 'taskManagerFrontend' : 'taskManager',
				'module'           => 'newComment',
				'callback_success' => 'loadedCommentsSuccess',
				'follower_view'    => $follower_view
			)
		);
	}

	/**
	 * Ajoutes un commentaires et l'attache à un point
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_edit_comment() {
		// @info check_ajax_referer( 'edit_comment' );.
		$comment_id = ! empty( $_POST['comment_id'] ) ? (int) $_POST['comment_id'] : 0;
		$post_id    = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$parent_id  = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$date       = ! empty( $_POST['mysql_date'] ) ? sanitize_text_field( $_POST['mysql_date'] ) : current_time( 'mysql' );
		$content    = ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : '';
		$time       = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : Point_Class::g()->calcul_elapsed_time();
		$frontend   = ( isset( $_POST['frontend'] ) && 'true' == $_POST['frontend'] ) ? true : false;
		$notif      = ( isset( $_POST['notif'] ) && ! empty( $_POST['notif'] ) ) ? $_POST['notif']  : array();
		$toggle     = ( isset( $_POST['toggle'] ) && 'true' == $_POST['toggle'] ) ? true : false;

		$comment = Task_Comment_Class::g()->edit_comment( $post_id, $parent_id, $content, $date, $time, $comment_id );

		$number_comments = get_comments( array( 'parent' => $parent_id, 'count' => true ) );
		$count_comments  = 0;

		if( $number_comments > 0 ){
			$count_comments = intval( $number_comments / 10 );
			if( intval( $number_comments % 10 ) > 0 ){
				$count_comments++;
			}
		}

		$comments       = Task_Comment_Class::g()->get_comments( $parent_id, array( 'number' => 10, 'offset' => 0 ) );
		$comment_schema = Task_Comment_Class::g()->get(
			array(
				'schema' => true,
			),
			true
		);

		$view = 'backend';
		if ( $frontend ) {
			$view = 'frontend';
		}

		if( ! empty( $notif ) ){
			Notify_Class::g()->send_notification_followers_are_tags( $notif, $post_id, $parent_id, $comment->data[ 'id' ] );
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $comment->data['post_id'],
			),
			true
		);

		do_action( 'tm_edit_comment', $task, $comment->data['point'], $comment );

		$point = Point_Class::g()->get( array( 'id' => $comment->data['parent_id'] ), true );

		if ( $frontend ) {
			do_action( 'tm_action_after_comment_update', $comment->data['id'] );
		}

		$time_task = $task->data['time_info']['elapsed'];

		ob_start();
		if ( ! $toggle ) {
			Task_Class::g()->display_bodies( $comments );
		} else {
			Task_Class::g()->display_bodies( array( $comment ) );
		}

		$view = ob_get_clean();

		/*if ( $task->data['time_info']['estimated_time'] != null ) {
			$time_task .= ' / ' . $task->data['time_info']['estimated_time'];
		}*/

		if ( ! empty( $task->data['user_info']['affected_id'] ) && empty( $comment_id ) ) {
			foreach ( $task->data['user_info']['affected_id'] as $affected_id ) {
				if ( $affected_id != get_current_user_id() ) {
					Notify_Class::g()->add_notification( $affected_id, get_current_user_id(), $task->data['user_info']['affected_id'], $comment->data['id'], 'comment', TM_NOTIFY_NEW_COMMENT );
				}
			}
		}

		wp_send_json_success(
			array(
				'time'             => array(
					'point' => $comment->data['point']->data['time_info']['elapsed'],
					'task'  => $time_task,
				),
				'point'            => $point,
				'view'             => $view,
				'namespace'        => $frontend ? 'taskManagerFrontend' : 'taskManager',
				'module'           => 'newComment',
				'callback_success' => empty( $comment_id ) ? 'addedCommentSuccess' : 'editedCommentSuccess',
				'comment'          => $comment,
			)
		);
	}

	/**
	 * Passes le commentaire en mode édition au niveau de l'affichage.
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.3.6
	 */
	public function callback_load_edit_view_comment() {
		check_ajax_referer( 'load_edit_view_comment' );

		$comment_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $comment_id ) ) {
			wp_send_json_error();
		}

		$comment = Task_Comment_Class::g()->get(
			array(
				'id' => $comment_id,
			),
			true
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'comment',
			'backend/edit',
			array(
				'task_id'  => $comment->data['post_id'],
				'point_id' => $comment->data['parent_id'],
				'comment'  => $comment,
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'comment',
				'callback_success' => 'loadedEditViewComment',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Met le commentaire en status "trash".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_delete_task_comment() {
		check_ajax_referer( 'delete_task_comment' );

		$comment_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $comment_id ) ) {
			wp_send_json_error();
		}

		$comment = Task_Comment_Class::g()->get(
			array(
				'id' => $comment_id,
			),
			true
		);

		$comment->data['status'] = 'trash';

		$comment = Task_Comment_Class::g()->update( $comment->data );

		$comment->data['point']->data['count_comments']--;

		Point_Class::g()->update( $comment->data['point']->data );

		$task = Task_Class::g()->get(
			array(
				'id' => $comment->data['post_id'],
			),
			true
		);

		$time_task = $task->data['time_info']['elapsed'];

		if ( $task->data['time_info']['estimated_time'] != null ) {
			$time_task .= ' / ' . $task->data['time_info']['estimated_time'];
		}

		wp_send_json_success(
			array(
				'time'             => array(
					'point' => $comment->data['point']->data['time_info']['elapsed'],
					'task'  => $time_task,
				),
				'namespace'        => 'taskManager',
				'module'           => 'comment',
				'callback_success' => 'deletedCommentSuccess',
				'comment'          => $comment
			)
		);
	}

	/**
	 * Charges les commentairs pour le front.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_load_front_comments() {
		// @info check_ajax_referer( 'load_front_comments' );.
		$task_id  = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		$comments = \task_manager\Task_Comment_Class::g()->get(
			array(
				'parent' => $point_id,
			)
		);

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->data['author'] = get_userdata( $comment->data['author_id'] );
			}
		}

		$comment_schema = \task_manager\Task_Comment_Class::g()->get(
			array(
				'schema' => true,
			),
			true
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'comment',
			'frontend/main',
			array(
				'task_id'        => $task_id,
				'point_id'       => $point_id,
				'comments'       => $comments,
				'comment_schema' => $comment_schema,
			)
		);
		$view = ob_get_clean();

		wp_send_json_success(
			array(
				'view'             => $view,
				'namespace'        => 'taskManagerFrontend',
				'module'           => 'comment',
				'callback_success' => 'loadedFrontComments',
			)
		);
	}

	/**
	 * Edition d'un commentaire dans le front.
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_edit_comment_front() {
		check_ajax_referer( 'edit_comment_front' );
		if ( false === is_user_logged_in() ) {
			wp_send_json_error();
		}

		$comment_id = ! empty( $_POST['comment_id'] ) ? (int) $_POST['comment_id'] : 0;
		$post_id    = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$parent_id  = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content    = ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : '';
		$time       = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;
		$new        = 0 === $comment_id ? true : false;

		$content = str_replace( '<div>', '<br>', $content );
		$content = wp_kses(
			$content,
			array(
				'br'      => array(),
				'tooltip' => array(
					'class' => array(),
				),
			)
		);

		$comment = \task_manager\Task_Comment_Class::g()->create(
			array(
				'id'        => $comment_id,
				'post_id'   => $post_id,
				'parent_id' => $parent_id,
				'date'      => current_time( 'mysql' ),
				'content'   => $content,
				'time_info' => array(
					'elapsed' => $time,
				),
			),
			true
		);

		if ( $new ) {
			$point = Point_Class::g()->get(
				array(
					'id' => $parent_id,
				),
				true
			);

			$point->data['count_comments']++;

			Point_Class::g()->update( $point->data, true );
		}

		// Possibilité de lancer une action après ajout d'un commentaire.
		do_action( 'tm_action_after_comment_update', $comment->data['id'] );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'comment',
			'frontend/comment',
			array(
				'comment' => $comment,
			)
		);

		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => 'taskManagerFrontend',
				'module'           => 'comment',
				'callback_success' => ! empty( $comment_id ) ? 'editedCommentSuccess' : 'addedCommentSuccess',
				'comment'          => $comment,
			)
		);
	}

	public function callback_pagination_update_commments(){

		$page_actual = isset( $_POST[ 'page' ] ) ? (int) $_POST[ 'page' ] : 0;
		$point_id = isset( $_POST[ 'point_id' ] ) ? (int) $_POST[ 'point_id' ] : 0;
		$next = isset( $_POST[ 'next' ] ) ? (int) $_POST[ 'next' ] : 0;

		if( ! $page_actual || ! $point_id || ! $next ){
			wp_send_json_error();
		}

		$frontend = false;

		$next = ( $next - 1 ) > 0 ? ( $next - 1 ) *10 : 0;
		$number = 10;

		$args = array(
			'number' => $number,
			'offset' => $next
		);

		$point = Point_Class::g()->get( array( 'id' => $point_id ) , true );

		ob_start();
		Task_Comment_Class::g()->display( $point->data[ 'post_id' ], $point_id, $frontend, $args );
		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => $frontend ? 'taskManagerFrontend' : 'taskManager',
				'module'           => 'comment',
				'callback_success' => 'loadedCommentsSuccess',
			)
		);
	}
}

new Task_Comment_Action();
