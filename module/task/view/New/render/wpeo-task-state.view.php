
<?php //if ( $data['value'] == 'archive' ) : ?>
<!--	<div class="wpeo-button button-transparent action-attribute button-square-50 project-archive"-->
<!--		data-id="--><?php //echo esc_attr( $data['task_id'] ); ?><!--"-->
<!--		data-nonce="--><?php //echo esc_attr( wp_create_nonce( 'tm_unpack_task' ) ); ?><!--"-->
<!--		data-action="tm_unpack_task">-->
<!--		<i class="fas fa-archive wpeo-tooltip-event" aria-label="--><?php //esc_attr_e( 'Archived', 'task-manager' ); ?><!--"></i>-->
<!--	</div>-->

<div class="wpeo-button button-transparent button-square-50 load-complete-point wpeo-tooltip-event"
	aria-label="<?php esc_html_e( 'Show uncompleted points', 'task-manager' ); ?>"
	data-action="load_point"
	data-nonce="<?php echo wp_create_nonce( 'load_point' ); ?>"
	data-task-id="<?php echo $data['task_id']; ?>"
	data-point-state="uncompleted">
	<i class="button-icon far fa-square"></i>
	<!--				<span>Décomplété (<span class="point-completed">--><?php //echo esc_attr( $data['number_uncompleted_task'] ); ?><!--</span>)</span>-->
</div>

<div class="wpeo-button button-transparent button-square-50 load-complete-point wpeo-tooltip-event"
	aria-label="<?php esc_html_e( 'Show completed points', 'task-manager' ); ?>"
	data-action="load_point"
	data-nonce="<?php echo wp_create_nonce( 'load_point' ); ?>"
	data-task-id="<?php echo $data['task_id']; ?>"
	data-point-state="completed">
	<i class="button-icon far fa-check-square"></i>
	<!--				<span>Complété (<span class="point-completed">--><?php //echo esc_attr( $data['number_completed_task'] ); ?><!--</span>)</span>-->
</div>
