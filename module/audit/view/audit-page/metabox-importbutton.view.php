<?php
/**
 * Les bouttons pour importer ou créer une tache dans une audit
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm-import-task-to-audit-container">
	<!-- Bouton d'ouverture de la modal pour l'import de tâches -->
	<span href="#" class="wpeo-button button-main button-size-small wpeo-modal-event"
		data-target="tm-audit-import"
		data-parent="tm-import-task-to-audit-container">

		<i class="button-icon fas fa-download" ></i><?php /*<span><?php esc_html_e( 'Import', 'task-manager' ); ?></span>*/ ?></span>

	<!-- Structure de la modal pour l'import de tâches -->
	<div class="wpeo-modal tm-audit-import">
		<div class="modal-container">
			<div class="modal-header">
				<h2 class="modal-title-header" style="text-transform: uppercase; font-size: 18px; white-space: normal;"><?php echo esc_attr( 'Import audit\'s task', 'task-manager' ); ?></h2>
				<div class="modal-close"><i class="fas fa-times"></i></div>
			</div>

			<div class="modal-content">
				<div class="tm-import-add-keyword" style="display : flex">
					<div class="wpeo-button button-blue" data-type="task" style="margin-right: 8px;">
						<i class="button-icon fas fa-plus-circle"></i>
						<span><?php esc_html_e( 'Task', 'task-manager' ); ?></span>
					</div>
					<div class="wpeo-button button-blue" data-type="point" style="margin-right: 8px;">
						<i class="button-icon fas fa-plus-circle"></i>
						<span><?php esc_html_e( 'Point', 'task-manager' ); ?></span>
					</div>
					<?php /*<div class="wpeo-button button-blue" data-type="comment" >
						<i class="button-icon fas fa-plus-circle"></i>
						<span><?php esc_html_e( 'Comment', 'task-manager' ); ?></span>
					</div>*/ ?>
					<div class="wpeo-button button-blue" data-type="category" style="margin-right: 8px;">
						<i class="button-icon fas fa-plus-circle"></i>
						<span><?php esc_html_e( 'Categorie', 'task-manager' ); ?></span>
					</div>
					<div>
						<?php
						\eoxia\View_Util::exec(
							'task-manager',
							'import',
							'backend/import-tag-button',
							array(
								'tags' => $tags
							)
						);
						?>
					</div>
				</div>
				<textarea name="content" style="height : 80%; width : 100%; margin-top : 10px"></textarea>
			</div>

			<div class="modal-footer">
				<div class="wpeo-button button-grey button-uppercase modal-close"><span><?php esc_html_e( 'Cancel', 'task-manager' ); ?></span></div>
				<a class="wpeo-button button-main button-uppercase action-input"
					data-parent-id="<?php echo esc_attr( $audit_id ); ?>"
					data-parent="tm-import-task-to-audit-container"
					data-action="audit_import_tasks_and_points"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'audit_import_tasks_and_points' ) ); ?>" ><span><?php esc_html_e( 'Import', 'task-manager' ); ?></span></a>
			</div>
		</div>
	</div>
</div>
