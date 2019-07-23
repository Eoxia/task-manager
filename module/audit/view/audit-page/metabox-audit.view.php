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

<?php if( ! empty( $audit->data[ 'info' ] ) ): ?>
	<div class="audit-progress">
		<div class="progress-bar" style="width:<?= $audit->data[ 'info' ][ 'percent_uncompleted_points' ] . '%' ?>; background-color :<?=  $audit->data[ 'info' ][ 'color' ] ?>;"></div>
		<span class="progress-text"><?= ( $audit->data[ 'info' ][ 'percent_uncompleted_points' ] > 5 ) ? $audit->data[ 'info' ][ 'count_completed_points' ] . ' /' . ( $audit->data[ 'info' ][ 'count_uncompleted_points' ] + $audit->data[ 'info' ][ 'count_completed_points' ] . ' (' . $audit->data[ 'info' ][ 'percent_uncompleted_points' ] . '%) ' ) : '' ?></span>
	</div>
<?php endif; ?>


	<div class="audit-container">

		<div class="audit-header">
			<div class="tm-audit-display-editmode" style="display : none">

				<?php
					\eoxia\View_Util::exec(
						'task-manager',
						'audit',
						'audit-page/metabox-audit-editmod',
						array(
							'audit' => $audit,
							'parent_page' => isset( $parent_page ) && $parent_page > 0 ? $parent_page : 0
						)
					);
				?>

			</div>
			<div class="tm-audit-display-readonly">

				<?php
					\eoxia\View_Util::exec(
						'task-manager',
						'audit',
						'audit-page/metabox-audit-readonly',
						array(
							'audit' => $audit,
							'parent_page' => isset( $parent_page ) ? $parent_page : 0
						)
					);
				?>

			</div>
		</div>

		<div id="audit_client_indicator_<?= $audit->data[ 'id' ] ?>" class="audit-chart-container" data-grid="1"></div>

		<div class="update-to-edit-view-audit" data-editmode="0" style="margin: auto 0; margin-left: 25px;">
			<div class="wpeo-button button-main tm-main-mode">
				<span><i class="fas fa-pen"></i></span>
			</div>

			<div class="wpeo-button button-green tm-valid-edit action-input"
			data-action="edit_title_audit"
			data-parent="audit-container"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title_audit' ) ); ?>"
			data-parentpage=<?php echo isset( $parent_page ) ? $parent_page : 0 ?>
			data-id="<?= $audit->data[ 'id' ] ?>"
			style="display : none">
				<span><i class="fas fa-save"></i></span>
			</div>
		</div>

		<div class="audit-action">

				<div class="wpeo-dropdown wpeo-comment-setting" data-parent="toggle" data-target="content" data-mask="wpeo-project-task">

					<span class="wpeo-button button-transparent dropdown-toggle"><i class="button-icon fa fa-ellipsis-v"></i></span>

					<ul class="dropdown-content left content point-header-action">
						<li class="dropdown-item action-attribute"
								data-action="edit_audit"
								data-page="audit-page/metabox-audit-edit"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_audit' ) ); ?>"
								data-id="<?= $audit->data[ 'id' ] ?>"
								data-parentpage="<?php echo isset( $parent_page ) && $parent_page > 0 ? esc_attr( $parent_page ) : 0 ?>"
								style="cursor : pointer">
							<span><i class="fas fa-arrow-right fa-fw"></i> <?php esc_html_e( 'See audit', 'task-manager' ); ?></span>
						</li>

						<li class="dropdown-item action-delete"
								data-action="delete_audit"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_audit' ) ); ?>"
								data-page="audit-page/metabox-audit-edit"
								data-id="<?= $audit->data[ 'id' ] ?>"
								data-loader="audit-container"
								data-message-delete="<?php echo esc_attr_e( 'Delete this audit ?', 'task-manager' ); ?>"
								style="cursor : pointer">
							<span><i class="fas fa-trash fa-fw"></i> <?php esc_html_e( 'Delete this audit', 'task-manager' ); ?></span>
						</li>
					</ul>

				</div>
		</div>
	</div><!-- .audit-container -->

</div><!-- .tm-audit -->
