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
<div class="tm-audit tm_audit_item_<?php echo esc_html( $audit->data[ 'id' ] ); ?>" data-id="<?= $audit->data[ 'id' ] ?>">

	<div class="audit-progress">
		<div class="progress-bar" style="width:<?= $audit->data[ 'info' ][ 'percent_uncompleted_points' ] . '%' ?>; background-color :<?=  $audit->data[ 'info' ][ 'color' ] ?>;"></div>
		<span class="progress-text"><?= ( $audit->data[ 'info' ][ 'percent_uncompleted_points' ] > 5 ) ? $audit->data[ 'info' ][ 'count_completed_points' ] . ' /' . ( $audit->data[ 'info' ][ 'count_uncompleted_points' ] + $audit->data[ 'info' ][ 'count_completed_points' ] . ' (' . $audit->data[ 'info' ][ 'percent_uncompleted_points' ] . '%) ' ) : '' ?></span>
	</div>

	<div class="audit-container">

		<div class="audit-header">
			<div class="audit-title action-attribute" contenteditable="false"
				data-action="edit_audit"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_audit' ) ); ?>"
				data-id="<?= $audit->data[ 'id' ] ?>"
				data-parent-id="<?= $parent_id ?>">
				<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : esc_html_e( 'New Audit', 'task-manager' );  ?></div>

			<ul class="audit-summary">
				<li class="audit-summary-id"><i class="fas fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
				<li class="audit-summary-date">
					<span class="summary-created wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Created date', 'task-manager' ); ?>">
						<i class="fas fa-calendar-alt"></i> <?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?>
					</span> /
					<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Due date', 'task-manager' ); ?>">
						<i class="fas fa-calendar-alt"></i> <?= $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ?>
					</span>
				</li>
			</ul>
		</div>

		<div id="audit_client_indicator_<?= $audit->data[ 'id' ] ?>" class="audit-chart-container" data-grid="1"></div>

		<div class="audit-action">

				<div class="wpeo-dropdown wpeo-comment-setting" data-parent="toggle" data-target="content" data-mask="wpeo-project-task">

					<span class="wpeo-button button-transparent dropdown-toggle"><i class="button-icon fa fa-ellipsis-v"></i></span>

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
	</div><!-- .audit-container -->

</div><!-- .tm-audit -->
