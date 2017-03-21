<?php if ( ! defined( 'ABSPATH' ) ) exit;


global $time_controller; ?>

<?php if ( !empty( $task ) ): ?>
	<div data-id="<?php echo $task->id; ?>" class="task">
		<header>
			<h2><?php echo '#' . $task->id . ' ' . $task->title; ?></h2>
			<!-- Temps passé, temps estimé / Elapsed time, estimated time -->
			<div class="task-time">
				<span class="dashicons dashicons-clock"></span>
				<?php /*esc_html_e( 'Elapsed time', 'task-manager' );*/ ?><strong><?php echo $task->option['time_info']['elapsed']; ?></strong> /
				<?php/* esc_html_e( 'Estimated time', 'task-manager' );*/ ?><strong><?php echo $task->option['time_info']['estimated']; ?></strong> min
			</div>
		</header>

		<ul class="wpeo-task-point">
		<!-- On affiches tous les points avec leurs commentaires / Display all points with their comments -->
		<?php if ( ! empty( $task->point_uncompleted ) ) :

			foreach ( $task->point_uncompleted as $point ) :
				?>
					<li>
						<div class="point-content">
							<span class="wpeo-point-input" data-id="<?php echo $point->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>">
								<?php echo '<strong>#' . $point->id . '</strong> ' . $point->content; ?>
							</span>
						</div>

						<!-- Temps passé / Elapsed time -->
						<div class="point-info">
							<?php /*esc_html_e( 'Elapsed time', 'task-manager' );*/ ?>
							<span class="point-time">
								<i class="dashicons dashicons-clock"></i><strong><?php echo $point->option['time_info']['elapsed']; ?></strong>
							</span>

							<?php $list_time = $time_controller->index( $point->post_id, array( 'parent' => $point->id, 'status' => -34070 ) ); ?>
							<span class="point-comment">
								<i class="dashicons dashicons-admin-comments"></i><?php echo count( $list_time ); ?>
							</span>
						</div>

					</li>
				<?php
			endforeach;
		endif; ?>

		<li class="wpeo-point-title wpeo-task-point-use-toggle">
			<p>
				<span class="dashicons wpeo-point-toggle-arrow dashicons-plus"></span>
				<strong><?php esc_html_e( 'Completed point', 'task-manager' ); ?></strong>
				<?php echo '(' . count( $task->point_completed ) . '/' . count( $task->point_uncompleted ) . ')'; ?>
			</p>
		</li>

		<div class="hidden completed-point">
		<?php
		foreach ( $task->point_completed as $point ):
			?>
			<li>
				<div class="point-content">
					<span data-id="<?php echo $point->id; ?>" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>">
						<?php echo '<strong>#' . $point->id . '</strong> ' . $point->content; ?>
					</span>
				</div>

				<!-- Temps passé / Elapsed time -->
				<div class="point-info">
					<?php /*esc_html_e( 'Elapsed time', 'task-manager' );*/ ?>
					<span class="point-time">
						<i class="dashicons dashicons-clock"></i><strong><?php echo $point->option['time_info']['elapsed']; ?></strong>
					</span>

					<?php $list_time = $time_controller->index( $point->post_id, array( 'parent' => $point->id, 'status' => -34070 ) ); ?>
					<span class="point-comment">
						<i class="dashicons dashicons-admin-comments"></i><?php echo count( $list_time ); ?>
					</span>
				</div>

			</li>
			<?php
		endforeach;
		?></div></ul>
	</div>
<?php endif; ?>
