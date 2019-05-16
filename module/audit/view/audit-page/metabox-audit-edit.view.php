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

<div class="tm-audit" style="padding: 20px;">
	<div class="audit-container">
		<div class="action-attribute audit-backlink"
			data-action="reset_main_page"
			data-page="metabox-main"
			data-parentpage="<?php echo isset( $parent_page ) ? esc_attr( $parent_page ) : 0 ?>"
			style="float: left; width: 40px">
			<i class="fas fa-arrow-left fa-2x" style="color: blue"></i>
		</div>

		<div class="audit-header">
			<div class="audit-edit-header">
				<div style="float :left">

					<div class="audit-title" contenteditable="false" style="font-size: 30px;">
						<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : esc_html_e( 'No name Audit', 'task-manager' );  ?></div>

					<ul class="audit-summary" style="font-size : 22px; margin-top: 15px">
						<li class="audit-summary-id"><i class="fas fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
						<li class="audit-summary-date">
							<span class="summary-created wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Created date', 'task-manager' ); ?>">
								<i class="fas fa-calendar-alt"></i> <?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?>
							</span>
							<?php /*<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Due date', 'task-manager' ); ?>">
								<i class="fas fa-calendar-alt"></i> <?= $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ?>
							</span>*/ ?>
						</li>
							<?php if( isset( $audit->data[ 'parent_id' ] ) && $audit->data[ 'parent_id' ] ): ?>
								<span class="summary-rendered">
									<a class="wpeo-tooltip-event wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Audit Parent', 'task-manager' ); ?>"
										href="<?php echo admin_url( 'post.php?post=' . $audit->data[ 'parent_id' ] . '&action=edit' ); ?>" style="text-decoration: none;">
										<i class="fas fa-clone"></i>
										#<?php echo esc_html( $audit->data[ 'parent_id' ] ); ?> -
										<?php echo esc_html( $audit->data[ 'parent_title' ] ); ?>
									</a>
								</span>
							<?php endif; ?>
						</li>
					</ul>
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
						'audit-page/metabox-importbutton',
						array(
							'audit_id' => $audit->data[ 'id' ],
							'tags'     => $tags
						)
					);
					?>
					<div class="wpeo-dropdown wpeo-comment-setting" data-parent="toggle" data-target="content" data-mask="wpeo-project-task">
						<span class="wpeo-button button-transparent dropdown-toggle"><i class="fa fa-ellipsis-v"></i></span>
							<ul class="dropdown-content left content point-header-action">
								<li class="dropdown-item action-delete"
										data-action="delete_audit"
										data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_audit' ) ); ?>"
										data-parentpage="<?php echo isset( $parent_page ) ? esc_attr( $parent_page ) : 0 ?>"
										data-id="<?= $audit->data[ 'id' ] ?>"
										data-message-delete="<?php echo esc_attr_e( 'Delete this audit ?', 'task-manager' ); ?>">
									<span><i class="fas fa-trash fa-fw"></i> <?php esc_html_e( 'Delete this audit', 'task-manager' ); ?></span>
								</li>
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
