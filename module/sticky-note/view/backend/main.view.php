<?php
/**
 * La vue principale d'une note.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( $last ) : ?>
	<div class="action-attribute" data-direction="top"
		data-action="add_note"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'add_note' ) ); ?>"
		data-loader="postbox">
		<span><i class="fas fa-plus"></i></span>
	</div>
<?php endif; ?>

<div class="action-delete" data-direction="top"
	data-action="delete_note"
	data-message-delete="<?php echo esc_attr_e( 'Are you sure you want to delete this note ?', 'task-manager' ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_note' ) ); ?>"
	data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
	data-loader="postbox">
	<span><i class="fas fa-trash"></i></span>
</div>

<input type="hidden" name="note_id" value="<?php echo esc_attr( $note->data['id'] ); ?>" />
<div contenteditable="true"><?php echo trim( $note->data['content'] ); ?></div>

<?php if ( empty( $note->data['content'] ) ) : ?>
	<span class="placeholder <?php echo empty( $note->data['content'] ) ? '': 'hidden'; ?>"><?php esc_html_e( 'Write your note here', 'task-manager' ); ?></span>
<?php endif; ?>