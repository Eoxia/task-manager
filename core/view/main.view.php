<?php
/**
 * La vue principale de la page "wpeomtm-dashboard"
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
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

	<span class="open-popup-ajax dashicons dashicons-screenoptions"
				data-parent="wpeo-project-wrap"
				data-target="last-activity"
				data-action="load_last_activity"
				data-namespace="taskManager"
				data-module="activity"
				data-before-method="getDataBeforeOpenPopup"
				data-title="<?php echo esc_attr( 'Last activities', 'task-manager' ); ?>"></span>

	<div class="popup last-activity activities">
		<div class="container">
			<div class="header">
				<h2 class="title">Titre de la popup</h2>
				<i class="close fa fa-times"></i>
			</div>
			<input type="hidden" class="offset-event" value="<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page ); ?>" />
			<input type="hidden" class="last-date" value="" />

			<div class="content">
			</div>

			<button class="load-more-history"><?php esc_html_e( 'Load more', 'task-manager' ); ?></button> <!-- Ne pas supprimer 'load-more-history' -->
		</div>
</div>


	<?php do_shortcode( '[task_manager_search_bar term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '"]' ); ?>


	<?php
	if ( ! empty( $id ) ) :
		do_shortcode( '[task id="' . $id . '"]' );
	else :
		do_shortcode( '[task term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '" status="any" post_parent="0" with_wrapper="0"]' );
	endif;
	?>
</div>
