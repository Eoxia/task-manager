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
<div class="wpeo-button button-grey button-uppercase modal-close"><span><?php esc_html_e( 'Cancel', 'task-manager' ); ?></span></div>
<a class="wpeo-button button-main button-uppercase action-input"
	data-task-id="<?php echo esc_attr( $task_id ); ?>"
	data-parent="tm-import-tasks"
	data-action="tm_import_tasks_and_points"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'tm_import_tasks_and_points' ) ); ?>" ><span><?php esc_html_e( 'Import', 'task-manager' ); ?></span></a>
