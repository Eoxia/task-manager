<?php
/**
 * Initialise les actions liées à la barre de recherche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux tâches.
 */
class Navigation_Action {

	/**
	 * Initialise les actions liées à la barre de recherche.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_search', array( $this, 'callback_search' ) );
		add_action( 'search_order', array( $this, 'callback_search_order' ) );
		add_action( 'wp_ajax_load_modal_create_shortcut', array( $this, 'callback_load_modal_create_shortcut' ) );
		add_action( 'wp_ajax_create_shortcut', array( $this, 'callback_create_shortcut' ) );
	}

	/**
	 * Utilises le shorcode "tasks" pour récupérer les tâches correspondant au critères de la recherche.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 * @todo nonce
	 */
	public function callback_search() {
		$term                          = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$task_id                       = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id                      = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$post_parent                   = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$categories_id                 = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : '';
		$user_id                       = ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : '';
		$tm_dashboard_archives_include = ! empty( $_POST['tm_dashboard_archives_include'] ) ? (bool) $_POST['tm_dashboard_archives_include'] : false;
		$status                        = 'any';
		if ( $tm_dashboard_archives_include ) {
			add_filter( 'task_manager_get_tasks_args', function( $args ) {
				$args['status'] .= ',"archive"';

				return $args;
			} );
		}

		ob_start();
		Navigation_Class::g()->display_search_result( $term, $status, $task_id, $point_id, $post_parent, $categories_id, $user_id );
		$search_result_view = ob_get_clean();

		ob_start();
		echo do_shortcode( '[task id="' . $task_id . '" post_parent="' . $post_parent . '" point_id="' . $point_id . '" users_id="' . $user_id . '" categories_id="' . $categories_id . '" term="' . $term . '" posts_per_page="' . \eoxia\Config_Util::$init['task-manager']->task->posts_per_page . '" with_wrapper="0"]' );
		$tasks_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'navigation',
			'callback_success' => 'searchedSuccess',
			'view'             => array(
				'tasks'         => $tasks_view,
				'search_result' => $search_result_view,
			),
		) );
	}
	
	public function callback_search_order( $data ) {
		$posts = get_posts( array(
			'post_status' => array( 'publish', 'inherit', 'draft' ),
			'post_type' => 'wpshop_shop_order',
			'meta_query' => array(
				array(
					'key'     => '_order_postmeta',
					'value'   => $data['term'],
					'compare' => 'LIKE',
				),
			),
		) );
		
		if ( ! empty( $posts ) ) {
			foreach ( $posts as &$post ) {
				$post->meta = get_post_meta( $post->ID, '_order_postmeta', true );
				
				$post->meta['tm_key']  = $post->meta['order_key'];
				
				if ( empty( $post->meta['tm_key'] ) ) {
					$post->meta['tm_key'] = $post->meta['order_temporary_key'];
				}
			}
		}
		
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/search-orders', array(
			'posts' => $posts,
			'term'  => $data['term'],
		) );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
		) );
	}
	
	public function callback_load_modal_create_shortcut() {
		$term          = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$task_id       = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id      = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$post_parent   = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$categories_id = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : '';
		$user_id       = ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : '';
		
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/modal-create-shortcut-content', array(
			'term'          => $term,
			'task_id'       => $task_id,
			'point_id'      => $point_id,
			'post_parent'   => $post_parent,
			'categories_id' => $categories_id,
			'user_id'       => $user_id,
		) );
		$content = ob_get_clean();
		
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/modal-create-shortcut-buttons' );
		$buttons = ob_get_clean();
		
		wp_send_json_success( array(
			'view'          => $content,
			'buttons_view'  => $buttons,
		) );
	}
	
	public function callback_create_shortcut() {
		$name = ! empty( $_POST['shortcut_name'] ) ? sanitize_text_field( $_POST['shortcut_name'] ) : '';
		$data = array();
		$data['term']          = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$data['task_id']       = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$data['point_id']      = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$data['post_parent']   = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$data['categories_id'] = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : '';
		$data['user_id']       = ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : '';
	
		$construct_args = '';
		
		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( ! empty( $value ) ) {
					$construct_args .= '&' . $key . '=' . $value;
				}
			}
		}
		
		if ( empty( $name ) || empty( $construct_args ) ) {
			wp_send_json_error();
		}
		
		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );
	
		$shortcuts['wpeomtm-dashboard'][] = array(
			'label' => $name,
			'page' => 'admin.php',
			'link' => '?page=wpeomtm-dashboard' . $construct_args,
		);
		
		update_user_meta( get_current_user_id(), '_tm_shortcuts', $shortcuts );
		
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/modal-create-shortcut-content-success' );
		$content_success = ob_get_clean();
		
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/modal-create-shortcut-button-success' );
		$button_success = ob_get_clean();
		
		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'navigation',
			'callback_success' => 'createdShortcutSuccess',
			'view_button'      => $button_success,
			'view_content'     => $content_success,
		) );
	}
	
}

new Navigation_Action();