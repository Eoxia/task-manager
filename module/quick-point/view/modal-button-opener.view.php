<?php
/**
 * Vue pour le bouton permettant d'ouvrir la modal d'ajout de point rapide dans un tâche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}?><li class="wpeo-modal-event wpeo-tooltip-event quick-point-event"
		data-action="load_modal_quick_point"
		data-title="<?php echo esc_attr_e( 'Quick point add', 'task-manager' ); ?>"
		aria-label="<?php echo esc_attr_e( 'Quick point add', 'task-manager' ); ?>"
		data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_modal_quick_point' ) ); ?>"
		data-quick="true"
		data-class="wpeo-project-wrap quick-point">
	<span class="fa-layers fa-fw">
		<i class="fas fa-list-ul"></i>
		<i class="fas fa-circle" data-fa-transform="up-6 right-8"></i>
		<i class="fas fa-plus" data-fa-transform="shrink-6 up-6 right-8"></i>
	</span>
</li>
