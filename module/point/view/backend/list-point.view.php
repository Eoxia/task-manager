<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="hidden point-schema">
	<?php View_Util::exec( 'point', 'backend/point', array(
		'point' => $point_schema,
	) ); ?>
</div>

<!-- Uncompleted points -->
<ul class="wpeo-task-point wpeo-task-point-sortable">
	<input type="hidden" class="wpeo-object-id" value="<?php echo $object_id; ?>" />

	<?php if( !empty( $list_point_uncompleted ) ):?>
		<?php foreach( $list_point_uncompleted as $point ):?>
			<?php if( !$point->point_info['completed'] ): ?>
				<?php $custom_class = 'wpeo-task-point-sortable'; ?>
				<?php View_Util::exec( 'point', 'backend/point', array( 'point' => $point, 'custom_class' => $custom_class ) ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>

<?php echo apply_filters( 'point_list_add', '', $object_id ); ?>

	<!-- Completed point -->
<div class="wpeo-task-point-use-toggle">
	<p class="action-attribute" data-action="load_completed_point" data-task[id]="<?php echo $object_id; ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_completed_point_' . $object_id ) ); ?>">
	<span class="dashicons dashicons-plus wpeo-point-toggle-arrow"></span>
	<a class="wpeo-point-toggle-a" href="#" title="<?php __( 'Toggle completed point', 'task-manager' ); ?>"><?php _e( 'Completed point', 'task-manager' ); ?> (<span class='wpeo-task-count-completed'><?php echo count( $list_point_completed ); ?>/<?php echo (count( $list_point_completed ) + count( $list_point_uncompleted )); ?></span>)</a>
	</p>

	<ul class="wpeo-task-point wpeo-task-point-completed wpeo-point-no-sortable hidden">
		<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Loading...' ); ?>" />
	</ul>

</div>
