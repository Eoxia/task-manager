<?php
/**
 * La vue d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="points sortable ui-sortable tm_audit_item_<?= $audit->data[ 'id' ] ?>" data-id="<?= $audit->data[ 'id' ] ?>">

	<div class="point new" style="border-radius: 2px;">
		<div class="form">
			<div class="progress" style="background-color: rgb(249, 249, 249); height: 10px;">
		    <div class="progress-bar"
				style="width:<?= $audit->data[ 'info' ][ 'percent_uncompleted_points' ] . '%' ?>; background-color :<?=  $audit->data[ 'info' ][ 'color' ] ?>; height: 10px;">

				<span>
					<?= ( $audit->data[ 'info' ][ 'percent_uncompleted_points' ] > 5 ) ? $audit->data[ 'info' ][ 'count_completed_points' ] . ' /' . ( $audit->data[ 'info' ][ 'count_uncompleted_points' ] + $audit->data[ 'info' ][ 'count_completed_points' ] . ' (' . $audit->data[ 'info' ][ 'percent_uncompleted_points' ] . '%) ' ) : '' ?>
				</span>

		    </div>
		  </div>
			<div class="point-container wpeo-grid grid-6">

				<div class="wpeo-task-main-info grid-2" style="font-size: 22px">
					<div class="wpeo-task-title">
						<div contenteditable="false" class="wpeo-project-task-title"><?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : esc_html_e( 'This audit has no name', 'task-manager' );  ?>
						</div>
					</div>


					<div class="wpeo-task-summary" style="padding: 4px; font-size: 14px; color: rgba(0,0,0,0.4); line-height: 1; white-space: nowrap;">
						<li class="wpeo-task-id"><i class="far fa-hashtag"></i><?= $audit->data[ 'id' ] ?>
							<span class="wpeo-task-date tooltip hover">
								<i class="far fa-calendar-alt"></i>
								<span> <?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?></span>
								<?= $audit->data[ 'deadline' ][ 'rendered' ][ 'mysql' ] ?>
							</span>
						</li>
					</div>
				</div>

				<div class="grid-3">
					<div id="audit_client_indicator_<?= $audit->data[ 'id' ] ?>" class="wpeo-grid grid-4" data-grid="1"></div>
				</div>

				<div class="point-action grid-1" style="min-width : 0%">

					<div class="comment-action" style="margin-left: 50%;">
						<div class="wpeo-dropdown wpeo-comment-setting"
								data-parent="toggle"
								data-target="content"
								data-mask="wpeo-project-task">

							<span class="wpeo-button button-transparent dropdown-toggle">
								<i class="fa fa-ellipsis-v"></i>
							</span>

							<ul class="dropdown-content left content point-header-action">

								<li class="dropdown-item action-attribute"
										data-action="edit_audit"
										data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_audit' ) ); ?>"
										data-id="<?= $audit->data[ 'id' ] ?>"
										data-parent-id="<?= $parent_id ?>">
									<span><i class="fas fa-pencil fa-fw"></i> <?php esc_html_e( 'Edit this audit', 'task-manager' ); ?></span>
								</li>

								<li class="dropdown-item action-delete"
										data-action="delete_audit"
										data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_audit' ) ); ?>"
										data-id="<?= $audit->data[ 'id' ] ?>"
										data-message-delete="<?php echo esc_attr_e( 'Delete this audit ?', 'task-manager' ); ?>"
										data-parent-id="<?= $parent_id ?>">
									<span><i class="fas fa-trash fa-fw"></i> <?php esc_html_e( 'Delete this audit', 'task-manager' ); ?></span>
								</li>
							</ul>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
