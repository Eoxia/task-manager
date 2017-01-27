<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-task-account">
	<?php if( !empty( $list_task ) ): ?>
		<?php foreach( $list_task as $task ): ?>
			<?php require( wpeo_template_01::get_template_part( WPEOMTM_TASK_DIR, WPEOMTM_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'task' ) ); ?>			
		<?php endforeach; ?>
	<?php endif; ?>
	
	<?php if( !empty( $list_order ) ): ?>
		<?php foreach( $list_order as $order ): ?>
			<h2><?php echo $order->post_title; ?></h2>
			<?php if( !empty( $order->task ) ): ?>
				<?php foreach( $order->task as $task ): ?>
					<?php require( wpeo_template_01::get_template_part( WPEOMTM_TASK_DIR, WPEOMTM_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'task' ) ); ?>			
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

</div>