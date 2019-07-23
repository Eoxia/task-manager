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

<div class="table-row tm-table-edit">
	<div class="table-cell form-element">
			<label class="form-field-container">
				<input type="text" name="title" class="form-field" value="<?php echo esc_attr( $contract[ 'title' ] ); ?>"/>
			</label>
	</div>
	<div class="table-cell">
		<div class="form-element group-date">
			<label class="form-field-container">
					<input type="hidden" class="mysql-date" name="start_date"
					value="<?php echo esc_attr( date( 'Y-m-d', $contract[ 'start_date' ] ) ); ?>"/>
					<input type="text" class="form-field date" value="<?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) ); ?>" />
			</label>
		</div>
	</div>
	<div class="table-cell tm-date-end-contract" style="display: inherit;">
		<input id='tm-date-end-value' type="hidden" value="<?php echo esc_attr( $contract[ 'end_date_type' ] ); ?>" name="date_end_type" />
		<div class="form-element form-align-horizontal">
			<div class="form-field-inline group-date">
				<?php if( $contract[ 'end_date_type' ] == "sql" ): ?>
					<input type="radio" data-type="sql" id="tm-radio-contract-date" class="form-field" name="check_end" checked>
				<?php else: ?>
					<input type="radio" data-type="sql" id="tm-radio-contract-date" class="form-field" name="check_end">
				<?php endif; ?>
				<label class="form-field-container" for="tm-radio-contract-date">
					<span class="form-field-icon-prev"></span>
					<input type="hidden" class="mysql-date" name="end_date" value="<?php echo esc_attr( date( 'Y-m-d', $contract[ 'end_date' ] ) ); ?>" />
					<input type="text" class="form-field date" value="<?php echo esc_attr( date( 'd/m/Y', $contract[ 'end_date' ] ) ); ?>" />
				</label>
			</div>
		</div>
	</div>
	<div class="table-cell tm-date-end-contract">
		<div class="form-element form-align-horizontal" style="margin-left: 10px;">
			<label class="form-field-container">
				<div class="form-field-inline">
					<?php if( $contract[ 'end_date_type' ] == "actual" ): ?>
						<input type="radio" data-type="actual" id="tm-radio-contract-actual" class="form-field" name="check_end" checked>
					<?php else: ?>
						<input type="radio" data-type="actual" id="tm-radio-contract-actual" class="form-field" name="check_end">
					<?php endif; ?>
					<label for="tm-radio-contract-actual"><?php esc_html_e( 'Planning actual', 'task-manager' ); ?></label>
				</div>
			</label>
		</div></div>
	<div class="table-cell"></div>

	<div class="table-cell table-end">
			<div class="wpeo-button button-green action-input wpeo-tooltip-event tm-display-loading-table-planning"
			data-userid="<?php echo esc_attr( $userid ); ?>"
			data-id="<?php echo esc_attr( $contract[ 'id' ] ); ?>"
			data-parent="wpeo-table"
			data-loader="wpeo-table"
			data-action="<?php echo esc_attr( 'create_new_contract' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_new_contract' ) ); ?>"
			aria-label="<?php esc_html_e( 'Save contract', 'task-manager' ); ?>">
			<i class="fas fa-save"></i>
		</div>
	</div>
</div>
<?php
/*
	<div class="table-row tm-table-edit">
		<?php
			\eoxia\View_Util::exec(
				'task-manager',
				'follower',
				'backend/indicator-table/user-profile-planning-only',
				array(
					'planning' => $planning,
					'periods'  => $periods,
					'edit'     => $edit,
					'days'     => $days
				)
			);
		?>
	</div>
*/
 ?>
