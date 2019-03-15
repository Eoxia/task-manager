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

<div style="margin-bottom: 5px" class="action-attribute" data-action="reset_main_page" data-parent-id="<?php echo esc_attr( $parent_id ); ?>">
	<label style="color : blue; font-size : 12px" >
		< <?= esc_html_e( 'Back to list audit', 'task-manager' ); ?>

	</label>
</div>

<div class="points sortable ui-sortable" style="background-color: transparent">

	<div class="point new" data-parentid="<?php echo esc_attr( $parent_id ); ?>" data-auditid="<?= $audit->data[ 'id' ] ?>" id="tm_client_audit_data">
		<div class="form-audit">
			<ul class="point-container">
				<li class="wpeo-task-main-info" style="font-size: 22px">
					<div class="wpeo-task-title">

						<div contenteditable="true" id="tm_client_audit_title_new" class="wpeo-project-audit-title tm_placeholder_c" placeholder="<?= esc_html_e( 'Write audit title ...', 'task-manager' ); ?>"><?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '';  ?></div>

						<input type="hidden" name="title" id="tm_client_audit_title_newhidden" value="<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : 'No name audit';  ?>">
						<input type="hidden" name="title_old" id="tm_client_audit_title_old" value="<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : '';  ?>">
					</div>

					<ul class="wpeo-task-summary" style="font-size: 14px; line-height: 2; white-space: nowrap;">
						<li class="wpeo-task-id"><i class="far fa-hashtag"></i><?= $audit->data[ 'id' ] ?>
							<span class="wpeo-task-date tooltip hover">
								<i class="far fa-calendar-alt"></i>
								<span> <?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?></span> ->
								<span>
									<input id="tm_audit_client_date_deadline" name="date_deadline" type="text" onfocus="(this.type='date')" placeholder="<?php esc_html_e( 'Deadline date', 'task-manager' ); ?>"  style="margin-top: 10px;">
								</span>
								<?php // echo '<pre>'; print_r( $audit ); echo '</pre>'; ?>
							</span>
						</li>
					</ul>

				</li>

				<li class="point-action" style="min-width : 0%">
					<button class="wpeo-button button-disable alignright action-input"
						data-parent="form-audit"
						data-action="edit_title_audit"
						data-id="<?= $audit->data[ 'id' ] ?>"
						data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
						id="tm_client_audit_buttonsavetitle">
						 <i class="fas fa-save"></i>
					 </button>
				</li>

			</ul>
		</div>
	</div>
</div>


<div>

	<div style="border-bottom: 1px solid rgba(0,0,0,0.1); width: 100%;">

	</div>


			<?php
			\eoxia\View_Util::exec(
				'task-manager',
				'audit',
				'audit-new-task',
				array(
					'parent_id' => $audit->data[ 'id' ]
				)
			);
			?>

			<?php

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



			<div id="tm_audit_client_tasklink_button">
				<?php // Audit_Class::g()->audit_client_return_task_linkbutton( $audit->data[ 'id' ] ); ?>
			</div>

	<div id="tm_audit_client_generate_tasklink">
		<?php Audit_Class::g()->audit_client_return_task_link( $audit->data[ 'id' ] ); ?>
	<div>

</div>
