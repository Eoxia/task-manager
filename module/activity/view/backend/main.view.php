<?php
/**
 * Affichage des points en mode 'grille'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="activities">
	<input type="hidden" class="offset-event" value="<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page ); ?>" />
	<input type="hidden" class="last-date" value="" />

	<!-- Filtre de temps pour les activités -->
	<div class="filter-activity wpeo-form form-light">
		<div class="filter-fields wpeo-gridlayout grid-5">
			<div class="form-element gridw-2">
				<span class="form-label"><i class="fas fa-calendar-alt fa-fw"></i> <?php esc_html_e( 'Start date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="date" class="form-field" placeholder="<?php esc_html_e( 'Start date', 'task-manager' ); ?>" value="<?php echo esc_attr( $date_start ); ?>" name="tm_abu_date_start" />
				</label>
			</div>

			<div class="form-element gridw-2">
				<span class="form-label"><i class="fas fa-calendar-alt fa-fw"></i> <?php esc_html_e( 'End date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="date" class="form-field" placeholder="<?php esc_html_e( 'End date', 'task-manager' ); ?>" value="<?php echo esc_attr( $date_end ); ?>" name="tm_abu_date_end" />
				</label>
			</div>

			<button class="wpeo-button button-main button-square-40 action-input"
				data-tasks-id="<?php echo esc_attr( $task_id ); ?>"
				data-action="load_last_activity"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>"
				data-parent="filter-activity"
				id="tm-user-activity-load-by-date" >

				<i class="fas fa-search"></i></button>
		</div><!-- .filter-fields -->
	</div><!-- .filter-activity -->

<div class="content">
	<div class="tm-wrap" >
		<div class="activities" ><?php echo $history; // WPCS: XSS ok. ?></div>
	</div>
</div>
