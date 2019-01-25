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

<div class="action-attribute wpeo-button button-blue" data-direction="top"
	data-action="add_note"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'add_note' ) ); ?>"
	data-loader="postbox">
	<i class="fas fa-plus"></i>
	<span><?php esc_html_e( 'Click here to add new sticky note', 'task-manager' ); ?></span>
</div>
