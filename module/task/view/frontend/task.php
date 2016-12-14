<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( !empty( $task ) ): ?>
	<div data-id="<?php echo $task->id; ?>" class="task">
		<header>
			<h2><?php echo '#' . $task->id . ' ' . $task->title; ?></h2>	
			<!-- Temps passé, temps estimé / Elapsed time, estimated time -->
			<span class="dashicons dashicons-clock"></span>
			<?php /*_e( 'Elapsed time', 'task-manager' );*/ ?><strong><?php echo $task->option['time_info']['elapsed']; ?></strong> / 
			<?php/* _e( 'Estimated time', 'task-manager' );*/ ?><strong><?php echo $task->option['time_info']['estimated']; ?></strong> min
		</header>
		
		<!-- On affiches tous les points avec leurs commentaires / Display all points with their comments -->
		<?php if ( !empty( $task->point ) ):
			$number_point_completed = 0;
			?>
			<ul class="wpeo-task-point">
			<?php 
			foreach( $task->point as $point ):
				if ( !$point->option['point_info']['completed'] ):
				?>
					<li>
						<div>
							<a href="#" data-id="<?php echo $point->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>">
								<?php echo '<strong>#' . $point->id . '</strong> ' . $point->content; ?>
							</a>	
						</div>
						
						<!-- Temps passé / Elapsed time -->
						<div>
							<?php /*_e( 'Elapsed time', 'task-manager' );*/ ?> <span class="dashicons dashicons-clock"></span><strong><?php echo $point->option['time_info']['elapsed']; ?></strong>
						</div>
					
					</li><?php
				else:
					$number_point_completed++;
				endif; 
			endforeach;
			?>
			<li class="wpeo-point-title"><span class="dashicons wpeo-point-toggle-arrow dashicons-plus"></span> <strong><?php _e( 'Completed point', 'task-manager' ); ?></strong> <?php echo '(' . $number_point_completed . ')'; ?></li>
			<div class="wpeo-no-display completed-point">
			<?php 
			foreach( $task->point as $point ):
				if ( $point->option['point_info']['completed'] ):
				?>
					<li>
						<div>
							<a href="#" data-id="<?php echo $point->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>">
								<?php echo '<strong>#' . $point->id . '</strong> ' . $point->content; ?>
							</a>	
						</div>
				
						<!-- Temps passé / Elapsed time -->
						<div>
							<?php /*_e( 'Elapsed time', 'task-manager' );*/ ?> <span class="dashicons dashicons-clock"></span><strong><?php echo $point->option['time_info']['elapsed']; ?></strong>
						</div>
					</li><?php 
				endif;
			endforeach;
			?></div></ul><?php 
		endif; ?>
	</div>
<?php endif; ?>