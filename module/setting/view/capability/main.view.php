<?php
/**
 * Affichage pour gérer les capacités des utilisateurs.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Evarisk
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="section-capability">
	<input type="hidden" name="action" value="save_capability_task_manager" />
	<?php wp_nonce_field( 'save_capability_task_manager' ); ?>

	<h3><?php esc_html_e( 'Gestion des droits de Task Manager', 'task-manager' ); ?></h3>

	<p><?php esc_html_e( 'Définissez les droits d\'accés à l\'application Task Manager', 'task-manager' ); ?></p>

	<?php Setting_Class::g()->display_role_has_cap(); ?>

	<?php do_shortcode( '[digi-search icon="dashicons dashicons-search" next-action="display_setting_user_task_manager" type="user" target="list-users"]' ); ?>

	<?php Setting_Class::g()->display_user_list_capacity(); ?>

	<div class="margin action-input wpeo-button button-progress button-primary right" data-parent="section-capability">
		<span><?php esc_html_e( 'Enregistrer', 'task-manager' ); ?></span>
	</div>
</div>
