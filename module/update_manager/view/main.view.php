<?php
/**
 * La vue principale pour les mises à jour.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-wrap wpeo-project-wrap">
	<input type="hidden" class="user-id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
	<input type="hidden" name="action_when_update_finished" value="tm_redirect_to_dashboard" />

	<div class="wpeo-project-update-manager">
		<!-- <h1><?php esc_html_e( 'Update Manager', 'task-manager' ); ?></h1> -->

<?php if ( ! empty( $waiting_updates ) ) : ?>
		<div class="notice notice-info" >
			<?php esc_html_e( 'Be careful, before using this data update manager, please back up your datas', 'task-manager' ); ?></br>
			<?php esc_html_e( 'You may loose data if you quit this page until the update is in progress', 'task-manager' ); ?>
		</div>

	<?php foreach ( $waiting_updates as $version => $data ) : ?>
		<h2><?php /* Translators: %s represent current version number. */ echo esc_html( sprintf( __( 'List of updates for version %s', 'task-manager' ), $version ) ); ?></h2>

		<div class="wpeo-grid grid-3" >
			<?php foreach ( $data as $index => $def ) : ?>
			<div>
				<div class="wpeo-update-item wpeo-update-waiting-item" id="wpeo-upate-item-<?php echo esc_attr( $def['update_index'] ); ?>" >
					<div>
						<span class="spinner" ></span>
						<i class="dashicons" ></i>
					</div>
					<div>
						<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
							<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( $def['action'] ) ); ?>" />
							<input type="hidden" name="action" value="<?php echo esc_attr( $def['action'] ); ?>" />
							<div class="wpeo-update-item-description" >
								<?php echo esc_attr( $def['title'] ); ?>
								<p><?php echo esc_attr( $def['description'] ); ?></p>
							</div>
							<div class="wpeo-update-item-result" >
								<?php
								if ( isset( $def['count_callback'] ) && ! empty( $def['count_callback'] ) ) :
									$total_number = call_user_func( $def['count_callback'] );
								?>
								<input type="hidden" name="total_number" value="<?php echo esc_attr( $total_number ); ?>" />
								<div class="wpeo-update-item-progress" >
									<div class="wpeo-update-item-progression" >&nbsp;</div>
									<div class="wpeo-update-item-stats" ><span class="wpeo-update-item-done-elements" >0</span> / <?php echo esc_html( $total_number ); ?></div>
								</div>
								<?php endif; ?>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
<?php else : ?>
		<?php esc_html_e( 'No updates available for current version', 'task-manager' ); ?>
		<strong><a href="<?php echo esc_attr( admin_url( '?page=wpeomtm-dashboard' ) ); ?>"><?php echo esc_html_e( 'Back to main application', 'task-manager' ); ?></a></strong>
<?php endif; ?>
	</div>
</div>
