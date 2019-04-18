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

		<div class="action-attribute audit-backlink"
			data-action="reset_main_page"
			data-page="metabox-main"
			style="float: left;">
			<i class="fas fa-arrow-left fa-3x" style="color: grey"></i>
		</div>

		<div class="audit-header">
			<div class="audit-edit-header">
				<div class="audit-title-container" style="float: left;">
					<div class="form-element">
						<label class="form-field-container">
							<input id="tm_client_audit_title_new"	type="text"	class="audit-title form-field" name="title"
								placeholder="<?php esc_html_e( 'Write audit title ...', 'task-manager' ); ?>" value="<?php echo esc_html( empty( $audit->data[ 'title' ] ) ? null : $audit->data[ 'title' ] );  ?>"
								style="cursor: text">
						</label>
					</div>
					<ul class="audit-summary">
						<li class="audit-summary-id"><i class="far fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
						<li class="audit-summary-date">

							<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Creation date', 'task-manager' ); ?>">
								<span class="form-label"><i class="far fa-calendar-alt"></i></span>
								<span id="tm_audit_client_date_start" class="date form-field">
									<?php echo esc_attr( $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ); ?>
								</span>
							</span> /

							<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Due date', 'task-manager' ); ?>">
								<div class="form-element group-date">
									<span class="form-label"><i class="far fa-calendar-alt"></i></span>
									<label class="form-field-container">
										<input type="hidden" class="mysql-date" name="due_date" value="<?php echo esc_attr( $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ); ?>">
										<input id="tm_audit_client_date_deadline" class="date form-field" type="text"
										value="<?php echo esc_attr( $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ); ?>" />
									</label>
								</div>
							</span>
						</li>
						<li>
							<?php if( isset( $audit->data[ 'parent_id' ] ) && $audit->data[ 'parent_id' ] ): ?>
								<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Audit Parent', 'task-manager' ); ?>">
									<i class="far fa-clone"></i>
									<?php echo esc_html( $audit->data[ 'parent_id' ] ); ?>
								</span>
							<?php else: ?>
								<form class="tm-define-customer-to-audit">
									<div class="form-fields">
										<input type="hidden" class="audit_search-customers-id" name="customer_id"/>
								    <input type="text" class="audit-search-customers ui-autocomplete-input" placeholder="Nom/ID Client" autocomplete="off" />
								  </div>
								</form>

								<span class="summary-rendered wpeo-tooltip-event tm-define-customer-to-audit-after" aria-label="<?php esc_html_e( 'Audit Parent', 'task-manager' ); ?>" style="display : none">
									<i class="far fa-clone"></i>
								</span>
							<?php endif; ?>
						</li>

					</ul>
					<!-- <span class="audit-title-edit"><i class="fas fa-pencil"></i></span> -->
				</div>

				<div class="audit-save-container" style="float : left">
					<div class="wpeo-button button-green button-disable button-square-40 action-input"
						data-parent="audit-header"
						data-action="edit_title_audit"
						data-id="<?= $audit->data[ 'id' ] ?>"
						id="tm_client_audit_buttonsavetitle">
						 <i class="button-icon fas fa-save"></i></div>
				</div>

				<div class="task-button alignright">
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
							'audit_id' => $audit->data[ 'id' ]
						)
					);
					?>
					<div class="wpeo-dropdown wpeo-comment-setting" data-parent="toggle" data-target="content" data-mask="wpeo-project-task">
						<span class="wpeo-button button-transparent dropdown-toggle"><i class="fa fa-ellipsis-v"></i></span>
						<ul class="dropdown-content left content point-header-action">

						</ul>
					</div>
				</div>
			</div>

			<input type="hidden" id="tm_client_audit_title_old" value="<?php echo esc_html(! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '' ); ?>">
		</div><!-- .audit-header -->



		<div id="tm_audit_client_generate_tasklink" class="task-list">
			<?php Audit_Class::g()->audit_client_return_task_link( $audit->data[ 'id' ] ); ?>
		</div>
	</div>
</div>
