<?php
/**
 * La vue principale de la metabox des tâches rapides.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="content quick-time-content">
	<input type="hidden" name="action" value="quick_time_add_comment" />
	<?php wp_nonce_field( 'quick_time_add_comment' ); ?>

	<table class="list wpeo-table">
		<thead>
			<tr>
				<th class="task" data-title="<?php esc_html_e( 'Task ID', 'task-manager' ); ?>"><?php esc_html_e( 'Task ID', 'task-manager' ); ?></th>
				<th class="point" data-title="<?php esc_html_e( 'Point ID', 'task-manager' ); ?>"><?php esc_html_e( 'Point ID', 'task-manager' ); ?></th>
				<th class="content" data-title="<?php esc_html_e( 'Comment', 'task-manager' ); ?>"><?php esc_html_e( 'Comment', 'task-manager' ); ?></th>
				<th class="min" data-title="<?php esc_html_e( 'min.', 'task-manager' ); ?>">
					<i class="dashicons dashicons-clock" aria-hidden="true"></i>
					<span class="time"><?php echo esc_attr( isset( $comment_schema->data['time_info']['calculed_elapsed'] ) ? $comment_schema->data['time_info']['calculed_elapsed'] : $comment_schema->data['time_info']['elapsed'] ); ?></span>
					<span><?php esc_html_e( 'min.', 'task-manager' ); ?></span>
				</th>
				<th class="action"><input type="checkbox" /></th>
			</tr>
		</thead>

		<tbody>
			<?php
			$i = 0;
			if ( ! empty( $quicktimes ) ) :
				foreach ( $quicktimes as $key => $quicktime ) :
					\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/item', array(
						'key'       => $key,
						'quicktime' => $quicktime,
						'i'         => $i,
					) );
					$i++;
				endforeach;
			endif;
			?>
		</tbody>

	</table>

	<span class="wpeo-button button-main button-progress action-input"
		data-parent="content"><?php esc_html_e( 'Add time', 'task-manager' ); ?></span>

	<!-- <div class="list">
		<ul class="header">
			<li class="task"><?php esc_html_e( 'Task ID', 'task-manager' ); ?></li>
			<li class="point"><?php esc_html_e( 'Point ID', 'task-manager' ); ?></li>
			<li class="content"><?php esc_html_e( 'Comment', 'task-manager' ); ?></li>
			<li class="min">
				<i class="dashicons dashicons-clock" aria-hidden="true"></i>
				<span class="time"><?php echo esc_attr( isset( $comment_schema->time_info['calculed_elapsed'] ) ? $comment_schema->time_info['calculed_elapsed'] : end( $comment_schema->time_info['elapsed'] ) ); ?></span>
				<span><?php esc_html_e( 'min.', 'task-manager' ); ?></span>
			</li>
			<li class="action"><input type="checkbox" /></li>
		</ul>

		<?php
		// $i = 0;
		// if ( ! empty( $quicktimes ) ) :
		// 	foreach ( $quicktimes as $key => $quicktime ) :
		// 		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/item', array(
		// 			'key'       => $key,
		// 			'quicktime' => $quicktime,
		// 			'i'         => $i,
		// 		) );
		// 		$i++;
		// 	endforeach;
		// endif;
		?>


	</div> -->
</div>
