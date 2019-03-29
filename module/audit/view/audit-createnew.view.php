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

		<div class="action-attribute audit-backlink" data-action="reset_main_page" data-parent-id="<?php echo esc_attr( $parent_id ); ?>" style="float: left;">
			<i class="fas fa-arrow-left fa-3x" style="color: grey"></i>
		</div>

		<div class="audit-header">
			<div class="audit-edit-header">
				<div class="audit-title-container" style="float: left;">
					<div class="form-element">
						<label class="form-field-container">
							<input id="tm_client_audit_title_new"
								type="text"
								class="audit-title form-field"
								placeholder="<?php esc_html_e( 'Write audit title ...', 'task-manager' ); ?>" value="<?php echo esc_html( empty( $audit->data[ 'title' ] ) ? null : $audit->data[ 'title' ] );  ?>"
								style="cursor: text">
						</label>
					</div>
					<ul class="audit-summary">
						<li class="audit-summary-id"><i class="far fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
						<li class="audit-summary-date">
							<span class="form-label"><i class="far fa-calendar-alt"></i></span>
							<span id="tm_audit_client_date_start" class="date form-field">
								<?php echo esc_attr( $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ); ?>
							</span> /

							<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Due date', 'task-manager' ); ?>">
								<div class="form-element group-date">
									<span class="form-label"><i class="far fa-calendar-alt"></i></span>
									<label class="form-field-container">
										<input id="tm_audit_client_date_deadline" class="date form-field" type="text"
										value="<?php echo esc_attr( $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ); ?>" />
									</label>
								</div>
							</span>
						</li>
					</ul>
					<!-- <span class="audit-title-edit"><i class="fas fa-pencil"></i></span> -->
				</div>

				<div class="audit-save-container" style="float : left">
					<div class="wpeo-button button-green button-disable button-square-40 action-input"
						data-parent="audit-header"
						data-action="edit_title_audit"
						data-id="<?= $audit->data[ 'id' ] ?>"
						data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
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
							'client_id' => $parent_id,
							'audit_id' => $audit->data[ 'id' ]
						)
					);
					?>
					<div class="wpeo-dropdown wpeo-comment-setting" data-parent="toggle" data-target="content" data-mask="wpeo-project-task">
						<span class="wpeo-button button-transparent dropdown-toggle"><i class="fa fa-ellipsis-v"></i></span>
						<ul class="dropdown-content left content point-header-action">
							<?php /*<li class="dropdown-item action-attribute" data-action="load_edit_view_comment" data-nonce="2f800343ae" data-id="239">
								<span><i class="fas fa-pencil fa-fw"></i> Editer ce commentaire</span>
							</li>

							<li class="dropdown-item action-delete" data-action="delete_task_comment" data-message-delete="Voulez vous supprimer ce commentaire ?" data-nonce="33bf025dc1" data-id="239">
								<span><i class="fas fa-trash fa-fw"></i> Supprimer ce commentaire</span>
							</li>*/ ?>
						</ul>
					</div>
				</div>
			</div>
			<div>

			</div>



			<input type="hidden" name="title" id="tm_client_audit_title_newhidden" value="<?php echo esc_html( ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '' );  ?>">
			<input type="hidden" name="title_old" id="tm_client_audit_title_old" value="<?php echo esc_html(! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '' ); ?>">
		</div><!-- .audit-header -->



		<div id="tm_audit_client_generate_tasklink" class="task-list">
			<?php Audit_Class::g()->audit_client_return_task_link( $audit->data[ 'id' ] ); ?>
		</div>

	</div>
</div>
