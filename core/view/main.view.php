<?php
/**
 * La vue principale de la page "wpeomtm-dashboard"
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wrap wpeo-project-wrap">
	<input type="hidden" class="user-id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />

	<div class="wpeo-project-dashboard">
		<h2>
			<?php	esc_html_e( 'Tasks Manager', 'task-manager' ); ?>
			<a 	href="#"
					class="action-attribute add-new-h2"
					data-action="create_task"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'New task', 'task-manager' ); ?></a>
		</h2>
	</div>

	<?php do_shortcode( '[task_manager_search_bar term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '"]' ); ?>
	<?php do_shortcode( '[task term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '" post_parent="0" with_wrapper="0"]' ); ?>
</div>
