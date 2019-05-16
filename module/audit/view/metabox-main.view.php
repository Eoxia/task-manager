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

<div class="tm_client_audit_main" style="background:none; background-color : #f1f1f1">
	<div class="wpeo-gridlayout grid-5 tm_audit_search wpeo-form form-light">
		<div class="form-element">
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-calendar"></i></span>

				<div class="form-element group-date">
					<!-- <span class="form-label"><i class="fas fa-calendar-alt"></i><?php //esc_html_e( 'Due date', 'task-manager' ); ?></span> -->
					<label class="form-field-container">
						<input type="hidden" class="mysql-date" name="tm_indicator_date_start" />
						<input class="date form-field" type="text" placeholder="<?php esc_html_e( 'Start date', 'task-manager' ); ?>" />
					</label>
				</div>

			</label>
		</div>

		<div class="form-element">
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-calendar"></i></span>

				<div class="form-element group-date">
					<!-- <span class="form-label"><i class="fas fa-calendar-alt"></i><?php //esc_html_e( 'Due date', 'task-manager' ); ?></span> -->
					<label class="form-field-container">
						<input type="hidden" class="mysql-date" name="tm_indicator_date_end" />
						<input class="date form-field" type="text" placeholder="<?php esc_html_e( 'End date', 'task-manager' ); ?>" />
					</label>
				</div>

			</label>
		</div>

		<div class="form-element">
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-filter"></i></span>
				<div class="wpeo-dropdown">
					<span class="dropdown-toggle form-field"><span class="display-text-audit"><?php esc_html_e( 'Audit type', 'task-manager' ); ?></span>
					<i class="fas fa-caret-down"></i></span>
					<input type="hidden" class="tm_audit_search_hidden" name="tm_audit_selector_search_" >
					<ul class="dropdown-content" id="tm_audit_selector_search" class="tm_audit_search_update">
						<li class="dropdown-item">
							<?php esc_html_e( 'All audit', 'task-manager' ); ?>
							<input type="hidden" value="all" />
						</li>
						<li class="dropdown-item">
							<?php esc_html_e( 'Audit completed', 'task-manager' ); ?>
							<input type="hidden" value="completed" />
						</li>
						<li class="dropdown-item">
							<?php esc_html_e( 'Audit in progress', 'task-manager' ); ?>
							<input type="hidden" value="progress" />
						</li>
					</ul>
				</div>
			</label>
		</div>

		<?php if( ! isset( $parent_id ) || $parent_id == 0 ): ?>

			<div class="form-element tm-define-customer-to-audit">
				<div class="form-fields">
					<input type="hidden" class="audit_search-customers-id" name="tm_audit_selector_customer"/>
					<input type="text" class="audit-search-customers ui-autocomplete-input" placeholder="Nom/ID Client" autocomplete="off" />
				</div>
			</div>

		<?php	else: ?>
			<div class="form-element">

			</div>
		<?php	endif; ?>

		<div class="form-element grid-1">
				<span class="action-input alignright wpeo-button button-main button-square-40"
				id="tm_audit_button_search"
				data-action="search_audit_client"
				data-parent="tm_audit_search"
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
				'metabox-foreach',
				array(
					'audits' => $audits,
					'parent_page' => $parent_id
				)
			);
		endif;
		?>

	</div>
</div>
<?php if( isset( $showedit ) && ! $showedit ):
	 			if( isset( $parent_id ) && $parent_id > 0 ): ?>
					<div class="tm_client_audit_edit" style="display : none"></div>
				<?php else: ?>
					<div class="tm_client_audit_edit" style="margin-top: 10px; display : none"></div>
	<?php	endif;
			endif; ?>
