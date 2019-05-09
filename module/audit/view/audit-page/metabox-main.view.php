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

				<select id="tm_audit_selector_search" class="tm_audit_search_update" class="form-field" value="<?php esc_html_e( 'Filter', 'task-manager' ); ?>" name="tm_audit_selector_search_" style="width: 80%">
					<option value="all"><?php esc_html_e( 'All audit', 'task-manager' ); ?></option>
					<option value="completed"><?php esc_html_e( 'Audit completed', 'task-manager' ); ?></option>
					<option value="progress"><?php esc_html_e( 'Audit in progress', 'task-manager' ); ?></option>
				</select>
			</label>
		</div>

		<?php /*<div class="form-element"> // AFFICHE TOUS LES CUSTOMERS EN OPTION -> SELECT
			<label class="form-field-container">
				<span class="form-field-label-next"><i class="fas fa-clone"></i></span>

				<select id="tm_audit_selector_customer" class="form-field tm_audit_search_update" value="<?php esc_html_e( 'Filter', 'task-manager' ); ?>" name="tm_audit_selector_customer" style="width: 80%">
					<option value="0"><?php esc_html_e( 'Select a customer', 'task-manager' ); ?></option>
					<?php echo apply_filters( 'tm_audit_list_customers', '' ); ?>
				</select>
			</label>
		</div>*/ ?>

		<?php /*<div class="form-element"> // AFFICHE TOUS LES CUSTOMERS EN INPUT AUTOCOMPLETE -> BUG ?
			<?php
				global $eo_search;
				$eo_search->register_search(
					'tm_search_customer',
					array(
						'label' => 'Client',
						'icon'  => 'fa-search',
						'type'  => 'post',
						'name'  => 'post_parent',
						'args'  => array(
							'post_type'   => 'wpshop_customers',
							'post_status' => array( 'publish', 'inherit', 'draft' ),
						),
					)
				);

			$eo_search->display( 'tm_search_customer' );?>
		</div> */ ?>
		<div class="form-element tm-define-customer-to-audit">
			<div class="form-fields">
				<input type="hidden" class="audit_search-customers-id" name="tm_audit_selector_customer"/>
				<input type="text" class="audit-search-customers ui-autocomplete-input" placeholder="Nom/ID Client" autocomplete="off" />
			</div>
		</div>


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
				'audit-page/metabox-foreach',
				array(
					'audits' => $audits,
				)
			);
		endif;
		?>

	</div>
</div>
<?php if( isset( $showedit ) && ! $showedit ): ?>
	<div class="tm_client_audit_edit"></div>
<?php endif; ?>
