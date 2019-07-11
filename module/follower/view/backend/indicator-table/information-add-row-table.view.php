<?php
/**
 * Options de la ligne d'ajout dans le tableau des indicators dans le profil utilisateur.
 *
 * @since 1.10.0
 * @version 1.10.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm-information-planning" style="color: blue; float : left">
		<span class="tm-default-information tm-focus-element">
	    <?php esc_html_e( 'Move your mouse over an element for more information', 'task-manager' ); ?>
	  </span>

    <span class="tm-planning-custom-name" style="display : none">
			<?php esc_html_e( 'Custom name to name your line (example: My Best Monday Morning)', 'task-manager' ); ?>
    </span>

		<span class="tm-planning-dropdown-day" style="display : none">
			<?php esc_html_e( 'Choose a day of the week (example: Monday)', 'task-manager' ); ?>
    </span>

		<span class="tm-planning-period" style="display : none">
			<?php esc_html_e( 'Choose period (example: Morning)', 'task-manager' ); ?>
		</span>

		<span class="tm-planning-work-from" style="display : none">
			<?php esc_html_e( 'Start time for period (example: 9:00)', 'task-manager' ); ?>
    </span>

		<span class="tm-planning-work-to" style="display : none">
			<?php esc_html_e( 'End time for period (example: 12:00)', 'task-manager' ); ?>
    </span>

		<span class="tm-planning-day-start" style="display : none">
			<?php esc_html_e( 'Start date of the contract (example: 12/10/1998)', 'task-manager' ); ?>
    </span>

		<span class="tm-planning-action-add" style="display : none">
			<?php esc_html_e( 'Add this to your planning !', 'task-manager' ); ?>
    </span>

		<span class="tm-planning-action-delete" style="display : none; color : red">
			<?php esc_html_e( 'Delete this from your planning !', 'task-manager' ); ?>
    </span>
</div>
<div style="float: right; margin-top: 2px; display : inline-flex">
	<div class="tm-modal-archive"
		data-action="display_archive_user"
		data-wpnonce="<?php echo esc_attr( wp_create_nonce( 'display_archive_user' ) ); ?>"
		style="margin-right: 10px;">
		<div class="wpeo-button button-yellow button-square-40 button-rounded"><i class="fas fa-archive"></i></div>

		<div class="tm-information-modal-view"></div>
	</div>
	<div class="tm-expand-table" data-expand="true">
		<div class="wpeo-button button-square-40 button-rounded" style="background-color : #17a2b8; border-color : #17a2b8">
			<i class="fas fa-expand"></i>
		</div>
	</div>
</div>
<br />
<span class="tm-information-status-request" style="color : green; font-size : 20px">
	<div class="wpeo-notice notice-success" style="display : none; padding: 0.2em;">
		<div class="notice-content">
			<div class="notice-title" style="margin : 0; font-size: 30px;"></div>
			<div class="notice-close" style="float: right; margin-top: -25px;"><i class="fas fa-times"></i></div>
		</div>
	</div>
</span>
<div class="tm-information-run-for-another-day">

</div>

<!--
















 -->
