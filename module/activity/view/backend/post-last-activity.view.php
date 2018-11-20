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

<div class="wpeo-dropdown activities-filter">
    <div class="dropdown-toggle wpeo-button button-main button-size-small button-square-30 button-rounded">
        <i class="button-icon fas fa-search"></i>
    </div>

    <div class="dropdown-content">
		<div class="filter-activity wpeo-form">

			<div class="form-element">
				<span class="form-label"><i class="fas fa-calendar"></i> <?php esc_html_e( 'Start date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="date" class="form-field" placeholder="Date de début" value="<?php echo esc_attr( $date_start ); ?>" name="tm_abu_date_start" />
				</label>
			</div>

			<div class="form-element">
				<span class="form-label"><i class="fas fa-calendar"></i> <?php esc_html_e( 'End date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="date" class="form-field" value="<?php echo esc_attr( $date_end ); ?>" name="tm_abu_date_end" />
					<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>" name="_wpnonce" />
				</label>
			</div>

			<?php echo apply_filters( 'tm_activity_filter_input', '' ); ?>
			<input type="hidden" name="tasks_id" value="<?php echo $tasks_id; ?>" />
			<button class="button-primary action-input" data-parent="filter-activity" data-action="load_last_activity" id="tm-user-activity-load-by-date" ><?php esc_html_e( 'View activity', 'task-manager' ); ?></button>
		</div>
	</div>
</div>

<!-- Liste des tâches effectuées -->
<div class="daily-activity activities">
	<div class="content">
		<?php if ( ! empty( $datas ) ) :
			$last_date = null; ?>
			<?php foreach ( $datas as $activity ) : ?>
				<?php if ( mysql2date( 'd/m/Y', $activity->COM_DATE ) != $last_date ) : ?>
					<div class="day">
						<span class="label"><?php echo esc_html( ucfirst( mysql2date( 'l', $activity->COM_DATE ) ) . ' ' . mysql2date( 'd/m/Y', $activity->COM_DATE ) ); ?></span>
					</div>
				<?php endif; 
				
				$last_date = mysql2date( 'd/m/Y', $activity->COM_DATE ); ?>
				
				<div class="activity">
					<div class="content">
						<div class="event-header">
							<!-- Utilisateur affecté -->
							<?php echo do_shortcode( '[task_avatar ids="' . $activity->COM_author_id . '" size="30"]' ); ?>
							<!-- Heure de l'action -->
							<span class="time-posted"><i class="fas fa-calendar"></i> <?php echo esc_html( mysql2date( 'H\hi', $activity->COM_DATE, true ) ); ?></span>
							<!-- Client -->
							<span class="event-client">
								<i class="fas fa-user"></i>
								<?php if ( ! empty( $activity->PT_ID ) ) : ?>
								<a href="<?php echo esc_url( admin_url( 'post.php?action=edit&post=' . $activity->PT_ID ) ); ?>" target="wptm_view_activity_element" >
									<?php echo esc_html( '#' . $activity->PT_ID . ' ' . $activity->PT_title ); ?>
								</a>
							<?php else : ?>
								<?php echo esc_html( '-' ); ?>
							<?php endif; ?>
							</span>
							<!-- Tâche -->
							<span class="event-task wpeo-tooltip-event" aria-label="<?php echo esc_html( '#' . $activity->T_ID . ' ' . $activity->T_title ); ?>">
								<i class="fas fa-th-large"></i> <?php echo esc_html( '#' . $activity->T_ID ); ?>
							</span>
							<!-- Point -->
							<span class="event-point wpeo-tooltip-event" aria-label="<?php echo esc_html( '#' . $activity->POINT_ID . ' ' . $activity->POINT_title ); ?>">
								<i class="fas fa-list-ul"></i> <?php echo esc_html( '#' . $activity->POINT_ID ); ?>
							</span>
							<!-- Temps passé -->
							<?php
							$com_details = ( ! empty( $activity->COM_DETAILS ) ? json_decode( $activity->COM_DETAILS ) : '' );
							// $total_time += $com_details->time_info->elapsed;
							?>
							<span class="event-time"><i class="far fa-clock"></i> <?php echo ! empty( $com_details->time_info->elapsed ) ? esc_html( $com_details->time_info->elapsed ) : 0; ?></span>
						</div>

						<span class="event-content">
							<?php
							$link = 'admin.php?page=wpeomtm-dashboard&term=' . $activity->T_ID . '&point_id=' . $activity->POINT_ID . '&comment_id=' . $activity->COM_ID;
							if ( ! empty( $activity->PT_ID ) ) :
								$link = 'post.php?post=' . $activity->PT_ID . '&term=' . $activity->T_ID . '&action=edit&point_id=' . $activity->POINT_ID . '&comment_id=' . $activity->COM_ID;
							endif;
							?>
							<a target="wptm_view_activity_element" href="<?php echo esc_url( admin_url( $link ) ); ?>" ><?php echo $activity->COM_title; // WPCS : XSS ok. ?></a>
						</span>
					</div>
				</div>

			<?php endforeach; ?>
		<?php else : ?>
			<?php esc_html_e( 'No activity found for now', 'task-manager' ); ?>
		<?php endif; ?>
	</div><!-- .content -->
</div>
