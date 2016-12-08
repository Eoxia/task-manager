<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li class="<?php echo !empty($_GET['account_dashboard_part']) && $_GET['account_dashboard_part'] == 'my-task' ? 'wps-activ' : ''; ?>">
	<a data-target="menu1" href='?account_dashboard_part=my-task'>
		<i class="dashicons dashicons-layout"></i>
		<span><?php _e( 'My tickets', 'task-manager' ); ?></span>
	</a>
</li>
