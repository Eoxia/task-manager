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

<p>
<?php
/* translators: */
echo sprintf( esc_html__( 'Your quick point was added on the task #%1$d: %2$s', 'task-manager' ), $task->data['id'], $task->data['title'] );
?>
</p>

<p>
<?php
/* translators: */
echo sprintf( esc_html__( 'You added the %1$s point #%2$d: %3$s', 'task-manager' ), $point->data['completed'] ? __( 'completed', 'task-manager' ) : __( 'uncompleted', 'task-manager' ), $point->data['id'], $point->data['content'] );
?>
</p>
<p>
<?php
/* translators: */
echo sprintf( esc_html__( 'With the comment #%1$d: %2$s with the elapsed time: %3$d minute(s)', 'task-manager' ), $comment->data['id'], $comment->data['content'], $comment->data['time_info']['elapsed'] );
?>
</p>
