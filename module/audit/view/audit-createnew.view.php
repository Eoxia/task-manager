<?php
/**
 * Vue pour la création d'un nouvel audit
 *
 * @author <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm-audit">
	<div class="audit-container">

		<div class="action-attribute audit-backlink" data-action="reset_main_page" data-parent-id="<?php echo esc_attr( $parent_id ); ?>">
			<i class="fas fa-angle-left"></i> <?= esc_html_e( 'Back to list audit', 'task-manager' ); ?>
		</div>

		<div class="audit-header">
			<div class="audit-edit-header">
				<div class="audit-title-container">
					<div id="tm_client_audit_title_new" class="audit-title" contenteditable="true" placeholder="<?php esc_html_e( 'Write audit title ...', 'task-manager' ); ?>"><?php echo empty( $audit->data[ 'title' ] ) ? null : $audit->data[ 'title' ];  ?></div>
					<span class="audit-title-edit"><i class="fas fa-pencil"></i></span>
				</div>

				<ul class="audit-summary">
					<li class="audit-summary-id"><i class="far fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
					<li class="audit-summary-date">
						<span class="summary-created wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Created date', 'task-manager' ); ?>">
							<i class="far fa-calendar-alt"></i> <?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?>
						</span> /
						<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Due date', 'task-manager' ); ?>">

							<div class="form-element group-date">
								<span class="form-label"><i class="far fa-calendar-alt"></i></span>
								<label class="form-field-container">
									<input id="tm_audit_client_date_deadline_new" type="hidden" class="mysql-date" name="date_deadline"  value="<?php echo esc_attr( $audit->data[ 'deadline' ]['raw'] ); ?>" />
									<input id="tm_audit_client_date_deadline" class="date form-field" type="text"
									value="<?php echo esc_attr( $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ); ?>" />
								</label>

							</div>


						</span>
					</li>
				</ul>
			</div>

			<div class="audit-save-container">
				<div class="wpeo-button button-green button-disable button-square-40 action-input"
					data-parent="audit-header"
					data-action="edit_title_audit"
					data-id="<?= $audit->data[ 'id' ] ?>"
					data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
					id="tm_client_audit_buttonsavetitle">
					 <i class="button-icon fas fa-save"></i></div>
			</div>

			<input type="hidden" name="title" id="tm_client_audit_title_newhidden" value="<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '';  ?>">
			<input type="hidden" name="title_old" id="tm_client_audit_title_old" value="<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '';  ?>">
		</div><!-- .audit-header -->

		<div class="task-button">
			<?php
			\eoxia\View_Util::exec(
				'task-manager',
				'audit',
				'audit-new-task',
				array(
					'parent_id' => $audit->data[ 'id' ]
				)
			);

			\eoxia\View_Util::exec(
				'task-manager',
				'audit',
				'audit-import-button',
				array(
					'client_id' => $parent_id,
					'audit_id' => $audit->data[ 'id' ]
				)
			);
			?>
		</div>

		<div id="tm_audit_client_generate_tasklink" class="task-list">
			<?php Audit_Class::g()->audit_client_return_task_link( $audit->data[ 'id' ] ); ?>
		</div>

	</div>
</div>
