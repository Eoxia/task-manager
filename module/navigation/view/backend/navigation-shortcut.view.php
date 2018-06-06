<?php
/**
 * Vue pour afficher la barre de recherche.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><ul>
	<li class="action-input change-status active"
		data-namespace="taskManager"
		data-module="navigation"
		data-before-method="checkDataBeforeSearch"
		data-parent="form"><?php esc_html_e( 'All tasks', 'task-manager' ); ?></li>

	<li class="action-attribute"
		data-action="load_my_task"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_my_task' ) ); ?>"><?php esc_html_e( 'My task', 'task-manager' ); ?></li>

	<?php echo apply_filters( 'task_manager_navigation_after', '' ); ?>
</ul>
