<?php
/**
 * Affichage pour gérer les capacités des utilisateurs.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="section-capability">
	<input type="hidden" name="action" value="save_capability_task_manager" />
	<?php wp_nonce_field( 'save_capability_task_manager' ); ?>

	<h3><?php esc_html_e( 'Task Manager Rights Management', 'task-manager' ); ?></h3>

	<p><?php esc_html_e( 'Set access rights to the Task Manager application', 'task-manager' ); ?></p>

	<?php Setting_Class::g()->display_role_has_cap(); ?>

	<?php Setting_Class::g()->display_user_list_capacity(); ?>

	<div class="margin action-input wpeo-button button-progress button-primary right" data-parent="section-capability">
		<span><?php esc_html_e( 'Save', 'task-manager' ); ?></span>
	</div>
</div>
