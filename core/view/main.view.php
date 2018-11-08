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

	<div class="tm-dashboard-header">
		<div>
			<h2>
				<?php	esc_html_e( 'Tasks', 'task-manager' ); ?>
				<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'New task', 'task-manager' ); ?></a>
			</h2>
			<?php echo apply_filters( 'tm_dashboard_header', '', $search_args ); // WPCS: XSS ok. ?>
		</div>
		<div class="tm-dashboard-subheader">
			<?php echo apply_filters( 'tm_dashboard_subheader', '', $search_args ); // WPCS: XSS ok. ?>
		</div>
	</div>

	<div class="tm-dashboard-wrap">
		<div class="tm-dashboard-primary">
			<?php Navigation_Class::g()->display_search_result( 
				$search_args['term'],
				$search_args['status'],
				$search_args['task_id'],
				$search_args['point_id'],
				$search_args['post_parent'],
				$search_args['categories_id'],
				$search_args['users_id'] );

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
		<div class="tm-dashboard-secondary">
			Sidebar
		</div>
	</div>
</div>
