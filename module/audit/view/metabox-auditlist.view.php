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
	<div class="wpeo-gridlayout grid-4 tm_audit_search wpeo-form form-light">
		<div class="form-element">
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-calendar"></i></span>
				<input id="tm_indicator_date_start_id" class="form-field" placeholder="<?php esc_html_e( 'Start date', 'task-manager' ); ?>" onfocus="(this.type='date')" name="tm_indicator_date_start" />
			</label>
		</div>

		<div class="form-element">
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-calendar"></i></span>
				<input id="tm_indicator_date_end_id" class="form-field" placeholder="<?php esc_html_e( 'End date', 'task-manager' ); ?>" onfocus="(this.type='date')" name="tm_indicator_date_end" />
			</label>
		</div>

		<div class="form-element">
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-filter"></i></span>

				<select id="tm_audit_selector_search" class="form-field" value="<?php esc_html_e( 'Filter', 'task-manager' ); ?>" name="tm_audit_selector_search_" style="width: 80%">
					<option value="all"><?php esc_html_e( 'All audit', 'task-manager' ); ?></option>
					<option value="completed"><?php esc_html_e( 'Audit completed', 'task-manager' ); ?></option>
					<option value="progress"><?php esc_html_e( 'Audit in progress', 'task-manager' ); ?></option>
				</select>
			</label>
		</div>

		<div class="form-element grid-1">
				<span class="action-input alignright wpeo-button button-main button-square-40"
				id="tm_audit_button_search"
				data-action="search_audit_client"
				data-parent="tm_audit_search"
				data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'search_audit_client' ) ); ?>"
				data-modification="">
				<i class="button-icon fas fa-search"></i></span>
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
	<div class="tm_client_audit_edit"></div>
<?php endif; ?>
