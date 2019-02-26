<?php namespace task_manager;

 if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( ! empty( $list_message ) ) : ?>
	<?php $message_output = ''; ?>
	<?php $day_time_info = 0; ?>

	<?php foreach ( $list_message as $time => $object ) : ?>
		<?php ob_start(); ?>
		<li class="point">
			<div class="point-hour"><strong><?php echo esc_html( $time ); ?></strong></div>
		</li> <!-- .point -->
		<?php $message_output .= ob_get_clean(); ?>
	<?php endforeach; ?>

	<div class="timeline-block day">
		<section>
			<header data-color="<?php echo Timeline_Class::g()->array_color[$day % count( Timeline_Class::g()->array_color )]; ?>" style="background-color: #<?php echo Timeline_Class::g()->array_color[$day % count( Timeline_Class::g()->array_color )]; ?>;">
				<!--<span class="timeline-pointer"></span>-->
				<svg class="timeline-pointer" height="20" width="10">
					<polygon points="0,0 10,10 0,20" style="fill: #<?php echo Timeline_Class::g()->array_color[$day % count( Timeline_Class::g()->array_color )]; ?>"/>
				</svg>
				<ul>
					<li class="avatar" title="<?php echo get_userdata( get_current_user_id() )->user_email; ?>" ><?php echo get_avatar( get_current_user_id(), 32 ); ?></li>
					<li class="date"><?php echo mysql2date( 'l d/m/Y', $year . '-' . $month . '-' . $day ); ?></li>
					<?php apply_filters( 'tm_filter_timeline_day', '', get_current_user_id(), $year, $month, $day ); ?>
					<li style="width: 30%; text-align: right; height: 36px; line-height: 36px;" ><span><i class="dashicons dashicons-clock"></i><?php echo esc_attr( $day_time_info ); ?> min</span></li>
				</ul>
			</header>

			<div class="timeline-block-content"><ul><?php echo ( $message_output ); ?></ul></div> <!-- timeline-block-content -->
		</section>
	</div> <!-- timeline-block -->
<?php endif; ?>
