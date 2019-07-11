<?php
/**
 * Options de la ligne d'ajout dans le tableau des indicators dans le profil utilisateur.
 *
 * @since 1.10.0
 * @version 1.10.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div style="font-size : 20px">
	<div class="tm-information-secret-element">
	<?php if( ! empty( $planning ) ): ?>
		<input type="hidden" name="name" value="<?php echo esc_attr( $planning[ 'name' ] ); ?>">
		<input type="hidden" name="period" value="<?php echo esc_attr( $planning[ 'period' ] ); ?>">
		<input type="hidden" name="from" value="<?php echo esc_attr( $planning[ 'work_from' ] ); ?>">
		<input type="hidden" name="to" value="<?php echo esc_attr( $planning[ 'work_to' ] ); ?>">
		<input type="hidden" name="daystart" value="<?php echo esc_attr( $planning[ 'day_start' ] ); ?>">
	<?php endif; ?>
</div>

	<div class="wpeo-notice notice-info" style="display : block; padding: 0.4em;">
		<div class="notice-content">
			<div class="notice-title" style="margin : 0; font-size: 30px;"><?php esc_html_e( 'Applicate same plannings ?', 'task-manager' ); ?></div>
			<div class="notice-close" style="float: right; margin-top: -25px;"><i class="fas fa-times"></i></div>

			<div class="notice-subtitle" style="font-size: 20px;">
				<?php esc_html_e( 'These days don\'t have planning define, do you want to configure them with the same data?', 'task-manager' ); ?><br />
				<div class="tm-information-notice-day">
					<?php foreach( $planning_valid as $day ): ?>
						<?php if( $day[ 'valid' ] ): ?>
							<div class="wpeo-button button-blue button-radius-3" data-valid="true" data-day="<?php echo esc_attr( $day[ 'day' ] ); ?>">
						<?php else: ?>
							<div class="wpeo-button button-grey button-radius-3" data-valid="false" data-day="<?php echo esc_attr( $day[ 'day' ] ); ?>">
						<?php endif; ?>
							<span>
								<?php echo esc_attr( Follower_Class::g()->tradThisDay( $day[ 'day' ] ) ); ?>
								<?php if( $day[ 'valid' ] ): ?>
									<i class="button-icon fas fa-check-square"></i>
								<?php else: ?>
									<i class="button-icon fas fa-square"></i>
								<?php endif; ?>
							</span>
						</div>
					<?php endforeach; ?>
				</div>


			<div class="tm-information-notice-action" style="float: right">
				<div class="wpeo-button button-red button-radius-3" data-actionjs="hide" style="margin-right: 10px;">
					<span>
						<?php esc_html_e( 'Cancel', 'task-manager' ); ?>
						<i class="far fa-window-close"></i>
					</span>
				</div>
				<div class="wpeo-button button-green button-radius-3"
				data-actionjs="request"
				data-action="applicate_same_planning_for_another_days"
				data-wpnonce="<?php echo esc_attr( wp_create_nonce( 'applicate_same_planning_for_another_days' ) ); ?>">
					<span>
						<?php esc_html_e( 'Applicate', 'task-manager' ); ?>
						<i class="far fa-check-circle"></i>
					</span>
				</div>
			</div>
	</div>
</div>
