<?php
/**
 * Déclaration des actions permettant l'import de contenu dans les tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import\Action
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Déclaration des actions permettant l'import de contenu dans les tâches.
 */
class Import_Action {

	/**
	 * Instanciation des actions pour l'import des tâches.
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_import_modal', array( $this, 'cb_load_import_modal' ) );

		add_action( 'wp_ajax_tm_import_tasks_and_points', array( $this, 'cb_tm_import_tasks_and_points' ) );
		add_action( 'wp_ajax_category_not_found_so_create_it', array( $this, 'cb_category_not_found_so_create_it' ) );

		add_action( 'wp_ajax_get_text_from_url_tm', array( $this, 'callback_get_text_from_url' ) );


	}

	/**
	 * AJAX Callback - Charge la vue de la modal permettant d'importer des points dans une tâches.
	 */
	public function cb_load_import_modal() {
		check_ajax_referer( 'load_import_modal' );

		$task_id = ! empty( $_POST ) && ! empty( (int) $_POST['id'] ) ? (int) $_POST['id'] : 0;

		// Récupération de la vue du contenu de la modal.
		ob_start();
		Import_Class::g()->display_textarea();
		$modal_content = ob_get_clean();

		// Récupéreation de la vue des bouttons de la modal.
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'import',
			'backend/ajax-modal-save-buttons',
			array(
				'task_id' => $task_id,
			)
		);
		$buttons_view = ob_get_clean();

		wp_send_json_success(
			array(
				'view'         => $modal_content,
				'buttons_view' => $buttons_view,
			)
		);
	}

	/**
	 * AJAX Callback - Importe les données selon le format défini.
	 *
	 * %task%Titre De la tâche.
	 * %point%Intitulé du point.
	 * %point%Intitulé du point.
	 */
	public function cb_tm_import_tasks_and_points() {
		check_ajax_referer( 'tm_import_tasks_and_points' );

		$response = array(
			'namespace'        => 'taskManager',
			'module'           => 'import',
			'callback_success' => 'importSuccess',
			'type'             => '',
			'view'             => '',
			'category_info'    => array(),
		);

		$post_id = ! empty( $_POST ) && ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$task_id = ! empty( $_POST ) && ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		// if ( empty( $post_id ) && empty( $task_id ) ) {
		// wp_send_json_error( array( 'message' => __( 'No element have been given for import data for', 'task-manager' ) ) );.
		// }.
		$content = ! empty( $_POST ) && ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : null;
		if ( null === $content ) {
			wp_send_json_error( array( 'message' => __( 'No content have been given for import', 'task-manager' ) ) );
		}

		$return_element = Import_Class::g()->treat_content( $post_id, $content, $task_id );

		$created_elements = $return_element[0];
		$response[ 'category_info' ] = $return_element[1];

		if ( ! empty( $created_elements['created']['tasks'] ) ) {
			$response['type'] = 'tasks';
			foreach ( $created_elements['created']['tasks'] as $task ) {
				ob_start();
				\eoxia\View_Util::exec(
					'task-manager',
					'task',
					'backend/task',
					array(
						'task' => $task,
					)
				);
				$response['view'] .= ob_get_clean();
			}
		} elseif ( ! empty( $created_elements['created']['points'] ) ) {
			$response['type']    = 'points';
			$response['point']   = '';
			$response['task_id'] = $task_id;
			$response['task']    = Task_Class::g()->get(
				array(
					'id' => $task_id,
				),
				true
			);

			$response['task']->data['count_uncompleted_points'] += count( $created_elements['created']['points'] );
			Task_Class::g()->update( $response['task']->data, true );

			foreach ( $created_elements['created']['points'] as $point ) {
				ob_start();
				\eoxia\View_Util::exec(
					'task-manager',
					'point',
					'backend/point',
					array(
						'point'      => $point,
						'parent_id'  => $task_id,
						'comment_id' => 0,
						'point_id'   => 0,
					)
				);
				$response['view'] .= ob_get_clean();
			}
		}

		$response['view'] .= ob_get_clean();

		wp_send_json_success( $response );
	}

	public function cb_category_not_found_so_create_it(){
		$task_id  = isset( $_POST[ 'task_id' ] ) ? (int) $_POST[ 'task_id' ] : 0;
		$tag_name = isset( $_POST[ 'category_name' ] ) ? sanitize_text_field( $_POST[ 'category_name' ] ) : '';

		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );



		if( ! $task_id || ! $tag_name ){
			wp_send_json_error();
		}

		$term     = wp_create_term( $tag_name, Tag_Class::g()->get_type() );
		$category = Tag_Class::g()->get(
			array(
				'include' => array( $term['term_id'] ),
			),
			true
		);

		$task = Task_Class::g()->get( array( 'id' =>$task_id ), true );
		if( ! empty( $task ) ){
			$task->data['taxonomy'][ Tag_Class::g()->get_type() ][] = $category->data[ 'id' ];
			$task = Task_Class::g()->update( $task->data, true );
		}

		ob_start();

		echo do_shortcode( '[task_manager_task_tag task_id=' . $task->data['id'] . ']' );

		$footer_task = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'import',
				'callback_success' => 'update_footer_task_category',
				'footertask'       => $footer_task,
				'taskid'           => $task_id
			)
		);
	}

	public function callback_get_text_from_url(){
		$link = ! empty( $_POST ) && ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : null;
		$data = "";
		$error = "";

		if( $link ){
			$data = file_get_contents( $link );
		}else{
			$error = "true";
		}

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'import',
				'callback_success' => 'get_content_from_url_to_import_textarea',
				'content'          => $data,
				'error'            => $error,
				'link'             => $link
			)
		);
	}
}

new Import_Action();
