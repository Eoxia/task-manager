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

<div id="tm-indicator-notes-<?php echo esc_attr( $note->data['id'] ); ?>" class="postbox sticky-note">
	<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Ouvrir/fermer la section &nbsp;</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle"><span>&nbsp;</span></h2>
	<div class="inside">

		<div class="action-delete wpeo-button button-square-30 button-rounded button-grey"
			data-direction="top"
			data-action="delete_note"
			data-message-delete="Are you sure you want to delete this note ?"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_note' ) ); ?>"
			data-id="<?php esc_attr( $note->data['id'] ); ?>"
			data-loader="postbox">

			<i class="button-icon fas fa-trash"></i>
		</div>

		<div class="note-content">
			<input type="hidden" name="note_id" value="<?php echo esc_attr( $note->data['id'] ); ?>">
			<div contenteditable="true"><?php trim( $note->data['content'] ); ?></div>
			<span class="placeholder <?php echo empty( $note->data['content'] ) ? '' : 'hidden'; ?>"><i class="fas fa-plus fa-fw"></i> <?php esc_html_e( 'Write your note here ...', 'task-manager' ); ?></span>
		</div>
	</div>
</div>
