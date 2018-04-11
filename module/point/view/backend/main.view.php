<?php
/**
 * La vue principale des points dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="points sortable">
	<?php
	\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/points', array(
		'comment_id' => $comment_id,
		'point_id'   => $point_id,
		'parent_id'  => $task_id,
		'points'     => $points_uncompleted,
	) );

	\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
		'point'      => $point_schema,
		'comment_id' => $comment_id,
		'point_id'   => $point_id,
		'parent_id'  => $task_id,
	) );
	?>
</div>

<div class="wpeo-task-point-use-toggle">
	<p 	class="action-attribute"
			data-id="<?php echo esc_attr( $task_id ); ?>"
			data-namespace="taskManager"
			data-module="point"
			data-before-method="beforeLoadCompletedPoint"
			data-action="load_completed_point"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_completed_point' ) ); ?>">

		<span class="dashicons dashicons-plus wpeo-point-toggle-arrow"></span>
		<span class="wpeo-point-toggle-a">
			<?php esc_html_e( 'Completed points', 'task-manager' ); ?>
			(<span class="wpeo-task-count-completed"><span class="point-completed"><?php echo esc_html( $task->data['count_completed_points'] ); ?></span>/<span class="total-point"><?php echo esc_html( $task->data['count_all_points'] ); ?></span></span>)
		</span>
	</p>

	<ul class="points completed hidden"></ul>
</div>
