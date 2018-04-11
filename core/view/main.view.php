<?php
/**
 * La vue principale de la page "wpeomtm-dashboard"
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap wpeo-project-wrap">
	<input type="hidden" class="user-id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />

	<div class="wpeo-project-dashboard">
		<h2>
			<?php	esc_html_e( 'Task', 'task-manager' ); ?>
			<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'New task', 'task-manager' ); ?></a>
		</h2>
	</div>

	<?php echo do_shortcode( '[task_manager_search_bar term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '"]' ); ?>

	<?php
	$waiting_updates = get_option( '_tm_waited_updates', array() );
	if ( ! empty( $waiting_updates ) && strpos( $_SERVER['REQUEST_URI'], 'admin.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=' . \eoxia\Config_Util::$init['task-manager']->update_page_url ) ) :
		\eoxia\Update_Manager_Class::g()->display_say_to_update( 'task-manager', __( 'Need to update Task Manager data', 'task-manager' ) );
	else :
		if ( ! empty( $id ) ) :
			echo do_shortcode( '[task id="' . $id . '"]' );
		else :
			echo do_shortcode( '[task term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '" status="any" post_parent="0" with_wrapper="0"]' );
		endif;
	endif;

	?>
</div>
