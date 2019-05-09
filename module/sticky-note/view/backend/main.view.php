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

<div class="action-delete wpeo-button button-square-30 button-rounded button-grey"
	data-direction="top"
	data-action="delete_note"
	data-message-delete="<?php echo esc_attr_e( 'Are you sure you want to delete this note ?', 'task-manager' ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_note' ) ); ?>"
	data-id="<?php echo esc_attr( $note->data['id'] ); ?>"
	data-loader="postbox">

	<i class="button-icon fas fa-trash"></i>
</div>

<input type="hidden" name="note_id" value="<?php echo esc_attr( $note->data['id'] ); ?>" />
<div contenteditable="true"><?php echo trim( $note->data['content'] ); ?></div>

<?php if ( empty( $note->data['content'] ) ) : ?>
	<span class="placeholder <?php echo empty( $note->data['content'] ) ? '' : 'hidden'; ?>"><i class="fas fa-plus fa-fw"></i> <?php esc_html_e( 'Write your note here ...', 'task-manager' ); ?></span>
<?php endif; ?>
