<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap wpeo-project-timeline">
	<h2><?php esc_html_e( 'Timeline', 'task-manager' ); ?></h2>

	<?php \eoxia\View_Util::exec( 'task-manager', 'timeline', 'backend/year', array(
		'list_year' => $list_year,
		'current_month' => $current_month,
	) ); ?>
</div>
