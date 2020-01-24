<?php
/**
 * La vue principale de la page "wpeomtm-dashboard"
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm-dashboard-header">
	<div class="tm-dashboard-surheader-buttons">
		<a 	href="#"
			class="action-attribute add-new-h2 wpeo-button button-main button-square-30 button-rounded wpeo-tooltip-event"
	        aria-label="<?php esc_html_e( 'Add project', 'task-manager' ); ?>"
	        data-direction="bottom"
			data-action="create_task"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><i class="button-icon fas fa-plus"></i>
		</a>
		<?php require_once PLUGIN_TASK_MANAGER_PATH . '/core/view/modal-import.view.php'; ?>
	</div>
</div>
