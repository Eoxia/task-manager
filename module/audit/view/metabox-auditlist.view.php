<?php
/**
 * La vue principale de la page des clients WPShop.
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


<div class="tm_client_audit_main">
	<div class="wpeo-grid grid-4 tm_audit_search" style="background-color : rgba(0,0,0,0.05); margin-left: 0; margin-right: 0">
		<div class="form-element grid-1">
			<span class="form-label"><i class="fas fa-calendar"></i> <?php //esc_html_e( 'Start date', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<input id="tm_indicator_date_start_id" class="form-field" placeholder="<?php esc_html_e( 'Start date', 'task-manager' ); ?>" onfocus="(this.type='date')" name="tm_indicator_date_start" style="width: 80%"/>
			</label>
		</div>

		<div class="form-element grid-1">
			<span class="form-label"><i class="fas fa-calendar"></i> <?php //esc_html_e( 'End date', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<input id="tm_indicator_date_end_id" class="form-field" placeholder="<?php esc_html_e( 'End date', 'task-manager' ); ?>" onfocus="(this.type='date')" name="tm_indicator_date_end" style="width: 80%" />
			</label>
		</div>

		<div class="form-element grid-1">
			<span class="form-label"><i class="fas fa-filter"></i> <?php //esc_html_e( 'Filter', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<select id="tm_audit_selector_search" class="form-field" value="<?php esc_html_e( 'Filter', 'task-manager' ); ?>" name="tm_audit_selector_search_" style="width: 80%">
					<option value="all">
						<?php esc_html_e( 'All audit', 'task-manager' ); ?>
					</option>
					<option value="completed">
						<?php esc_html_e( 'Audit completed', 'task-manager' ); ?>
					</option>
					<option value="progress">
						<?php esc_html_e( 'Audit in progress', 'task-manager' ); ?>
					</option>

				</select>
			</label>
		</div>

		<div class="form-element grid-1">
				<span class="action-input page-title-action alignright"
				id="tm_audit_button_search"
				data-action="search_audit_client"
				data-parent="tm_audit_search"
				data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'search_audit_client' ) ); ?>"
				data-modification=""
				style="top : 6px"><?php echo esc_html( sprintf( __( 'Validate', 'task-manager' ) ) ); ?></span>
		</div>
	</div>

	<div id="tm_client_audit_listauditmain">

		<?php
		if( ! empty ( $audits ) ):
			\eoxia\View_Util::exec(
				'task-manager',
				'audit',
				'audit-list',
				array(
					'audits' => $audits,
					'parent_id' => $parent_id
				)
			);
		endif;
		?>

	</div>
</div>

<?php if( isset( $showedit ) && ! $showedit ): ?>
	<div class="tm_client_audit_edit" style="padding: 10px; background-color: rgba(0,0,0,0.05);"></div>

<?php endif; ?>
