<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( !empty( $task ) ): ?>
	<div data-id="<?php echo $task->id; ?>" class="task">
		<header>
			<h2><?php echo '#' . $task->id . ' ' . $task->title; ?></h2>
			<!-- Temps passé, temps estimé / Elapsed time, estimated time -->
			<span class="dashicons dashicons-clock"></span>
			<?php /*esc_html_e( 'Elapsed time', 'task-manager' );*/ ?><strong><?php echo $task->option['time_info']['elapsed']; ?></strong> /
			<?php/* esc_html_e( 'Estimated time', 'task-manager' );*/ ?><strong><?php echo $task->option['time_info']['estimated']; ?></strong> min
		</header>

		<!-- On affiches tous les points avec leurs commentaires / Display all points with their comments -->
		<?php if ( ! empty( $task->point_uncompleted ) ) :
			?>
			<ul class="wpeo-task-point">
			<?php
			foreach ( $task->point_uncompleted as $point ) :
				?>
					<li>
						<div>
							<span class="wpeo-point-input" data-id="<?php echo $point->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>">
								<?php echo '<strong>#' . $point->id . '</strong> ' . $point->content; ?>
							</span>
						</div>

						<!-- Temps passé / Elapsed time -->
						<div>
							<?php /*esc_html_e( 'Elapsed time', 'task-manager' );*/ ?> <span class="dashicons dashicons-clock"></span><strong><?php echo $point->option['time_info']['elapsed']; ?></strong>
						</div>

					</li><?php
			endforeach;
			?>

			<li class="wpeo-point-title wpeo-task-point-use-toggle">
				<span class="dashicons wpeo-point-toggle-arrow dashicons-plus"></span>
				<strong><?php esc_html_e( 'Completed point', 'task-manager' ); ?></strong>
				<?php echo '(' . count( $task->point_completed ) . '/' . count( $task->point_uncompleted ) . ')'; ?>
			</li>

			<div class="hidden completed-point">
			<?php
			foreach ( $task->point_completed as $point ):
				?>
				<li>
					<div>
						<span data-id="<?php echo $point->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>">
							<?php echo '<strong>#' . $point->id . '</strong> ' . $point->content; ?>
						</span>
					</div>

					<!-- Temps passé / Elapsed time -->
					<div>
						<?php /*esc_html_e( 'Elapsed time', 'task-manager' );*/ ?> <span class="dashicons dashicons-clock"></span><strong><?php echo $point->option['time_info']['elapsed']; ?></strong>
					</div>
				</li><?php
			endforeach;
			?></div></ul><?php
		endif; ?>
	</div>
<?php endif; ?>
