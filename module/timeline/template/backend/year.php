<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-timeline-content">
	<?php
	if ( !empty( $list_year ) ):
		?> <div class="chrono-line"></div> <?php
		foreach ( $list_year as $year ):
			?>
			<div>
				<div class="year"><?php echo $year; ?></div>
				<?php
				for ( $month = $current_month; $month > 0; $month-- ):
					$task_timeline->render_month( $user_id, $year, $month );
				endfor;
				$current_month = 12;

			?></div><?php
		endforeach;
	endif;
	?>
</div>
