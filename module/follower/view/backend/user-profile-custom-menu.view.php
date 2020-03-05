<?php
/**
 * Vue Profil dans le menu Utilisateur.
 * Affichage de la vue profil en fonction de chaque Utilisateur.
 *
 * @package   TaskManager
 * @author    Nicolas Domenech <nicolas@eoxia.com>
 * @copyright 2015-2020 Eoxia
 * @since     3.0.1
 * @version   3.0.1
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string $user                    Les données d'un utilisateur.
 */
?>

<h2><?php esc_html_e( 'Profile Settings', 'task-manager' ); ?></h2>

<div class="wpeo-form">
	<input type="hidden" name="action" value="save_profil_task_manager" />
	<?php wp_nonce_field( 'save_profil_task_manager' ); ?>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Task Per Page', 'task-manager' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="_tm_task_per_page" id="_tm_task_per_page" value="<?php echo isset( $user->data['_tm_task_per_page'] ) ? $user->data['_tm_task_per_page'] : 10; ?>">
		</label>
		<span class="form-sublabel"><?php esc_html_e( 'Set the number of task loaded by time', 'task-manager' ); ?></span>
	</div>

	<div class="form-element" style="width: 20%">
		<label class="form-field-container">
			<input type="checkbox" class="form-field" name="_tm_project_state" id="_tm_project_state" value="1" <?php checked( $user->data['_tm_project_state'], true, true ); ?>">
			<label for="_tm_project_state"><?php esc_html_e( 'Display Project and Task', 'task-manager' ); ?></label>
		</label>
		<span class="form-sublabel"><?php esc_html_e( 'Display project and task for quickly access at task in project', 'task-manager' ); ?></span>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" class="form-field" name="_tm_auto_elapsed_time" id="_tm_auto_elapsed_time" value="1" <?php checked( $user->data['_tm_auto_elapsed_time'], true, true ); ?>">
			<label for="_tm_auto_elapsed_time"><?php esc_html_e( 'Compil time automatically', 'task-manager' ); ?></label>
		</label>
		<span class="form-sublabel"><?php esc_html_e( 'Get the time of last comment you enter and fill elapsed time from this time. (You don\'t need to make hard calcul to get your elapsed time ;) ', 'task-manager' ); ?></span>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" class="form-field" name="_tm_advanced_display" id="_tm_advanced_display" value="1" <?php checked( $user->data['_tm_advanced_display'], true, true ); ?>">
			<label for="_tm_advanced_display"><?php esc_html_e( 'Advanced display', 'task-manager' ); ?></label>
		</label>
		<span class="form-sublabel"><?php esc_html_e( 'Display advanced: time, task informations, task link)', 'task-manager' ); ?></span>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" class="form-field" name="_tm_quick_point" id="_tm_quick_point" value="1" <?php checked( $user->data['_tm_quick_point'], true, true ); ?>">
			<label for="_tm_quick_point"><?php esc_html_e( 'Quick point', 'task-manager' ); ?></label>
		</label>
		<span class="form-sublabel"><?php esc_html_e( 'Display quick point button', 'task-manager' ); ?></span>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<input type="checkbox" class="form-field" name="_tm_display_indicator" id="_tm_display_indicator" value="1" <?php checked( $user->data['_tm_display_indicator'], true, true ); ?>">
			<label for="_tm_display_indicator"><?php esc_html_e( 'Display indicator', 'task-manager' ); ?></label>
		</label>
		<span class="form-sublabel"><?php esc_html_e( 'Display indicator box', 'task-manager' ); ?></span>
	</div>

	<div class="wpeo-button button-green button-progress action-input button-valid" data-parent="wpeo-form">
		<span class="button-icon fas fa-save"></span>
	</div>
</div>
