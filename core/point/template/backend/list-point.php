<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- Uncompleted points -->
<ul class="wpeo-task-point wpeo-task-point-uncompleted wpeo-task-point-sortable" data-nonce="<?php echo wp_create_nonce( 'ajax_edit_order_point_' . $object_id ); ?>">
	<input type="hidden" class="wpeo-object-id" value="<?php echo $object_id; ?>" />

	<?php if( !empty( $list_point_uncompleted ) ):?>
		<?php foreach( $list_point_uncompleted as $point ):?>
			<?php if( !$point->option['point_info']['completed'] ): ?>
				<?php $custom_class = 'wpeo-task-point-sortable'; ?>
				<?php require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point' ) ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>

<?php echo apply_filters( 'point_list_add', '', $object_id ); ?>

	<!-- Completed point -->
<div class="wpeo-task-point-use-toggle">
	<p>
	<span class="dashicons dashicons-plus wpeo-point-toggle-arrow"></span>
	<a class="wpeo-point-toggle-a" href="#" title="<?php __( 'Toggle completed point', 'task-manager' ); ?>"><?php _e( 'Completed point', 'task-manager' ); ?> (<span class='wpeo-task-count-completed'><?php echo count( $list_point_completed ); ?>/<?php echo (count( $list_point_completed ) + count( $list_point_uncompleted )); ?></span>)</a>
	</p>

	<ul class="wpeo-task-point wpeo-task-point-completed wpeo-point-no-sortable wpeo-no-display">
		<input type="hidden" class="wpeo-object-id" value="<?php echo $object_id; ?>" />
		<?php if( !empty( $list_point_completed ) ):?>
			<?php foreach( $list_point_completed as $point ):?>
				<?php if( $point->option['point_info']['completed'] ): ?>
					<?php require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point' ) ); ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

</div>
