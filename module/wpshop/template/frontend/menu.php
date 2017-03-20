<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li class="<?php echo !empty($_GET['account_dashboard_part']) && $_GET['account_dashboard_part'] == 'support' ? 'wps-activ' : ''; ?>">
	<a data-target="menu1" href='?account_dashboard_part=support'>
		<i class="dashicons dashicons-layout"></i>
		<span><?php esc_html_e( 'Support', 'task-manager' ); ?></span>
	</a>
</li>
