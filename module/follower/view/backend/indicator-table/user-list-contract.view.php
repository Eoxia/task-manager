<?php
/**
 * Options dans le profil utilisateur.
 *
 * @since 1.8.0
 * @version 1.8.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!--  tm-display-new-contract -->
<div style="display : inline-flex; margin-bottom : 10px">
	<h4><?php esc_html_e( 'List of contracts', 'task-manager' ); ?></h4>
	<div class="wpeo-button button-blue button-square-30 button-rounded button-bordered action-attribute wpeo-tooltip-event" style="margin-left: 5px"
		data-action="display_contract_planning"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_contract_planning' ) ); ?>"
		aria-label="<?php esc_html_e( 'Create new contract', 'task-manager' ); ?>">
		<i class="fas fa-plus"></i>
	</div>
</div>

<div class="tm-list-contract-button">
	<?php if( ! empty( $contracts ) ): ?>
		<?php foreach( $contracts as $contract ): ?>
			<?php if( $contract[ 'status' ] != "delete" ): ?>
				<div class="wpeo-button button-blue button-bordered action-attribute wpeo-tooltip-event"
					data-id="<?php echo esc_attr( $contract[ 'id' ] ); ?>"
					data-action="<?php echo esc_attr( 'display_contract_planning' ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_contract_planning' ) ); ?>"
					<?php if( $contract[ 'end_date_type' ] == "actual" ): ?>
						aria-label="<?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) . ' -> ' ) ?><?php esc_html_e( 'now', 'task-manager' ); ?>">
					<?php else: ?>
						aria-label="<?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) . ' -> ' . date( 'd/m/Y', $contract[ 'end_date' ] ) ); ?>">
					<?php endif; ?>
					<?php echo esc_attr( $contract[ 'title' ] ); ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="tm-contract-info-empty">
			<?php esc_html_e( 'No contract for now', 'task-manager' ); ?>
		</div>
	<?php endif; ?>
</div>

<div class="tm-user-add-contract"></div>
<div class="tm-user-add-contract-error" style="display : none">
	<div class="wpeo-notice notice-error">
		<div class="notice-content">
			<div class="notice-title"></div>
		</div>
		<div class="notice-close"><i class="fas fa-times"></i></div>
	</div>
</div>
