<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<li class="point-<?php echo $point->id; ?> wpeo-task-point" data-id="<?php echo $point->id; ?>" >
	<p>
		<?php echo '<span>' . $point->id . '</span> - ' . htmlspecialchars( $point->content ); ?> 
	
		<!-- Time -->
		<?php 
		if($task->option['front_info']['display_time']):
			echo $point->option['time_info']['elapsed'] . 'm';
		endif;
		?>
	</p>
</li>