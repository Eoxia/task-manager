<?php
/**
 * Affichage des charts des utilisateurs selon un lapse de temps préfédini
 *
 * @author Corentin-Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.9.0 - BETA
 * @copyright 2015-2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form>


	<div class="wpeo-form">
		<div class="wpeo-grid grid-5">
			<div class="form-element grid-1">
				<span class="form-label"><i class="fas fa-calendar"></i> <?php esc_html_e( 'Start date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input id="tm_indicator_date_start_id" type="date" class="form-field" placeholder="Date de début" value="<?php echo esc_attr( $date_start ); ?>" name="tm_indicator_date_start" />
				</label>
			</div>

			<div class="form-element grid-1">
				<span class="form-label"><i class="fas fa-calendar"></i> <?php esc_html_e( 'End date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input id="tm_indicator_date_end_id" type="date" class="form-field" value="<?php echo esc_attr( $date_end ); ?>" name="tm_indicator_date_end" />
				</label>
			</div>
			<div class="form-element grid-1" style='margin-top : 41px'>
				<button class="wpeo-button button-radius-3 action-input"
					data-time=''
					data-parent="wpeo-form"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'validate_indicator' ) ); ?>"
					data-action="validate_indicator">

					<?php esc_html_e( 'Validate', 'task-manager' ); ?>
				</button> </div>
			<div class="form-element grid-2">
				<?php

				\eoxia\View_Util::exec(
					'task-manager',
					'indicator',
					'backend-indicator/indicator-follower-admin',
					array(
						'followers' => $followers,
					)
				);

				?>
			</div>
</div>
		<div>
			<br>
			<button class="wpeo-button button-radius-3 action-input"
				data-time='day'
				data-parent="wpeo-form"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'validate_indicator' ) ); ?>"
				data-action="validate_indicator"
				style="background : #7ACCF3; border-color : #7ACCF3">

				<?php esc_html_e( 'Day', 'task-manager' ); ?>
			</button>
			<button class="wpeo-button button-radius-3 action-input"
				data-time='week'
				data-parent="wpeo-form"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'validate_indicator' ) ); ?>"
				data-action="validate_indicator"
				style="background : #46C8F3; border-color : #46C8F3">

				<?php esc_html_e( 'Week', 'task-manager' ); ?>
			</button>
			<button class="wpeo-button button-radius-3 action-input"
				data-time='month'
				data-parent="wpeo-form"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'validate_indicator' ) ); ?>"
				data-action="validate_indicator"
				style="background : #17A3D2; border-color : #17A3D2">


				<?php esc_html_e( 'Month', 'task-manager' ); ?>
			</button>
			<input type="hidden" name="list_follower" id="tm_indicator_list_followers" value="">

			<?php
			\eoxia\View_Util::exec(
				'task-manager',
				'indicator',
				'backend-indicator/indicator-button-display',
				array()
			); ?>
		</div>
	</div>
</form>
<br>


<div id='displaycanvas' style='display : none'>

</div>
<div id='displaycanvas_specific_week' style='display : none'>

</div>
<div id='displaycanvas_modal' style='display : none'>
	<div class="parent-container">

		<div class="wpeo-modal" id='tm_indicator_modal_active_canvas'>
			<div class="modal-container" data-update='false'>

				<!-- Entête -->
				<div class="modal-header">
					<h2 class="modal-title"><?php esc_html_e( 'Liste des points', 'task-manager' ); ?> : <span id='tm_indicator_day_taches'></span></h2>
				</div>

				<!-- Corps -->
				<div class="modal-content">
					<div id="display_modal">
					</div>
				</div>

				<!-- Footer -->
				<div class="modal-footer">
					<a class="wpeo-button button-grey button-uppercase modal-close"><span>Close</span></a>
				</div>
			</div>
		</div>
	</div>
</div>



<div id='information_canvas' style='display : none'></div>
<div id='tm_redirect_settings_user' style='display : none'>
	<a target="_blank" href="<?php echo esc_attr( admin_url( 'profile.php' ) ); ?>">
		<?php esc_html_e( 'Change your settings here', 'task-manager' ); ?>
	</a>
</div>
