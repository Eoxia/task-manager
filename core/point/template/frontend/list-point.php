<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-task-point-container">
	<?php if ( !empty ( $points ) ):?>
		<?php foreach ( $points as $key => $point ):?>
			<?php require( wpeoTasksTemplate_ctr::get_template_part( WPEOMTM_TASK_DIR, WPEOMTM_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'point' ) ); ?>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>