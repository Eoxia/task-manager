<?php
/**
 * Affichage des points en mode 'grille'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


		<div class="filter-activity wpeo-form wpeo-grid grid-5" style="margin-left : 0; margin-right : 0">
			<div class="form-element grid-2">
				<span class="form-label"><i class="fas fa-calendar"></i> <?php esc_html_e( 'Start date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="date" class="form-field" placeholder="Date de début" value="<?php echo esc_attr( $date_start ); ?>" name="tm_abu_date_start" required />
				</label>
			</div>

			<div class="form-element grid-2">
				<span class="form-label"><i class="fas fa-calendar"></i> <?php esc_html_e( 'End date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="date" class="form-field" value="<?php echo esc_attr( $date_end ); ?>" name="tm_abu_date_end" required/>
					<input type="hidden" value="" />
				</label>
			</div>

			<div class="form-element grid-1">
				<?php echo apply_filters( 'tm_activity_filter_input', '' ); ?>
				<input type="hidden" name="tasks_id" value="<?php echo $tasks_id; ?>" />
				<span class="form-label" style="visibility : hidden">.</span>
				<div class="wpeo-button action-input" data-parent="filter-activity" data-action="load_last_activity" id="tm-user-activity-load-by-date" >
					<i class="fas fa-filter"></i>
				</div>
			</div>
		</div>


		<?php \eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/activity-post-indicator', array( 'task_id' => $tasks_id ) ); ?>

<!-- Liste des tâches effectuées -->
<div class="daily-activity activities">
	<div class="content">
		<?php
		if ( ! empty( $datas ) ) :
			$last_date = null;
			?>

			<?php foreach ( $datas as $activity ) : ?>
				<?php if ( mysql2date( 'd/m/Y', $activity->com_date ) != $last_date ) : ?>
					<div class="day">
						<span class="label"><?php echo esc_html( ucfirst( mysql2date( 'l', $activity->com_date ) ) . ' ' . mysql2date( 'd/m/Y', $activity->com_date ) ); ?></span>
					</div>
					<?php
				endif;

				$last_date = mysql2date( 'd/m/Y', $activity->com_date );
				?>

				<div class="activity">
					<div class="content">
						<div class="event-header">
							<!-- Utilisateur affecté -->
							<?php echo do_shortcode( '[task_avatar ids="' . $activity->com_author_id . '" size="30"]' ); ?>
							<!-- Heure de l'action -->
							<span class="time-posted"><i class="fas fa-calendar"></i> <?php echo esc_html( mysql2date( 'H\hi', $activity->com_date, true ) ); ?></span>
							<!-- Client -->
							<span class="event-client">
								<i class="fas fa-user"></i>
								<?php if ( ! empty( $activity->pt_id ) ) : ?>
								<a href="<?php echo esc_url( admin_url( 'post.php?action=edit&post=' . $activity->pt_id ) ); ?>" target="wptm_view_activity_element" >
									<?php echo esc_html( '#' . $activity->pt_id . ' ' . $activity->pt_title ); ?>
								</a>
							<?php else : ?>
								<?php echo esc_html( '-' ); ?>
							<?php endif; ?>
							</span>
							<!-- Tâche -->
							<span class="event-task wpeo-tooltip-event" aria-label="<?php echo esc_html( '#' . $activity->t_id . ' ' . $activity->t_title ); ?>">
								<i class="fas fa-th-large"></i> <?php echo esc_html( '#' . $activity->t_id ); ?>
							</span>
							<!-- Point -->
							<span class="event-point wpeo-tooltip-event" aria-label="<?php echo esc_html( '#' . $activity->point_id . ' ' . $activity->point_title ); ?>">
								<i class="fas fa-list-ul"></i> <?php echo esc_html( '#' . $activity->point_id ); ?>
							</span>
							<!-- Temps passé -->
							<?php
							$com_details = ( ! empty( $activity->com_details ) ? json_decode( $activity->com_details ) : '' );
							// $total_time += $com_details->time_info->elapsed;
							?>
							<span class="event-time"><i class="fas fa-clock"></i> <?php echo ! empty( $com_details->time_info->elapsed ) ? esc_html( $com_details->time_info->elapsed ) : 0; ?></span>
						</div>

						<span class="event-content">
							<?php
							$link = 'admin.php?page=wpeomtm-dashboard&term=' . $activity->t_id . '&point_id=' . $activity->point_id . '&comment_id=' . $activity->com_id;
							if ( ! empty( $activity->pt_id ) ) :
								$link = 'post.php?post=' . $activity->pt_id . '&term=' . $activity->t_id . '&action=edit&point_id=' . $activity->point_id . '&comment_id=' . $activity->com_id;
							endif;
							?>
							<a target="wptm_view_activity_element" href="<?php echo esc_url( admin_url( $link ) ); ?>" ><?php echo $activity->com_title; // WPCS : XSS ok. ?></a>
						</span>
					</div>
				</div>

			<?php endforeach; ?>
		<?php else : ?>
			<?php esc_html_e( 'No activity found for now', 'task-manager' ); ?>
		<?php endif; ?>
	</div><!-- .content -->
</div>
