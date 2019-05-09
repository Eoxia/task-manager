<?php
/**
 * La vue d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="tm-import-tasks-container" >
	<!-- Bouton d'ouverture de la modal pour l'import de tâches -->
	<a href="#" class="page-title-action wpeo-modal-event"
		data-target="tm-import-tasks"
		data-parent="tm-import-tasks-container" ><i class="fas fa-download" ></i>&nbsp;<?php esc_html_e( 'Import', 'task-manager' ); ?></a>

	<!-- Structure de la modal pour l'import de tâches -->
	<div class="wpeo-modal tm-import-tasks">
		<div class="modal-container">
			<div class="modal-header">
				<h2 class="modal-title"><?php echo esc_attr( 'Create tasks from text', 'task-manager' ); ?></h2>
				<div class="modal-close"><i class="fas fa-times"></i></div>
			</div>

			<div class="modal-content"><p><?php Import_Class::g()->display_textarea(); ?></p></div>

			<div class="modal-footer">
				<div class="wpeo-button button-grey button-uppercase modal-close"><span><?php esc_html_e( 'Cancel', 'task-manager' ); ?></span></div>
				<a class="wpeo-button button-main button-uppercase action-input"
					data-parent-id="<?php echo esc_attr( $post->ID ); ?>"
					data-parent="tm-import-tasks"
					data-action="tm_import_tasks_and_points"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'tm_import_tasks_and_points' ) ); ?>" ><span><?php esc_html_e( 'Import', 'task-manager' ); ?></span></a>
			</div>
		</div>
	</div>
</div>
