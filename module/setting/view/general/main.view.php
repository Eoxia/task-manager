<?php
/**
 * Gestion des réglages générales de Task Manager.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Evarisk
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-form">
	<input type="hidden" name="action" value="save_general_settings" />
	<?php wp_nonce_field( 'save_general_settings' ); ?>

	<div class="form-element form-align-horizontal">
		<label class="form-field-container">
			<div class="form-field-inline">
				<input type="checkbox" id="search-bar" class="form-field" name="display_search_bar" <?php echo $use_search_in_admin_bar ? esc_attr( 'checked="checked"' ) : ''; ?>>
				<label for="search-bar"><?php esc_html_e( 'Use search task in admin bar', 'task-manager' ); ?></label>
			</div>
		</label>
	</div>

	<div class="wpeo-button button-main action-input" data-parent="wpeo-form">
		<span><?php esc_html_e( 'Save changes', 'task-manager' ); ?></span>
	</div>
</div>
