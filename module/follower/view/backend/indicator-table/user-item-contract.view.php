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


<div class="table-row" data-id="<?php echo esc_attr( $contract[ 'id' ] ); ?>">
	<div class="table-cell"><?php echo esc_attr( $contract[ 'title' ] ); ?></div>
	<div class="table-cell"><?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) ); ?></div>
	<div class="table-cell">
		<?php if( $contract[ 'end_date_type' ] == "actual" ): ?>
				<?php esc_html_e( 'NOW', 'task-manager' ); ?>
		<?php else: ?>
				<?php echo esc_attr( date( 'd/m/Y', $contract[ 'end_date' ] ) ); ?>
		<?php endif; ?>
	</div>
	<div class="table-cell"><?php echo esc_attr( $contract[ 'duration' ] ); ?> <?php esc_html_e( 'days', 'task-manager' ); ?></div>
	<div class="table-cell"><?php echo esc_attr( $contract[ 'duration_week' ] ); ?> <?php esc_html_e( 'hours per week', 'task-manager' ); ?></div>
	<div class="table-cell table-end">
			<div class="wpeo-button button-blue action-attribute wpeo-tooltip-event"
			data-id="<?php echo esc_attr( $contract[ 'id' ] ); ?>"
			data-userid=<?php echo esc_attr( $user_id ); ?>
			data-action="<?php echo esc_attr( 'display_contract_planning' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_contract_planning' ) ); ?>"
			aria-label="<?php esc_html_e( 'Edit', 'task-manager' ); ?>">
			<i class="fas fa-pen"></i>
		</div>
		<div class="wpeo-button button-red action-delete wpeo-tooltip-event wpeo-tooltip-event"
			data-id="<?php echo esc_attr( $contract[ 'id' ] ); ?>"
			data-userid=<?php echo esc_attr( $user_id ); ?>
			data-action="<?php echo esc_attr( 'delete_this_contract' ); ?>"
			data-message="<?php esc_html_e( 'Delete this row', 'task-manager' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_this_contract' ) ); ?>"
			aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>">
				<i class="fas fa-times"></i>
		</div>
	</div>
</div>

<?php /*
<div class="wpeo-grid grid-4">
	<div class="form-element">
		<span style="font-size: 20px;"><?php echo esc_attr( $contract[ 'title' ] ); ?></span>
	</div>
	<div class="form-element group-date">
		<span class="form-label"><?php esc_html_e( 'Date Start', 'task-manager' ); ?> <i class="fas fa-calendar-alt"></i></span>
		<label class="form-field-container">
				<span style="font-size: 15px;"><?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) ); ?></span>
		</label>
	</div>

	<div class="grid-2 tm-date-end-contract" style="display: inherit;">
		<span class="form-label"><?php esc_html_e( 'Date End', 'task-manager' ); ?> <i class="fas fa-calendar-alt"></i></span>
		<div class="form-element form-align-horizontal" style="margin-left : 4px; font-size: 15px;">
			<?php if( $contract[ 'end_date_type' ] == "actual" ): ?>
					<?php esc_html_e( 'now', 'task-manager' ); ?>
			<?php else: ?>
					<?php echo esc_attr( date( 'd/m/Y', $contract[ 'end_date' ] ) ); ?>
			<?php endif; ?>
		</div>
	</div>

</div>

<?php /*

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

*/ ?>
