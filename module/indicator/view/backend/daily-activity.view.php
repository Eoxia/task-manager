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
	<i class="fas fa-clock"></i> {{ total_time }}
</div>

<!-- Filtre de temps pour les activités -->
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
					<input type="hidden" value="" />
				</label>
			</div>
			<?php echo apply_filters( 'tm_filter_activity', '', $user_id, $customer_id, $page ); // WPCS: XSS ok. ?>

			<button class="button-primary action-input" data-parent="filter-activity" data-action="open_popup_user_activity" id="tm-user-activity-load-by-date" ><?php esc_html_e( 'View activity', 'task-manager' ); ?></button>
		</div>
	</div>
</div>

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
							$total_time += $com_details->time_info->elapsed;
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

<?php
$output = ob_get_clean();

echo wp_kses(
	str_replace( '{{ total_time }}', \eoxia\Date_Util::g()->convert_to_custom_hours( $total_time ), $output ),
	array(
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
			'data-action' => array(),
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
			'class'      => array(),
			'aria-label' => array(),
		),
		'img'    => array(
			'class' => array(),
			'src'   => array(),
		),
		'select' => array(
			'name'             => array(),
			'id'               => array(),
			'data-placeholder' => array(),
			'class'            => array(),
		),
		'option' => array(
			'value'    => array(),
			'selected' => array(),
		),
		'h2'     => array(
			'style' => array(),
		),
	)
);
