<?php
/**
 * La vue principale des points dans le frontend.
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
	if ( ! empty( $points_uncompleted ) ) :
		foreach ( $points_uncompleted as $point ) :
			\eoxia\View_Util::exec( 'task-manager', 'point', 'frontend/point', array(
				'point'     => $point,
				'parent_id' => $point->data['post_id'],
			) );
		endforeach;
	endif;
	?>
</div>

<div class="wpeo-task-point-use-toggle">
	<p>
		<span class="dashicons dashicons-plus wpeo-point-toggle-arrow"></span>
		<span class="wpeo-point-toggle-a">
			<?php esc_html_e( 'Completed points', 'task-manager' ); ?>
			(<span class="wpeo-task-count-completed"><span class="point-completed"><?php echo count( $points_completed ); ?></span>/<span class="total-point"><?php echo count( $points_uncompleted ) + count( $points_completed ); ?></span></span>)
		</span>
	</p>

	<ul class="points completed hidden">
		<?php
		if ( ! empty( $points_completed ) ) :
			foreach ( $points_completed as $point ) :
				\eoxia\View_Util::exec( 'task-manager', 'point', 'frontend/point', array(
					'point'     => $point,
					'parent_id' => $point->data['post_id'],
				) );
			endforeach;
		endif;
		?>
	</ul>
</div>
