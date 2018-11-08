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
	<form class="form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
		<input type="hidden" name="action" value="create_ticket">
		<label for="subject"><?php esc_html_e( 'Subject for your request', 'task-manager' ); ?></label>
		<input id="subject" type="text" name="subject" maxlength="150">
		<label for="description"><?php esc_html_e( 'A description', 'task-manager' ); ?></label>
		<textarea id="description" name="description" rows="6"></textarea>
		<input type="button" data-loader="form" class="action-input" data-parent="form" data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_ticket' ) ); ?>" value="<?php esc_html_e( 'Create ticket', 'task-manager' ); ?>">
	</form>
</div>