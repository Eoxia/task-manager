<?php if ( ! defined( 'ABSPATH' ) ) exit;

class task_action_01 {
	public function __construct() {
		/** Créer une tâche */
		add_action( 'wp_ajax_create_task', array( $this, 'ajax_create_task' ) );
		add_action( 'wp_ajax_edit_task', array( $this, 'ajax_edit_task' ) );
		add_action( 'wp_ajax_archive_task', array( $this, 'ajax_archive_task' ) );
		add_action( 'wp_ajax_export_task', array( $this, 'ajax_export_task') );
		add_action( 'wp_ajax_delete_task', array( $this, 'ajax_delete_task' ) );

		add_action( 'wp_ajax_load_all_task', array( $this, 'ajax_load_all_task' ) );
		add_action( 'wp_ajax_load_archived_task', array( $this, 'ajax_load_archived_task' ) );

		/** Tags */
		add_action( 'wp_ajax_view_task_tag', array( &$this, 'ajax_view_task_tag' ) );
		add_action( 'wp_ajax_edit_task_tag', array( &$this, 'ajax_edit_task_tag' ) );

		add_action( 'wp_ajax_wpeo-edit-task-owner-user', array( &$this, 'ajax_edit_task_owner_user' ) );

		add_action( 'wp_ajax_send_task_to_element', array( &$this, 'ajax_send_task_to_element' ) );

		add_action( 'wp_ajax_update_due_date', array( &$this, 'ajax_update_due_date' ) );
	}

	/**
	 * Créer une tâche en utilisant le modèle $task_mdl_01. Et renvoie le template d'une tâche
	 * grâce à la méthode render_task de l'objet $task_controller_01.
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['parent_id'] L'id du post type actuel. Peut être non défini.
	 * @return JSON Object { 'success': true|false, 'data': { 'template': '' } }
	 */
	public function ajax_create_task() {
		global $task_controller;

		$parent_id = !empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( !check_ajax_referer( 'ajax_create_task', array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create a task: invalid nonce', 'task-manager' ) ) );
		}

		$task = $task_controller->create( array(
			'title' => __( 'New task', 'task-manager' ),
			'parent_id' => $parent_id,
			'author_id' => get_current_user_id(),
			'option' => array( 'user_info' => array( 'owner_id' => get_current_user_id() ) ) ) );

		/** On log la création de la tâche */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $task->id,
			'message' => sprintf( __( 'The task #%d has been created by the user #%d', 'task-manager'), $task->id, get_current_user_id() ),
		), 0 );

		$task = $task_controller->show( $task->id );

		ob_start();
		$task_controller->render_task( $task );
		wp_send_json_success( array( 'template' => ob_get_clean(), 'message' => __( 'Task created', 'task-manager' ) ) );
	}

	/**
	 * Edites le titre, le slug et le temps estimé d'une tâche.
	 *
	 * @param string $_POST['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['task']['id'] L'id de la tâche
	 * @param string $_POST['task']['title'] Le nom de la tâche
	 * @param int $_POST['task']['option']['time_info']['estimated'] Le nombre de minute estimé pour la tâche
	 * @param int $_POST['task'][option][front_info][display_user] 0 ou 1, peut être non défini
	 * @param int $_POST['task'][option][front_info][display_time] 0 ou 1, peut être non défini
	 * @return JSON Object { 'success': true|false, 'data' => { } }
	 */
	public function ajax_edit_task() {
		global $task_controller;
		$task_data = !empty( $_POST['task'] ) ? (array) $_POST['task'] : array();
		$task_data['id'] = !empty( $task_data['id'] ) ? (int) $task_data['id'] : 0;
		$task_data['title'] = !empty( $task_data['title'] ) ? sanitize_text_field( $task_data['title'] ) : '';
		$task = $task_controller->show( $task_data['id'] );
		$task_data['slug'] = ( strchr( $task->slug, 'ask-task-' ) === FALSE ) ? sanitize_title( $task_data['tiel'] ) : $task->slug;
		$task_data['option']['time_info']['estimated'] = !empty( $task_data['option']['time_info']['estimated'] ) ? (int) $task_data['option']['time_info']['estimated'] : 0;

		if ( empty( $task_data ) || $task_data['id'] === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit a task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_edit_task_' . $task_data['id'], array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit a task: invalid nonce', 'task-manager' ) ) );
		}

		$task_controller->update( $task_data );

		wp_send_json_success( array( 'message' => __( 'Task edited' ) ) );
	}

	/**
	 * Ajoutes le tag 'archive' à la tâche. Si le tag 'archive' n'existe pas cette méthode le crée.
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['task_id'] L'id de la tâche
	 * @return JSON Object { 'success': true|false, 'data': { } }
	 */
	public function ajax_archive_task() {
		global $tag_controller;
		global $task_controller;

		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : $task_id;

		if ( $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for archive task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_archive_task_' . $task_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for archive task: invalid nonce', 'task-manager' ) ) );
		}

		// On vérifie que le term archive existe
		$term = get_term_by( 'slug', 'archive', $tag_controller->get_taxonomy() );
		$task = $task_controller->show( $task_id );

		if( empty( $term ) ) {
			// $term = wp_create_term( 'archive', $tag_controller->get_taxonomy() );
			$term = wp_set_object_terms( $task_id, 'archive', $tag_controller->get_taxonomy() );
			$task->taxonomy[$tag_controller->get_taxonomy()][] = $term[0];

		}
		else {
			$task->taxonomy[$tag_controller->get_taxonomy()][] = $term->term_id;
		}

		$task->status = 'archive';
		$task_controller->update( $task );

		/** On log l'achivage de la tâche */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $task_id,
			'message' => sprintf( __( 'The task #%d has been send to archive by the user #%d', 'task-manager'), $task_id, get_current_user_id() ),
		), 0 );

		wp_send_json_success( array( 'message' => __( 'Task archived', 'task-manager' ) ) );
	}


	public function ajax_export_task() {
		global $task_controller;
		global $point_controller;

		$task_id = !empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for export task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_export_task_' . $task_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for archive task: invalid nonce', 'task-manager' ) ) );
		}

		$task = $task_controller->show( $task_id );

		$name_file		= $task->slug . current_time( 'timestamp' ) . '.txt';
		$path_file		= WPEO_TASKMANAGER_EXPORT_DIR . $name_file;
		$url_to_file	= WPEO_TASKMANAGER_EXPORT_URL . $name_file;
		$content_file	= $task->id . ' - ' . $task->title . "\r\n\r\n";
		$content_file = apply_filters( 'task_export', $content_file, $task );

		$fp = fopen( $path_file, 'w' );
		fputs( $fp, $content_file );
		fclose( $fp );

		wp_send_json_success( array( 'url_to_file' => $url_to_file, 'message' => __( 'Task exported', 'task-manager' ) ) );
	}

	/**
	 * Appelle la méthode delete du modèle wpeo_task_model avec l'id de la tâche à supprimer.
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['task_id'] L'id de la tâche
	 * @return JSON Object { 'success': true|false, 'data': { } }
	 */
	public function ajax_delete_task() {
		global $task_controller;
		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for delete task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_delete_task_' . $task_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for delete task: invalid nonce', 'task-manager' ) ) );
		}

		$task_controller->delete( $task_id );

		/** On log la supression tâche */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $task_id,
			'message' => sprintf( __( 'The task #%d has been deleted by the user #%d', 'task-manager'), $task_id, get_current_user_id() ),
		), 0 );

		wp_send_json_success( array( 'message' => __( 'Task deleted', 'task-manager' ) ) );
	}

	public function ajax_load_all_task() {
		global $task_controller;

		if ( !check_ajax_referer( 'ajax_load_all_task', array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for load all task: invalid nonce', 'task-manager' ) ) );
		}

		$list_task = $task_controller->index( array( 'post_parent' => 0 ) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	public function ajax_load_archived_task() {
		global $task_controller;

		if ( !check_ajax_referer( 'ajax_load_archived_task', array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for load all task: invalid nonce', 'task-manager' ) ) );
		}

		$list_task = $task_controller->index( array( 'post_parent' => 0, 'post_status' => 'archive' ) );
		$status = 'archive';

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	 * Fait le rendu des catégories affectées à la tâche
	 *
	 * @param int $_POST['object_id'] L'id de la tâche
	 * @return JSON Object { 'success': true|false, 'data': { 'template': '' } }
	 */
	public function ajax_view_task_tag() {
		global $task_controller;
		global $tag_controller;

		$object_id = !empty( $_POST['object_id'] ) ? (int) $_POST['object_id'] : 0;

		if ( $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for view tag on task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_view_task_tag', array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for view tag on task: invalid nonce', 'task-manager' ) ) );
		}


		$object = $task_controller->show( $object_id );

		ob_start();
		$tag_controller->render_list_tag( $object, '' );

		wp_send_json_success( array( 'template' => ob_get_clean(), 'message' => __( 'Tag edited', 'task-manager' ) ) );
	}

	/**
	 * Affèctes une catégorie à une tâche depuis son $_POST['tag_id']
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['tag_id'] L'id de la catégorie
	 * @param int $_POST['object_id'] L'id de la tâche
	 * @return JSON Object { 'success': true|false, 'data': { } }
	 */
	public function ajax_edit_task_tag() {
		global $task_controller;

		$tag_id = !empty( $_POST['tag_id'] ) ? (int) $_POST['tag_id'] : 0;
		$object_id = !empty( $_POST['object_id'] ) ? (int) $_POST['object_id'] : 0;

		if ( $tag_id === 0 || $object_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit tag on task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_edit_task_tag_' . $tag_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit tag on task: invalid nonce', 'task-manager' ) ) );
		}

		$task = $task_controller->show( $object_id );

		$selected = ( $_POST['selected'] == 'true' ) ? (bool) $_POST['selected'] : false;

		$archive_tag = get_term_by( 'slug', 'archive', 'wpeo_tag' );

		$log_message = '';

		if( $task != null ) {
			if( $selected ) {
				$task->taxonomy['wpeo_tag'][] = $tag_id;
				$log_message = sprintf( __( 'The tag %d has selected for the task #%d by the user #%d', 'task-manager'), $tag_name, $object_id, get_current_user_id() );

				if( $tag_id == $archive_tag->term_id ) {
					$task->status = 'archive';
				}
			}
			else {
				$key = array_search( ( int ) $tag_id, $task->taxonomy['wpeo_tag'] );
				$log_message = sprintf( __( 'The tag %d has deselected for the task #%d by the user #%d', 'task-manager'), $tag_id, $object_id, get_current_user_id() );

				if( $key > -1 )
					unset( $task->taxonomy['wpeo_tag'][$key] );
			}


			$task_controller->update( $task );

			taskmanager\log\eo_log( 'wpeo_project',
			array(
				'object_id' => $object_id,
				'message' => $log_message,
			), 0 );
		}

		wp_send_json_success( array ( 'message' => __( 'Tag edited', 'task-manager' ) ) );
	}

	public function ajax_edit_task_owner_user() {
		global $task_controller;

		$owner_id = !empty( $_POST['owner_id'] ) ? (int) $_POST['owner_id'] : 0;
		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( $owner_id === 0 || $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit owner on task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_edit_task_owner_user_' . $owner_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit owner on task: invalid nonce', 'task-manager' ) ) );
		}

		/** On modifie l'utilisateur et on met à jour la tâche */
		$task = array( 'id' => $task_id, 'option' => array( 'user_info' => array( 'owner_id' => $owner_id ) ) );
		$task_controller->update( $task );

		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $owner_id,
			'message' => sprintf( __( 'The owner for the task #%d has been changed to %d by the user #%d', 'task-manager'), $task_id, $owner_id, get_current_user_id() )
		), 0 );

		wp_send_json_success( array( 'owner_id' => $owner_id, 'message' => __( 'Owner changed', 'task-manager' ) ) );
	}

	/**
	 * Appelle la méthode compile_time de l'objet $task_controller pour compilé le temps de toutes
	 * les tâches récupérées par cette méthode.
	 */
	public function ajax_compile_time() {
		wpeo_check_01::check( 'wpeo_nonce_compile_time' );

		global $task_controller;

		$list_task = $task_controller->index();

		if ( !empty( $list_task ) ) {
			foreach ( $list_task as $task ) {
				$task_controller->compile_time( $task->id );
			}
		}

		wp_send_json_success();
	}

	public function ajax_get_summary() {
		ob_start();

		if ( empty( $_POST['type'] ) )
			wp_send_json_error();

		global $task_controller;

		$total_time = array( 'elapsed' => 0, 'estimated' => 0 );
		$list_task = array();
		$list_task_model = array();

		switch( $_POST['type'] ) {
			case 'tag':
				$list_task = $this->get_summary_for_tag();
				break;
			case 'user':
				$list_task = $this->get_summary_for_user();
				break;
			case 'customer':
				break;
			default:
				break;
		}

		if ( !empty( $list_task ) ) {
			foreach ( $list_task as $task ) {
				$list_task_model[] = $task_controller->show( $task->ID );
			}
		}

		if ( !empty( $list_task_model ) ) {
			foreach ( $list_task_model as $task ) {
				$total_time['elapsed'] += (int) $task->option['time_info']['elapsed'];
				$total_time['estimated'] += (int) $task->option['time_info']['estimated'];
			}
		}

		// Le template
		require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'time' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	public function get_summary_for_tag() {
		global $tag_controller;
		global $task_controller;

		if ( empty( $_POST['filter'] ) )
			return null;

		$list_tag = $tag_controller->list_tag;
		$list_term_id = array();

		// Récupères tous les term_id des catégories présentes dans $_POST['filter']
		foreach ( $_POST['filter'] as &$filter ) {
			$filter = str_replace( '.', '', $filter );
		}

		foreach ( $list_tag as $tag ) {
			if ( in_array( 'wpeo-' . $tag->slug, $_POST['filter'] ) ) {
				$list_term_id[] = $tag->id;
			}
		}

		// On recherche les tâches par rapport aux term_id des tags
		$list_task = $task_controller->get_task_by_term_id( $list_term_id );

		return $list_task;
	}

	/**
	 * Récupères toutes les tâches où l'id des utilisateurs envoyées
	 * dans $_POST['list_user_id'] sont présentes
	 *
	 * @param $_POST['list_user_id'] Le tableau des ID des utilisateurs envoyées
	 * @return array La liste des tâches trouvées
	 */
	public function get_summary_for_user() {
		global $task_controller;

		if ( empty( $_POST['list_user_id'] ) )
			return null;

		$list_task = $task_controller->get_task_by_user_id( $_POST['list_user_id'] );

		return $list_task;
	}
	//
	// /**
	//  * Utilises la méthode ajax_load_task_customer pour récupérer toutes les tâches
	//  * du client WPShop
	//  * @return Array object task
	//  */
	// public function get_summary_for_wpshop_customer() {
	// 	// $list_task = $this->ajax_load_task_customer();
	// 	// return $list_task;
	// }

	public function ajax_send_task_to_element() {
		global $task_controller;

		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$element_id = !empty( $_POST['element_id'] ) ? (int) $_POST['element_id'] : 0;

		if ( $task_id === 0 || $element_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for send task to element', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_send_task_to_element_' . $task_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for send task to element: invalid nonce', 'task-manager' ) ) );
		}

		$task_controller->update( array( 'id' => $task_id, 'parent_id' => $element_id ) );

		wp_send_json_success( array( 'task_id' => $task_id, 'message' => __( 'Element sended', 'task-manager' ) ) );
	}

	public function ajax_update_due_date() {
		global $task_controller;

		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for update due date', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_update_due_date_' . $task_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for update due date: invalid nonce', 'task-manager' ) ) );
		}

		$task_controller->update( array( 'id' => $task_id, 'option' => array( 'date_info' => array( 'due' => $_POST['due_date'] ) ) ) );

		wp_send_json_success( array( 'message' => __( 'Due date updated', 'task-manager' ) ) );
	}
}

new task_action_01();
