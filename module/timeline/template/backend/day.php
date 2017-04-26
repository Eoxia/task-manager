<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( ! empty( $list_message ) ) : ?>
	<?php $message_output = ''; ?>
	<?php $day_time_info = 0; ?>

	<?php foreach ( $list_message as $time => $object ) : ?>
		<?php ob_start(); ?>
		<li class="point">
			<div class="point-hour"><strong><?php echo esc_html( $time ); ?></strong></div>
			<ul class="point-list">
				<?php
				if ( ! empty( $object ) ) :
					foreach ( $object as $type => $sub_object ) :
						foreach ( $sub_object as $the_object ) :
							require( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'day', $type ) );
							if ( 'comment' === $type ) {
								$day_time_info += $the_object->option['time_info']['elapsed'];
							}
						endforeach;
					endforeach;
				endif;
				?>
			</ul>
		</li> <!-- .point -->
		<?php $message_output .= ob_get_clean(); ?>
	<?php endforeach; ?>

	<div class="timeline-block day is-hidden">
		<section>
			<header data-color="<?php echo $this->array_color[$day % count( $this->array_color )]; ?>" style="background-color: #<?php echo $this->array_color[$day % count( $this->array_color )]; ?>;">
				<!--<span class="timeline-pointer"></span>-->
				<svg class="timeline-pointer" height="20" width="10">
					<polygon points="0,0 10,10 0,20" style="fill: #<?php echo $this->array_color[$day % count( $this->array_color )]; ?>"/>
				</svg>
				<ul>
					<li class="avatar" title="<?php echo get_userdata( $user_id )->user_email; ?>" ><?php echo get_avatar( $user_id, 32 ); ?></li>
					<li class="date"><?php echo mysql2date( 'l d/m/Y', $year . '-' . $month . '-' . $day ); ?></li>
					<?php apply_filters( 'tm_filter_timeline_day', '', $user_id, $year, $month, $day ); ?>
					<li style="width: 30%; text-align: right; height: 36px; line-height: 36px;" ><span><i class="dashicons dashicons-clock"></i><?php echo esc_attr( $day_time_info ); ?> min</span></li>
				</ul>
			</header>

			<div class="timeline-block-content"><ul><?php echo ( $message_output ); ?></ul></div> <!-- timeline-block-content -->
		</section>
	</div> <!-- timeline-block -->
<?php endif; ?>
