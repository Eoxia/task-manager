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

<!-- Structure -->
<div class="wpeo-modal modal-active">
	<div class="modal-container" style="max-height: none; width: 90%; height: 80%; max-width: none;">

		<div class="modal-header">
			<h2 class="modal-title"><?php esc_html_e( 'List of archive', 'task-manager' ); ?></h2>
			<div class="modal-close"><i class="fal fa-times"></i></div>
		</div>

		<!-- Corps -->
		<div class="modal-content">
			<div class="wpeo-grid grid-5">
				<?php foreach( $datas as $key_d => $day ): ?>
					<?php foreach( $day as $key_p => $period ): ?>
						<div class="wpeo-button button-blue" data-type="<?php echo esc_attr( $key_d . '-' . $key_p ); ?>">
							<span>
								<?php echo esc_attr( Follower_Class::g()->tradThisDay( $key_d ) . ' ' . Follower_Class::g()->tradThisPeriod( $key_p ) ); ?>
							</span>
						</div>
						<div class="grid-4" data-type="<?php echo esc_attr( $key_d . '-' . $key_p ); ?>" style="display : block">
							<?php foreach( $period as $setting ):
									if( $setting[ 'status' ] == "publish" ):
										$color = 'green';
									elseif( $setting[ 'status' ] == "archive" ):
										$color = 'orange';
									else:
										$color = 'red';
									endif;	?>
								<?php if( $setting[ 'status' ] != "draft" ): ?>
								<div style="color: <?php echo esc_attr( $color ); ?>">
									<?php echo esc_attr( $setting[ 'name' ] . ' : ' . $setting[ 'work_from' ] . ' -> ' . $setting[ 'work_to' ] ); ?>
									| <?php echo esc_attr( date( 'd-m-Y', $setting[ 'day_start' ] ) . ' ( ' . $setting[ 'status' ] . ' )' ); ?>
									<?php if( isset( $setting[ 'day_delete' ] ) ): ?>
										<?php echo esc_attr( 'delete -> ' . date( 'd-m-Y', $setting[ 'day_delete' ] ) ); ?>
									<?php endif; ?>
								</div>
								<?php else: ?>
									<div style="color: grey">
										<?php esc_html_e( 'No data for this day', 'task-manager' ); ?>
									</div>
								<?php endif; ?>
								<br />
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
			<a class="wpeo-button button-main button-uppercase modal-close"><span>Valider</span></a>
		</div>
	</div>
</div>
