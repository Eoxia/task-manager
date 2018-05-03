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
		add_action( 'wp_ajax_import_content', array( $this, 'cb_import_content' ) );
	}

	/**
	 * AJAX Callback - Importe les données selon le format défini.
	 *
	 * %taks%Titre De la tâche.
	 * %point%Intitulé du point.
	 * %point%Intitulé du point.
	 */
	public function cb_import_content() {
		check_ajax_referer( 'import_content' );

		$parent_id = ! empty( $_POST ) && ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : null;

		$content = ! empty( $_POST ) && ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : null;
		if ( null === $content ) {
			wp_send_json_error( array( 'message' => __( 'No content have been given for import', 'task-manager' ) ) );
		}

		$tasks = explode( '%task%', $content );
		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $task ) {
				if ( ! empty( $task ) ) {
					$points = explode( '%point%', $task );
					if ( ! empty( $points ) ) {
						// La première ligne est le nom de la tâche.
						$task = Task_Class::g()->create( array(
							'title'     => $points[0],
							'parent_id' => $parent_id,
						) );
						unset( $points[0] ); // On supprime l'entrée 0.

						// Les suivantes sont des points.
						foreach ( $points as $index => $point ) {
							Point_Class::g()->create( array(
								'post_id' => $task->data['id'],
								'content' => $point,
								'order'   => $index,
							) );
						}
					}
				}
			}
		}

		wp_send_json_success();
	}

}

new Import_Action();
