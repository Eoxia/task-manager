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
			class="action-attribute add-new-h2 wpeo-button button-size-small button-radius-2"
			data-action="create_task"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><i class="fas fa-plus"></i>
		</a>
		<?php require_once PLUGIN_TASK_MANAGER_PATH . '/core/view/modal-import.view.php'; ?>
	</div>
</div>
