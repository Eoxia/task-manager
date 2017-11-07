<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wrap wpeo-project-wrap">
	<div class="wpeo-project-dashboard">
		<p class="alignright"><?php esc_html_e( 'Total time past', 'task-manager' ); ?> : <?php echo esc_html( $total_time_elapsed ); ?> / <?php echo esc_html( $total_time_estimated ); ?></p>
		<span class="open-popup-ajax dashicons dashicons-screenoptions alignright"
					data-parent="wpeo-project-wrap"
					data-target="popup"
					data-action="load_last_activity"
					data-class="last-activity activities"
					data-tasks-id="<?php echo esc_attr( $task_ids_for_history ); ?>"
					data-title="<?php echo esc_attr_e( 'Last activities', 'task-manager' ); ?>"></span>

		<div class="popup">
			<div class="container">
				<div class="header">
					<h2 class="title"></h2>
					<i class="close fa fa-times"></i>
				</div>
				<input type="hidden" class="offset-event" value="<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page ); ?>" />
				<input type="hidden" class="last-date" value="" />

				<div class="content"></div>

				<button class="load-more-history"><?php esc_html_e( 'Load more', 'task-manager' ); ?></button> <!-- Ne pas supprimer 'load-more-history' -->
			</div>
		</div>
	</div>


	<?php
	if ( ! empty( $tasks ) ) :
		foreach ( $tasks as $key => $data ) :
			?>
			<?php if ( ! empty( $data['title'] ) ) : ?><hr/><h2><?php echo esc_html( $data['title'] ); ?></h2><?php endif; ?>
			<div class="list-task">
				<?php \task_manager\Task_Class::g()->display_tasks( $data['data'] ); ?>
			</div>
			<?php
		endforeach;
	endif;
	?>
</div>
