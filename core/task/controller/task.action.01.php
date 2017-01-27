<?php if ( ! defined( 'ABSPATH' ) ) exit;

class task_action_01 {
	public function __construct() {
		/** Créer une tâche */
		add_action( 'wp_ajax_create_task', array( $this, 'create_task' ) );
		add_action( 'wp_ajax_edit_task', array( $this, 'ajax_edit_task' ) );
		add_action( 'wp_ajax_archive_task', array( $this, 'ajax_archive_task' ) );
		add_action( 'wp_ajax_unarchive_task', array( $this, 'ajax_unarchive_task' ) );
		add_action( 'wp_ajax_export_task', array( $this, 'ajax_export_task') );
		add_action( 'wp_ajax_send_mail', array( $this, 'ajax_send_mail' ) );
		add_action( 'wp_ajax_delete_task', array( $this, 'ajax_delete_task' ) );

		add_action( 'wp_ajax_load_all_task', array( $this, 'ajax_load_all_task' ) );
		add_action( 'wp_ajax_load_archived_task', array( $this, 'ajax_load_archived_task' ) );



		// add_action( 'wp_ajax_ask_task', array( $this, 'ask_task' ) );



		// add_action('wp_ajax_view_task_setting', array(&$this, 'ajax_view_task_setting'));

		// add_action('wp_ajax_load_archive_task', array(&$this, 'ajax_load_archive_task'));

		//
		// add_action('wp_ajax_export_all_task', array($this, 'ajax_export_all_task'));

		/** Additional informations */

		/** Recharge le contenu de la tâche */
		add_action( 'wp_ajax_reload_task', array( &$this, 'ajax_reload_task' ) );

		/** Tags */
		add_action( 'wp_ajax_view_task_tag', array( &$this, 'ajax_view_task_tag' ) );
		add_action( 'wp_ajax_edit_task_tag', array( &$this, 'ajax_edit_task_tag' ) );

		/** Users */
		add_action( 'wp_ajax_wpeo-edit-task-owner-user', array( &$this, 'ajax_edit_task_owner_user' ) );

		/** WpShop customer */

		/** Compile time */

		/** Summary */

		add_action( 'wp_ajax_send_task_to_element', array( &$this, 'ajax_send_task_to_element' ) );

		add_action( 'wp_ajax_update_due_date', array( &$this, 'ajax_update_due_date' ) );

		/** Time History */
		add_action( 'admin_post_task_manager_time_history', array( $this, 'admin_post_task_time_history' ) );
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
	public function create_task() {
		wpeo_check_01::check( 'wpeo_nonce_create_task' );

		global $task_controller;
		$task = $task_controller->create( array(
			'title' => __( 'New task', 'task-manager' ),
			'parent_id' => !empty( $_POST['parent_id'] ) ? $_POST['parent_id'] : 0,
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
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
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
		// Vérification si $_POST['task_id'] est bien un entier
		$_CLEAN['task']['id'] = $_POST['task']['id'];
		if ( !is_int( $_CLEAN['task']['id'] ) )
			$_CLEAN['task']['id'] = intval( $_CLEAN['task']['id'] );

		wpeo_check_01::check( 'wpeo_nonce_edit_task_' . $_CLEAN['task']['id'] );

		global $task_controller;
		$task = $task_controller->show( $_CLEAN['task']['id'] );

		// Transfert des valeurs dans $_CLEAN
		if ( !empty( $_POST['task']['title'] ) ) {
			$_CLEAN['task']['title'] 	= $_POST['task']['title'];
			$_CLEAN['task']['slug'] 	= ( strchr( $task->slug, 'ask-task-' ) === FALSE ) ? sanitize_title( $_POST['task']['title'] ) : $task->slug;
			$_CLEAN['task']['option']['time_info']['estimated'] = $_POST['task']['option']['time_info']['estimated'];
			$log_message = sprintf( __( 'The task #%d has been edited by the user #%d with the title : %s and the estimated time %d', 'task-manager'), $_CLEAN['task']['id'], get_current_user_id(), $_CLEAN['task']['title'], $_CLEAN['task']['option']['time_info']['estimated'] );
		}

		if ( !empty( $_POST['task']['option']['front_info']['display_color'] ) ) {
			$_CLEAN['task']['option']['front_info']['display_color']	= sanitize_text_field( $_POST['task']['option']['front_info']['display_color'] );
		}

		if ( !empty( $_POST['task']['option']['front_info']['display_user'] ) ) {
			$_CLEAN['task']['option']['front_info']['display_user']	= $_POST['task']['option']['front_info']['display_user'];
			$_CLEAN['task']['option']['front_info']['display_time']	= $_POST['task']['option']['front_info']['display_time'];

			$log_message = sprintf( __( 'The task #%d has been edited by the user #%d with the frontend info display_user : %s and the display_time : %s ', 'task-manager'), $_CLEAN['task']['id'], get_current_user_id(), $_CLEAN['task']['option']['front_info']['display_user'], $_CLEAN['task']['option']['front_info']['display_time'] );
		}

		$task = $task_controller->update( $_CLEAN['task'] );

		/** On log la modification de la tâche */
		taskmanager\log\eo_log( 'wpeo_project',
			array(
			'object_id' => $_CLEAN['task']['id'],
			'message' => $log_message,
		), 0 );

		wp_send_json_success();
	}

	public function ajax_load_all_task() {
		global $task_controller;
		$list_task = $task_controller->index( array( 'post_parent' => 0 ) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );

	}

	public function ajax_load_archived_task() {
		global $task_controller;
		$list_task = $task_controller->index( array( 'post_parent' => 0, 'post_status' => 'archive' ) );
		$status = 'archive';

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	 * Récupères les informations (titre, point, responsable) de la tâche par $_POST['task_id']
	 * et renvoie le template du dashboard pour les tâches
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['task_id'] L'id de la tâche
	 * @return JSON Object { 'success': true|false, 'data': { 'template': '' } }
	 */
	public function ajax_load_dashboard_task() {
	// 	if (  0 === is_int( ( int )$_POST['task_id'] ) )
	// 	  wp_send_json_error();
	// 	else
	// 		$task_id = $_POST['task_id'];
	//
	// 	wpeo_check_01::check( 'wpeo_nonce_load_dashboard_task_' . $task_id );
	//
	// 	// On récupère les informations de la tâche
	// 	global $task_controller;
	// 	$task = $task_controller->show( $task_id );
	//
	// 	ob_start();
	// 	require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'window-task' ) );
	// 	wp_send_json_success( array( 'template' => ob_get_clean() ) );
	// }
	//
	// public function ajax_reload_task() {
	// 	if ( true !== is_int( ( int )$_POST['task_id'] ) )
	// 		wp_send_json_error();
	// 	else
	// 		$task_id = $_POST['task_id'];
	//
	// 	wpeo_check_01::check( 'wpeo_nonce_reload_task_' . $task_id );
	//
	// 	global $task_controller;
	// 	$task = $task_controller->show( $task_id );
	//

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
		$task_id = $_POST['task_id'];

		if ( !is_int( $task_id ) )
			$task_id = intval( $_POST['task_id'] );

		wpeo_check_01::check( 'wpeo_nonce_archive_task_' . $task_id );

		global $tag_controller;
		global $task_controller;

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

		wp_send_json_success();
	}

	public function ajax_unarchive_task() {
		$task_id = $_POST['task_id'];

		if ( !is_int( $task_id ) )
			$task_id = intval( $_POST['task_id'] );

		wpeo_check_01::check( 'wpeo_nonce_archive_task_' . $task_id );

		global $tag_controller;
		global $task_controller;

		// On vérifie que le term archive existe
		$task = $task_controller->show( $task_id );

		$term = get_term_by( 'slug', 'archive', $tag_controller->get_taxonomy() );
		$key = array_search( $term->term_id, $task->taxonomy[$tag_controller->get_taxonomy()] );
		if ( false === $key ) {
			wp_send_json_error();
		} else {
			unset( $task->taxonomy[$tag_controller->get_taxonomy()][$key] );
		}

		$task->status = 'publish';
		$task_controller->update( $task );

		/** On log l'achivage de la tâche */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $task_id,
			'message' => sprintf( __( 'The task #%d has been send to archive by the user #%d', 'task-manager'), $task_id, get_current_user_id() ),
		), 0 );

		wp_send_json_success();
	}

	public function ajax_export_task() {
		wpeo_check_01::check( 'wpeo_nonce_export_task_' . $_POST['id'] );
		global $task_controller;
		global $point_controller;

		$task = $task_controller->show( $_POST['id'] );

		$name_file		= $task->slug . current_time( 'timestamp' ) . '.txt';
		$path_file		= WPEO_TASKMANAGER_EXPORT_DIR . $name_file;
		$url_to_file	= WPEO_TASKMANAGER_EXPORT_URL . $name_file;
		$content_file	= $task->id . ' - ' . $task->title . "\r\n\r\n";
		$content_file = apply_filters( 'task_export', $content_file, $task );

		$fp = fopen( $path_file, 'w' );
		fputs( $fp, $content_file );
		fclose( $fp );

		wp_send_json_success( array( 'url_to_file' => $url_to_file ) );
	}

	/**
	 * Send mail to users affected on the task.
	 *
	 * @return void
	 */
	public function ajax_send_mail() {
		wpeo_check_01::check( 'wpeo_send_mail_task_' . $_POST['id'] );

		global $task_controller;
		$task = $task_controller->show( $_POST['id'] );

		$sender_data = wp_get_current_user();
		$multiple_recipients = array();

		if ( ! empty( $task->option['user_info']['affected_id'] ) ) {
			foreach ( $task->option['user_info']['affected_id'] as $user_id ) {
				$user_info = get_userdata( $user_id );
				$multiple_recipients[] = $user_info->user_email;
			}
		}

		$subject = 'Task Manager: ';
		$subject .= __( 'The task #' . $task->id . ' ' . $task->title, 'task-manager' );
		$body = __( '<p>This mail has been send automatically</p>', 'task-manager' );
		$body .= '<h2>#' . $task->id . ' ' . $task->title . ' send by ' . $sender_data->user_login . ' (' . $sender_data->user_email . ')</h2>';
		$body = apply_filters( 'task_points_mail', $body, $task );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name = get_bloginfo( 'name' );

		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		wp_mail( $multiple_recipients, $subject, $body, $headers );

		wp_send_json_success();
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
		$task_id = $_POST['task_id'];
		if ( !is_int( $task_id ) )
			$task_id = intval( $_POST['task_id'] );

		wpeo_check_01::check( 'wpeo_nonce_delete_task_' . $task_id );

		global $task_controller;
		$task_controller->delete( $task_id );

		/** On log la supression tâche */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $task_id,
			'message' => sprintf( __( 'The task #%d has been deleted by the user #%d', 'task-manager'), $task_id, get_current_user_id() ),
		), 0 );

		wp_send_json_success();
	}

	public function ajax_export_all_task() {
		wpeo_check_01::check( 'wpeo_nonce_export_all_task' );
		global $task_controller;

		$name_file		= '';
		$content_file	= '';
		$list_task_id 	= '';

		if ( !empty( $_POST['array_task_id'] ) ) {
			foreach ( $_POST['array_task_id'] as $task_id ) {
				$task 			= $task_controller->show( $task_id );
				$task->point 	= $task_controller->get_point( $task->option['task_info']['order_point_id'] );
				$point_in_work = false;
				$point_completed = false;
				$list_task_id .= $task_id . ' ';
				$name_file .= $task_id . '_';
				$content_file .= $task_id . ' - ' . $task->title . "

";
				if ( !empty( $task->point ) ) {
					foreach ( $task->point as $point ) {
						if ( $point->option['point_info']['completed'] ) {
							if ( !$point_completed ) {
								$content_file .= __( 'Completed', 'task-manager' ) . '
';
								$point_completed = true;
							}
							$content_file .= '    ' . $point->id . ' - ' . $point->content . "
";
						}
					}

					foreach($task->point as $point) {
						if(!$point->option['point_info']['completed']) {
							if(!$point_in_work) {
								$content_file .= __('In Progress', 'task-manager') . '
';
								$point_in_work = true;
							}
							$content_file .= '    ' . $point->id . ' - ' . $point->content . "
";
						}
					}
				}

				$content_file .= "

";
			}
			$name_file .= current_time('timestamp') . '.txt';
		}

		$path_file		= WPEO_TASK_EXPORT_DIR . $name_file;
		$url_to_file	= WPEO_TASK_EXPORT_URL . $name_file;

		$fp = fopen( $path_file, 'w' );
		fputs( $fp, $content_file );
		fclose( $fp );

		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => 0,
			'message' => sprintf( __( 'The tasks %s has been exported by the user #%d', 'task-manager'), $list_task_id, get_current_user_id() ),
		), 0 );

		wp_send_json_success( array( 'url_to_file' => $url_to_file ) );
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

		$object = $task_controller->show( ( int ) $_POST['object_id'] );

		ob_start();
		$tag_controller->render_list_tag( $object, '' );

		wp_send_json_success( array( 'template' => ob_get_clean() ) );
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
		wpeo_check_01::check( 'wp_nonce_edit_task_tag_' . $_POST['tag_id'] );

		global $task_controller;

		$task = $task_controller->show( $_POST['object_id'] );

		$selected = ( $_POST['selected'] == 'true' ) ? true : false;

		$archive_tag = get_term_by( 'slug', 'archive', 'wpeo_tag' );

		$log_message = '';

		if( $task != null ) {
			if( $selected ) {
				$task->taxonomy['wpeo_tag'][] = ( int ) $_POST['tag_id'];
				$log_message = sprintf( __( 'The tag %d has selected for the task #%d by the user #%d', 'task-manager'), $tag_name, $_POST['object_id'], get_current_user_id() );

				if( $_POST['tag_id'] == $archive_tag->term_id ) {
					$task->status = 'archive';
				}
			}
			else {
				$key = array_search( ( int ) $_POST['tag_id'], $task->taxonomy['wpeo_tag'] );
				$log_message = sprintf( __( 'The tag %d has deselected for the task #%d by the user #%d', 'task-manager'), $_POST['tag_id'], $_POST['object_id'], get_current_user_id() );

				if( $key > -1 )
					unset( $task->taxonomy['wpeo_tag'][$key] );

					if( $_POST['tag_id'] == $archive_tag->term_id ) {
						$task->status = 'publish';
					}
			}


			$task_controller->update( $task );

			taskmanager\log\eo_log( 'wpeo_project',
			array(
				'object_id' => $_POST['object_id'],
				'message' => $log_message,
			), 0 );
		}

		wp_send_json_success();
	}

	public function ajax_edit_task_owner_user() {
		// wpeo_check_01::check( 'wpeo_nonce_edit_task_owner_user_' . $_POST['owner_id'] );

		global $task_controller;

		/** On modifie l'utilisateur et on met à jour la tâche */
		$task = array( 'id' => $_POST['task_id'], 'option' => array( 'user_info' => array( 'owner_id' => $_POST['owner_id'] ) ) );
		$task_controller->update( $task );

		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $_POST['task_id'],
			'message' => sprintf( __( 'The owner for the task #%d has been changed to %d by the user #%d', 'task-manager'), $_POST['task_id'], $_POST['owner_id'], get_current_user_id() )
		), 0 );

		wp_send_json_success( array( 'owner_id' => $_POST['owner_id'] ) );
	}

	/**
	 * Charges toutes les tâches liée à l'utilisateur et renvoie les template en JSON
	 * Renvoie également le temps total passé/estimé de toutes ses tâches
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['customer_id'] L'id du client
	 * @return JSON Object { 'success': true|false, 'data': { template: '' } }
	 */
	public function ajax_load_task_customer() {
		global $task_controller;

		if ( !empty( $_POST['_wpnonce'] ) )
			wpeo_check_01::check( 'wpeo_nonce_customer_task_' . $_POST['customer_id'] );

		$template = '';
		$list_id = array();
		$total_time = array( 'elapsed' => 0, 'estimated' => 0 );

		$wps_orders_mdl = new wps_orders_mdl();
		$list_order 	= $wps_orders_mdl->get_customer_orders( $_POST['customer_id'] );

		if( !empty( $list_order ) ) {
			foreach( $list_order as $order_index => $order ) {
				$list_task = $task_controller->index( array( 'post_parent' => $order->ID ) );

				if( !empty( $list_task ) ) {
					foreach( $list_task as $task_index => $task ) {
						$list_id[] = $task->id;
						$list_task[$task_index] = $task_controller->get_task_information( $list_task[$task_index] );
						$list_task[$task_index]->class .= ' wpeo-project-task-customer wpeo-project-task-customer-' . $_POST['customer_id'] . ' ';
						ob_start();
						$task_controller->render_task( $list_task[$task_index], '', false );
						$template .= ob_get_clean();
					}
				}

				$list_order[$order_index]->task = $list_task;
			}
		}

		$list_task = $task_controller->index( array( 'post_parent' => $_POST['customer_id'] ) );

		if( !empty( $list_task ) ) {
			foreach( $list_task as $index => $task ) {
				$list_id[] = $task->id;
				$list_task[$index] = $task_controller->get_task_information( $list_task[$index] );

				// Temps total
				$total_time['elapsed'] += (int) $list_task[$index]->option['time_info']['elapsed'];
				$total_time['estimated'] += (int) $list_task[$index]->option['time_info']['estimated'];

				// C'est une tâche d'un client
				$list_task[$index]->class .= ' wpeo-project-task-customer wpeo-project-task-customer-' . $_POST['customer_id'] . ' ';

				// Le template
				ob_start();
				$task_controller->render_task( $list_task[$index], '', false );
				$template .= ob_get_clean();
			}
		}

		if ( !empty( $_POST['_wpnonce'] ) )
			wp_send_json_success( array( 'list_id' => $list_id, 'template' => $template, 'total_time' => $total_time ) );

		return $list_task;
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
				$list_task_model = $this->get_summary_for_wpshop_customer();
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

	/**
	 * Utilises la méthode ajax_load_task_customer pour récupérer toutes les tâches
	 * du client WPShop
	 * @return Array object task
	 */
	public function get_summary_for_wpshop_customer() {
		$list_task = $this->ajax_load_task_customer();
		return $list_task;
	}

	public function ajax_send_task_to_element() {
		if (  0 === is_int( ( int )$_POST['task_id'] ) ) {
			wp_send_json_error();
		}
		else {
			$task_id = $_POST['task_id'];
		}

		if (  0 === is_int( ( int )$_POST['element_id'] ) ) {
			wp_send_json_error();
		}
		else {
			$element_id = $_POST['element_id'];
		}

		global $task_controller;

		wpeo_check_01::check( 'wpeo_nonce_send_to_task_' . $task_id );

		if ( get_post( $element_id ) === null )
			wp_send_json_error();

		$task_controller->update( array( 'id' => $task_id, 'parent_id' => $element_id ) );

		wp_send_json_success( array( 'task_id' => $task_id ) );
	}

	public function ajax_update_due_date() {
		if (  0 === is_int( ( int )$_POST['task_id'] ) ) {
			wp_send_json_error();
		}
		else {
			$task_id = $_POST['task_id'];
		}

		global $task_controller;

		wpeo_check_01::check( 'wpeo_nonce_due_date_' . $task_id );

		$date_info = array( 'due' => $_POST['due_date'] );

		$task = $task_controller->show( $task_id );

		if( false === DateTime::createFromFormat('Y-m-d', $date_info['due'] ) ) {
			$date_info['due_archive'][] = $date_info['due'];
		}

		$task_controller->update( array( 'id' => $task_id, 'option' => array( 'date_info' => $date_info ) ) );

		wp_send_json_success();
	}

	public function admin_post_task_time_history() {
		if (  0 === is_int( ( int )$_GET['task_id'] ) ) {
			die();
		} else {
			$task_id = $_GET['task_id'];
		}

		global $task_controller;

		$task = $task_controller->show( $task_id );

		require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'task', 'time-history' ) );
	}
}

new task_action_01();
