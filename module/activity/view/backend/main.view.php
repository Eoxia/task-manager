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
	<div class="filter-activity">
		<label>
			<i class="fa fa-calendar"></i><?php esc_html_e( 'Start date', 'task-manager' ); ?>
			<input type="date" placeholder="<?php esc_html_e( 'Start date', 'task-manager' ); ?>" value="<?php echo esc_attr( $date_start ); ?>" name="tm_abu_date_start" />
		</label>
		<label>
			<i class="fa fa-calendar"></i><?php esc_html_e( 'End date', 'task-manager' ); ?>
			<input type="date" placeholder="<?php esc_html_e( 'End date', 'task-manager' ); ?>" value="<?php echo esc_attr( $date_end ); ?>" name="tm_abu_date_end" />
		</label>
		<button class="button-primary action-input" data-parent="filter-activity" id="tm-user-activity-load-by-date" ><?php esc_html_e( 'View activity', 'task-manager' ); ?></button>
	</div>

	<div class="content">
		<div class="tm-wrap" >
			<div class="activities" ><?php echo $history; //WPCS: XSS ok. ?></div>
		</div>
	</div>
</div>
