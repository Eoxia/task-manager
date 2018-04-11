<?php
/**
 * Affichage de l'activité d'un utilisateur pour la journée courante
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
}

$total_time = 0;

ob_start();
?>
<!-- Temps total travaillé -->
<div class="total-time wpeo-tooltip-event" aria-label="<?php echo esc_attr( mysql2date( 'd M Y H:i', current_time( 'mysql' ), true ) ); ?>" >
	<i class="dashicons dashicons-clock"></i> {{ total_time }}
</div>

<!-- Filtre de temps pour les activités -->
<div class="filter-activity">
	<label>
		<i class="fa fa-calendar"></i><?php esc_html_e( 'Start date', 'task-manager' ); ?>
		<input type="date" placeholder="Date de début" value="<?php echo esc_attr( $date_start ); ?>" name="tm_abu_date_start" />
	</label>
	<label>
		<i class="fa fa-calendar"></i><?php esc_html_e( 'End date', 'task-manager' ); ?>
		<input type="date" value="<?php echo esc_attr( $date_end ); ?>" name="tm_abu_date_end" />
		<input type="hidden" value="open_popup_user_activity" name="action" />
		<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'load_user_activity' ) ); ?>" name="_wpnonce" />
	</label>
	<button class="button-primary action-input" data-parent="filter-activity" id="tm-user-activity-load-by-date" ><?php esc_html_e( 'View activity', 'task-manager' ); ?></button>
</div>

<!-- Liste des tâches effectuées -->
<div class="daily-activity activities">
	<div class="content">
		<?php if ( ! empty( $datas ) ) : ?>
			<?php foreach ( $datas as $activity ) : ?>

				<div class="activity">
					<div class="information">
						<?php echo do_shortcode( '[task_avatar ids="1" size="30"]' ); ?>
						<span class="time-posted"><?php echo esc_html( mysql2date( 'H\hi', $activity->COM_DATE, true ) ); ?></span>
					</div>

					<div class="content">
						<div class="event-header">
							<!-- Client -->
								<span class="event-client">
									<i class="fa fa-user"></i>
									<?php if ( ! empty( $activity->PT_ID ) ) : ?>
									<a href="<?php echo esc_url( admin_url( 'post.php?action=edit&post=' . $activity->PT_ID ) ); ?>" target="wptm_view_activity_element" >
										<?php echo esc_html( '#' . $activity->PT_ID . ' ' . $activity->PT_title ); ?>
									</a>
								<?php else : ?>
									<?php echo esc_html( '-' ); ?>
								<?php endif; ?>
								</span>
							<!-- Tâche -->
							<span class="event-task">
								<i class="dashicons dashicons-layout"></i> <?php echo esc_html( '#' . $activity->T_ID . ' ' . $activity->T_title ); ?>
							</span>
							<!-- Point -->
							<span class="event-point">
								<i class="fa fa-list-ul"></i> <?php echo esc_html( '#' . $activity->POINT_ID . ' ' . $activity->POINT_title ); ?>
							</span>
							<!-- Temps passé -->
							<?php
							$com_details = ( ! empty( $activity->COM_DETAILS ) ? json_decode( $activity->COM_DETAILS ) : '' );
							$total_time += $com_details->time_info->elapsed;
							?>
							<span class="event-time"><i class="dashicons dashicons-clock"></i><?php echo ! empty( $com_details->time_info->elapsed ) ? esc_html( $com_details->time_info->elapsed ) : 0; ?></span>
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

<?php
$output = ob_get_clean();

echo wp_kses( str_replace( '{{ total_time }}', \eoxia\Date_Util::g()->convert_to_custom_hours( $total_time ), $output ), array(
	'table'  => array(
		'style' => array(),
		'class' => array(),
	),
	'tr'     => array(
		'class' => array(),
	),
	'td'     => array(
		'style'   => array(),
		'class'   => array(),
		'colspan' => array(),
	),
	'input'  => array(
		'type'  => array(),
		'value' => array(),
		'name'  => array(),
	),
	'button' => array(
		'class'       => array(),
		'data-parent' => array(),
	),
	'a'      => array(
		'href'   => array(),
		'target' => array(),
		'style'  => array(),
	),
	'br'     => array(),
	'div'    => array(
		'class'      => array(),
		'id'         => array(),
		'style'      => array(),
		'aria-label' => array(),
	),
	'i'      => array(
		'class' => array(),
	),
	'label'  => array(
		'class' => array(),
	),
	'span'   => array(
		'class' => array(),
	),
	'img'    => array(
		'class' => array(),
		'src'   => array(),
	),
) );
