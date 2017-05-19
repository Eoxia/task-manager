<?php
/**
 * La vue principale des points dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="points sortable">
	<?php
	if ( ! empty( $points_uncompleted ) ) :
		foreach ( $points_uncompleted as $point ) :
			View_Util::exec( 'point', 'backend/point', array(
				'point' => $point,
				'parent_id' => $point->post_id,
			) );
		endforeach;
	endif;

	View_Util::exec( 'point', 'backend/point', array(
		'point' => $point_schema,
		'parent_id' => $task_id,
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
			<?php esc_html_e( 'Points complétés', 'task-manager' ); ?>
			(<span class="wpeo-task-count-completed"><span class="point-completed"><?php echo count( $points_completed ); ?></span>/<span class="total-point"><?php echo count( $points_uncompleted ) + count( $points_completed ); ?></span></span>)
		</span>
	</p>

	<ul class="points completed hidden"></ul>
</div>
