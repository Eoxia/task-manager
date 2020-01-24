<?php
/**
 * Les propriétés d'un commentaire.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0-ford
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="dropdown-item action-delete wpeo-tooltip-event" data-position="top"
    aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
    data-action="delete_task_comment"
    data-message-delete="<?php echo esc_attr_e( 'Delete this comment ?', 'task-manager' ); ?>"
    data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task_comment' ) ); ?>"
    data-id="<?php echo esc_attr( $comment->data['id'] ); ?>"
    data-loader="actions">
	<span><i class="fas fa-trash"></i></span>
</li>
