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
		\eoxia\View_Util::exec( 'task-manager', 'import', 'backend/ajax-modal-save-buttons', array(
			'task_id' => $task_id,
		) );
		$buttons_view = ob_get_clean();

		wp_send_json_success( array(
			'view'         => $modal_content,
			'buttons_view' => $buttons_view,
		) );
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
		);

		$post_id = ! empty( $_POST ) && ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$task_id = ! empty( $_POST ) && ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		// if ( empty( $post_id ) && empty( $task_id ) ) {
		// 	wp_send_json_error( array( 'message' => __( 'No element have been given for import data for', 'task-manager' ) ) );
		// }

		$content = ! empty( $_POST ) && ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : null;
		if ( null === $content ) {
			wp_send_json_error( array( 'message' => __( 'No content have been given for import', 'task-manager' ) ) );
		}

		$created_elements = Import_Class::g()->treat_content( $post_id, $content, $task_id );

		if ( ! empty( $created_elements['created']['tasks'] ) ) {
			$response['type'] = 'tasks';
			foreach ( $created_elements['created']['tasks'] as $task ) {
				ob_start();
				\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task', array(
					'task' => $task,
				) );
				$response['view'] .= ob_get_clean();
			}
		} elseif ( ! empty( $created_elements['created']['points'] ) ) {
			$response['type']    = 'points';
			$response['point']   = '';
			$response['task_id'] = $task_id;
			$response['task']    = Task_Class::g()->get( array(
				'id' => $task_id,
			), true );

			$response['task']->data['count_uncompleted_points'] +=  count($created_elements['created']['points']);
			Task_Class::g()->update( $response['task']->data, true );

			foreach ( $created_elements['created']['points'] as $point ) {
				ob_start();
				\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
					'point'      => $point,
					'parent_id'  => $task_id,
					'comment_id' => 0,
					'point_id'   => 0,
				) );
				$response['view'] .= ob_get_clean();
			}
		}

		wp_send_json_success( $response );
	}

}

new Import_Action();
