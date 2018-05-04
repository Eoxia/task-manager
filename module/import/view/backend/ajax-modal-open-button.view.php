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
<!-- Bouton d'ouverture de la modal d'import dans une tâche -->
<li class="wpeo-modal-event wpeo-tooltip-event" data-direction="top"
		aria-label="<?php esc_html_e( 'Import', 'task-manager' ); ?>"
		data-action="load_import_modal"
		data-class="popup-import tm-import-tasks"
		data-title="<?php /* Translators: %s stands for the task title. */ echo esc_html( sprintf( __( 'Import points from text file in : %s', 'task-manager' ), $task->data['title'] ) ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_import_modal' ) ); ?>"
		data-id="<?php echo esc_attr( $task_id ); ?>" >
	<span><i class="fas fa-download" ></i></span>
</li>
