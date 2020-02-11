<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $project->uncompleted_tasks ) ) :
	foreach ( $project->uncompleted_tasks as $task ) :
		?>
		<a class="tm-task" href="<?php echo esc_attr( home_url( '/mon-compte/?account_dashboard_part=support&project_id=' . $project->data['id'] . '&task_id=' . $task->data['id'] ) ); ?>">
			<span class="task-header">
				<span class="header-id"><i class="fas fa-hashtag"></i> <?php echo esc_attr( $task->data['id'] ); ?></span>
				<span class="header-time"><i class="far fa-clock"> <?php echo esc_attr( $task->data['time_info']['elapsed'] ); ?></i></span>
<!--				<span class="header-update"></span>-->
			</span>
			<span class="task-content">
				<?php echo esc_attr( $task->data['content'] ); ?>
			</span>
		</a>
	<?php
	endforeach;
else:
endif;


if ( ! empty( $project->completed_tasks ) ) :
	?>
	<div class="tm-task-archived">
		<?php
		foreach ( $project->completed_tasks as $task ) :
			?>
			<a class="tm-task task-archived" href="<?php echo esc_attr( home_url( '/mon-compte/?account_dashboard_part=support&project_id=' . $project->data['id'] . '&task_id=' . $task->data['id'] ) ); ?>">
				<span class="task-header">
					<span class="header-id"><i class="fas fa-hashtag"></i> <?php echo esc_attr( $task->data['id'] ); ?></span>
					<span class="header-time"><i class="far fa-clock"> <?php echo esc_attr( $task->data['time_info']['elapsed'] ); ?></i></span>
					<!--				<span class="header-update"></span>-->
				</span>
				<span class="task-content">
					<?php echo esc_attr( $task->data['content'] ); ?>
				</span>
			</a>
		<?php
		endforeach;
		?>
	</div>
	<?php
else:
endif;
