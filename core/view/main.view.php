<?php
/**
 * La vue principale de la page "wpeomtm-dashboard"
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-wrap tm-wrap">
	<input type="hidden" class="user-id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
	<div class="tm-dashboard">
		<?php echo apply_filters( 'tm_dashboard_subheader', '', $search_args ); // WPCS: XSS ok. ?>
		<?php echo apply_filters( 'tm_dashboard_header', '', $search_args ); // WPCS: XSS ok. ?>
	</div>

	<div class="tm-dashboard-wrap tm-main-container">
		<div class="tm-dashboard-primary">
			<?php
			$waiting_updates = get_option( '_tm_waited_updates', array() );
			if ( ! empty( $waiting_updates ) && strpos( $_SERVER['REQUEST_URI'], 'admin.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=' . \eoxia\Config_Util::$init['task-manager']->update_page_url ) ) :
				\eoxia\Update_Manager_Class::g()->display_say_to_update( 'task-manager', __( 'Need to update Task Manager data', 'task-manager' ) );
			else :
				$shortcode_final_args = '';
				foreach ( $search_args as $shortcode_params_key => $shortcode_params_value ) {
					$shortcode_final_args .= $shortcode_params_key . '="' . $shortcode_params_value . '" ';
				}
				echo do_shortcode( '[task ' . $shortcode_final_args . ']' );
			endif;
			?>
		</div>

		<?php /*if ( $user->data['_tm_display_indicator'] ) : */?><!--
			<div class="tm-dashboard-secondary">
				<?php
/*				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
				do_meta_boxes( 'wpeomtm-dashboard', 'normal', '' );
				*/?>
			</div>
		--><?php /*endif; */?>
	</div>
</div>
