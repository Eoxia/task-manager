<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php if ( !empty( $list_message ) ): ?>
	<div class="timeline-block day is-hidden">
		<section>
			<header data-color="<?php echo $this->array_color[$day % count( $this->array_color )]; ?>" style="background-color: #<?php echo $this->array_color[$day % count( $this->array_color )]; ?>;">
				<!--<span class="timeline-pointer"></span>-->
				<svg class="timeline-pointer" height="20" width="10">
					<polygon points="0,0 10,10 0,20" style="fill: #<?php echo $this->array_color[$day % count( $this->array_color )]; ?>"/>
				</svg>
				<ul>
					<li class="avatar"><?php echo get_avatar( $user_id, 32 ); ?></li>
					<li class="user-mail"><?php echo get_userdata( $user_id )->user_email; ?></li>
					<li><?php echo apply_filters( 'tm_filter_timeline_day', '', $user_id, $year, $month, $day ); ?></li>
					<li class="date"><?php echo mysql2date( 'l d/m/Y', $year . '-' . $month . '-' . $day ); ?></li>
				</ul>
			</header>

			<div class="timeline-block-content">

				<ul>
					<?php if ( !empty( $list_message ) ): ?>
						<?php foreach( $list_message as $time => $object ): ?>
							<li class="point">
								<div class="point-hour"><strong><?php echo  $time; ?></strong></div>
								<ul class="point-list">
									<?php
									if ( !empty( $object ) ):
										foreach ( $object as $type => $sub_object ):
											foreach( $sub_object as $the_object ):
												require( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'day', $type ) );
											endforeach;
										endforeach;
									endif;
									?>
								</ul>
							</li> <!-- .point -->
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>

			</div> <!-- timeline-block-content -->
		</section>
	</div> <!-- timeline-block -->
<?php endif; ?>
