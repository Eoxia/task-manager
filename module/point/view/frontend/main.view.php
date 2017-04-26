<?php
/**
 * La vue principale des points dans une tâche dans le frontend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul class="wpeo-task-point">
<!-- On affiches tous les points avec leurs commentaires / Display all points with their comments -->
<?php if ( ! empty( $points_uncompleted ) ) :

	foreach ( $points_uncompleted as $point ) :
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
						<i class="dashicons dashicons-clock"></i><strong><?php echo $point->time_info['elapsed']; ?></strong>
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
		<?php echo '(' . count( $points_completed ) . '/' . ( count( $points_uncompleted ) + count( $points_completed ) ) . ')'; ?>
	</p>
</li>

<div class="hidden completed-point">
<?php
foreach ( $points_completed as $point ) :
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
				<i class="dashicons dashicons-clock"></i><strong><?php echo $point->time_info['elapsed']; ?></strong>
			</span>
		</div>

	</li>
	<?php
endforeach;
?></div></ul>
