<?php
/**
 * La liste des tâches ayant dépassé.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
if ( ! empty( $tasks_exceed_time ) ) :
	foreach ( $tasks_exceed_time as $task ) :
		?>
		<tr>
			<td><?php echo esc_html( $task->id ); ?></td>
			<td><?php echo esc_html( $task->title ); ?></td>
			<td>
				<?php if ( ! empty( $task->task_parent->ID ) ) : ?>
					<a target="_blank" href="<?php echo esc_attr( admin_url( 'post.php?post=' . $task->task_parent->ID . '&action=edit' ) ); ?>">
						<?php echo esc_html( $task->task_parent->post_title ); ?>
					</a>
				<?php else : ?>
					<?php echo esc_html( $task->task_parent ); ?>
				<?php endif ; ?>
			</td>

			<td><?php echo esc_html( $task->time_displayed ); ?></td>
			<td><?php echo esc_html( $task->time_exceeded_displayed ); ?></td>
		</tr>
		<?php
	endforeach;
endif;
