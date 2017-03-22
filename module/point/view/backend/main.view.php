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

<ul class="wpeo-task-point wpeo-task-point-sortable">
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
</ul>

<div class="wpeo-task-point-use-toggle">
	<p 	class="action-attribute"
			data-id="<?php echo esc_attr( $task_id ); ?>"
			data-action="load_completed_point"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_completed_point' ) ); ?>">

		<span class="dashicons dashicons-plus wpeo-point-toggle-arrow"></span>
		<span class="wpeo-point-toggle-a">
			<?php esc_html_e( 'Completed point', 'task-manager' ); ?>
			(<span class="wpeo-task-count-completed"><?php echo count( $points_completed ); ?>/<?php echo count( $points_uncompleted ) + count( $points_completed ); ?></span>)
		</span>
	</p>

	<ul class="wpeo-task-point wpeo-task-point-completed wpeo-point-no-sortable">
		<img src="https://shop.eoxia.com/wp-admin/images/loading.gif" alt="Loading...">
	</ul>
</div>
