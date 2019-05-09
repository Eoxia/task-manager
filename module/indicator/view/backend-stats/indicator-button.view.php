<?php
/**
 * Button -> Affichage tache Deadline/ Récursive
 *
 * @author Corentin-Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.10.0 - BETA
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>



	<?php if( isset( $element ) && $element == "deadline" ): ?>

		<span id="tm-indicator-stats-client-displaybutton" class="alignright"
			data-date="<?php echo esc_attr( $date[ 'value' ] ); ?>"
			data-focus="deadline">

		<div class="wpeo-button button-grey button-radius-3"
		data-element="recursive"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats' ) ); ?>"
		data-action="update_indicator_stats">
			<i class="button-icon fas fa-square"></i>
			<span><?php esc_html_e( 'Recursives tasks', 'task-manager' ); ?></span>
		</div>

		<div class="wpeo-button button-blue active button-radius-3"
		data-element="deadline"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats_deadline' ) ); ?>"
		data-action="update_indicator_stats_deadline">
			<i class="button-icon fas fa-check-square"></i>
			<span><?php esc_html_e( 'Deadlines tasks', 'task-manager' ); ?></span>
		</div>

	<?php else: ?>
		<span id="tm-indicator-stats-client-displaybutton" class="alignright"
			data-date="<?php echo esc_attr( $date[ 'value' ] ); ?>"
			data-focus="recursive">

		<div class="wpeo-button button-blue active button-radius-3"
		data-element="recursive"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats' ) ); ?>"
		data-action="update_indicator_stats">
			<i class="button-icon fas fa-check-square"></i>
			<span><?php esc_html_e( 'Recursives tasks', 'task-manager' ); ?></span>
		</div>

		<div class="wpeo-button button-grey button-radius-3"
		data-element="deadline"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats_deadline' ) ); ?>"
		data-action="update_indicator_stats_deadline">
			<i class="button-icon fas fa-square"></i>
			<span><?php esc_html_e( 'Deadlines tasks', 'task-manager' ); ?></span>
		</div>
	<?php endif; ?>


</span>
