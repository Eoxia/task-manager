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
		<div class="action-attribute page-title-action wpeo-tooltip-event" style="margin-left: 5px"
		data-action="display_contract_planning"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_contract_planning' ) ); ?>"
		data-userid="<?php echo esc_attr( $user_id ); ?>"
		aria-label="<?php esc_html_e( 'Create new contract', 'task-manager' ); ?>">
		<?php esc_html_e( 'New contract', 'task-manager' ); ?>
	</div>
</div>

<div class="tm-list-contract-button">
	<?php if( ! empty( $contracts ) && $one_contract_is_valid ): ?>
		<div class="wpeo-table table-flex table-4">
			<div class="table-row table-header">
				<div class="table-cell"><?php esc_html_e( 'Title', 'task-manager' ); ?></div>
				<div class="table-cell"><?php esc_html_e( 'Date start', 'task-manager' ); ?></div>
				<div class="table-cell"><?php esc_html_e( 'Date end', 'task-manager' ); ?></div>
				<div class="table-cell"><?php esc_html_e( 'Time slot', 'task-manager' ); ?></div>
				<div class="table-cell"><?php esc_html_e( 'Duration', 'task-manager' ); ?></div>
				<div class="table-cell"></div>
			</div>
			<?php
			$first_element = true;
			 foreach( $contracts as $contract ): ?>
				<?php if( $contract[ 'status' ] != "delete" ): ?>
					<?php	\eoxia\View_Util::exec(
						'task-manager',
						'follower',
						'backend/indicator-table/user-item-contract',
						array(
							'contract' => $contract,
							'first_el' => $first_element,
							'user_id'  => $user_id
						)
					);
					if( $first_element ){
						$first_element = false;
					}
					?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

	<?php else: ?>
		<div class="tm-contract-info-empty">
			<?php esc_html_e( 'No contract for now', 'task-manager' ); ?>
		</div>
	<?php endif; ?>
</div>

<div class="tm-user-add-contract" style="margin-top: 20px">
	<?php // Follower_Class::g()->loadPlanningContract(); ?>
</div>
<div class="tm-user-add-contract-error" style="display : none">
	<div class="wpeo-notice notice-error">
		<div class="notice-content">
			<div class="notice-title"></div>
		</div>
		<div class="notice-close"><i class="fas fa-times"></i></div>
	</div>
</div>
