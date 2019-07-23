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

		<div class="wpeo-grid grid-4">
			<?php if( $edit ): // 12/07/2019 ?>
			<div class="form-element">
				<?php if( $edit ): ?>
				<span class="form-label"><?php esc_html_e( 'Title', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="text" name="title" class="form-field" value="<?php echo esc_attr( $contract[ 'title' ] ); ?>"/>
				</label>
				<?php else: ?>
					<span style="font-size: 20px;"><?php echo esc_attr( $contract[ 'title' ] ); ?></span>
				<?php endif; ?>
			</div>
			<div class="form-element group-date">
				<span class="form-label"><?php esc_html_e( 'Date Start', 'task-manager' ); ?> <i class="fas fa-calendar-alt"></i></span>
				<label class="form-field-container">
					<?php if( $edit ): ?>
						<input type="hidden" class="mysql-date" name="start_date"
						value="<?php echo esc_attr( date( 'Y-m-d', $contract[ 'start_date' ] ) ); ?>"/>
						<input type="text" class="form-field date"
						value="<?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) ); ?>" />
					<?php else: ?>
						<span style="font-size: 15px;"><?php echo esc_attr( date( 'd/m/Y', $contract[ 'start_date' ] ) ); ?></span>
					<?php endif; ?>
				</label>
			</div>

			<div class="grid-2 tm-date-end-contract" style="display: inherit;">
				<?php if( $edit ): ?>
					<input id="tm-date-end-value" type="hidden" value="<?php echo esc_attr( $contract[ 'end_date_type' ] ); ?>" name="date_end_type" />
					<div class="form-element form-align-horizontal">
						<div class="form-field-inline group-date">
							<span class="form-label"><?php esc_html_e( 'Date End', 'task-manager' ); ?> <i class="fas fa-calendar-alt"></i></span>
							<input type="radio" data-type="sql" id="tm-radio-contract-date" class="form-field" name="check_end">
							<label class="form-field-container" for="tm-radio-contract-date">
								<span class="form-field-icon-prev"></span>
								<input type="hidden" class="mysql-date" name="end_date" value="<?php echo esc_attr( date( 'Y-m-d', $contract[ 'end_date' ] ) ); ?>" />
								<input type="text" class="form-field date" value="<?php echo esc_attr( date( 'd/m/Y', $contract[ 'end_date' ] ) ); ?>" />
							</label>
						</div>
					</div>

					<div class="form-element form-align-horizontal" style="margin-left: 10px;">
						<label class="form-field-container">
							<div class="form-field-inline">
								<input type="radio" data-type="actual" id="tm-radio-contract-actual" class="form-field" name="check_end" checked>
								<label for="tm-radio-contract-actual"><?php esc_html_e( 'Planning actual', 'task-manager' ); ?></label>
							</div>
						</label>
					</div>
				<?php else: ?>
					<span class="form-label"><?php esc_html_e( 'Date End', 'task-manager' ); ?> <i class="fas fa-calendar-alt"></i></span>
					<div class="form-element form-align-horizontal" style="margin-left : 4px; font-size: 15px;">
						<?php if( $contract[ 'end_date_type' ] == "actual" ): ?>
								<?php esc_html_e( 'now', 'task-manager' ); ?>
						<?php else: ?>
								<?php echo esc_attr( date( 'd/m/Y', $contract[ 'end_date' ] ) ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

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
<div class="wpeo-button button-blue action-input wpeo-tooltip-event"
	data-parent="tm-user-add-contract"
	data-action="create_new_contract"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_new_contract' ) ); ?>"
	data-id="<?php echo esc_attr( $contract[ 'id' ] ); ?>"
	data-userid="<?php echo esc_attr( $userid ); ?>"
	aria-label="<?php esc_html_e( 'Create this contract', 'task-manager' ); ?>"
	style="margin-top: 10px; float: right;">
	<i class="fas fa-plus"></i>
</div>
