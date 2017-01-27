<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- Uncompleted points -->
<ul class="wpeo-task-point wpeo-task-point-sortable">
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
	<p data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_completed_point' ) ); ?>">
	<span class="dashicons dashicons-plus wpeo-point-toggle-arrow"></span>
	<a class="wpeo-point-toggle-a" href="#" title="<?php __( 'Toggle completed point', 'task-manager' ); ?>"><?php _e( 'Completed point', 'task-manager' ); ?> (<span class='wpeo-task-count-completed'><?php echo count( $list_point_completed ); ?>/<?php echo (count( $list_point_completed ) + count( $list_point_uncompleted )); ?></span>)</a>
	</p>

	<ul class="wpeo-task-point wpeo-task-point-completed wpeo-point-no-sortable hidden">
		<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Loading...' ); ?>" />
	</ul>

</div>
