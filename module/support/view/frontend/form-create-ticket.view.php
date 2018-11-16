<?php
/**
 * Le formulaire pour créer un nouveau ticket.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="wpeo-window-ask-task" >
	<form class="form wpeo-form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
		<input type="hidden" name="action" value="create_ticket">

		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Subject for your request', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<input id="subject" type="text" name="subject" maxlength="150" class="form-field" />
			</label>
		</div>

		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'A description', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<textarea id="description" name="description" rows="6" class="form-field"></textarea>
			</label>
		</div>

		<div class="wpeo-button button-main action-input"
			data-loader="form"
			data-parent="form"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_ticket' ) ); ?>">

			<?php esc_html_e( 'Create ticket', 'task-manager' ); ?>
		</div>
	</form>
</div>
