<?php
/**
 * Vu principale de la modal quand le point rapide a été ajouté.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<p><?php esc_html_e(  sprintf( 'Your quick point was added on the task #%d: %s', $task->data['id'], $task->data['title'] ), 'task-manager' ); ?></p>

<p><?php esc_html_e( sprintf( 'You added the %s point #%d: %s', $point->data['completed'] ? __( 'completed', 'task-manager' ) : __( 'uncompleted', 'task-manager' ), $point->data['id'], $point->data['content'] ), 'task-manager' ); ?></p>
<p><?php esc_html_e( sprintf( 'With the comment #%d: %s with the elapsed time: %d', $comment->data['id'], $comment->data['content'], $comment->data['time_info']['elapsed'] ), 'task-manager' ); ?></p>
