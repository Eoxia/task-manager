<?php namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wpeo-timeline-content">
	<?php
	if ( ! empty( $list_year ) ):
		?> <div class="chrono-line"></div> <?php
		// foreach ( $list_year as $year ):
			?>
			<div>
				<div class="year"><?php echo '2017'; ?></div>
				<?php
				// for ( $month = $current_month; $month > 0; $month-- ):
					Timeline_Class::g()->render_month( get_current_user_id(), 2017, 8 );
					// break;
				// endfor;
				// $current_month = 12;

			?></div>
			<?php
		// endforeach;
	endif;
	?>
</div>
