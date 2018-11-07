<?php
/**
 * La vue du header d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Construction de l'affichage du temps passé.
$task_time_info                = $task->data['time_info']['elapsed'];
$task_time_info_human_readable = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'] );

// Construction de l'affichage du temps prévu.
if ( ! empty( $task->data['last_history_time']->data['estimated_time'] ) ) :
	$task_time_info                .= ' / ' . $task->data['last_history_time']->data['estimated_time'];
	$task_time_info_human_readable .= ' / ' . \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['last_history_time']->data['estimated_time'] );
endif;

?><div class="wpeo-task-header">
	<div class="wpeo-task-main-header">
		<div class="wpeo-task-author"><?php echo do_shortcode( '[task_manager_owner_task task_id="' . $task->data['id'] . '" owner_id="' . $task->data['user_info']['owner_id'] . '"]' ); ?></div>

		<div class="wpeo-task-main-info" >
			<div class="wpeo-task-title">
				<input type="text" name="task[title]" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title' ) ); ?>" class="wpeo-project-task-title" value="<?php echo esc_html( $task->data['title'] ); ?>" />
			</div>
			<ul class="wpeo-task-summary" >
				<li class="wpeo-task-id"><i class="far fa-hashtag"></i> <?php echo esc_html( $task->data['id'] ); ?></li>
				
				<li class="wpeo-task-time-history wpeo-modal-event"
						data-class="history-time wpeo-wrap tm-wrap"
						data-action="load_time_history"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_time_history' ) ); ?>"
						data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Time history', 'task-manager' ), $task->data['id'] ) ); ?>"
						data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">

					<?php if ( 0 !== $task->data['last_history_time']->data['id'] ) : ?>
						<?php if ( 'recursive' === $task->data['last_history_time']->data['custom'] ) : ?>
							<span><?php esc_html_e( 'Repeated', 'task-manager' ); ?>
						<?php else : ?>
							<span class="wpeo-task-date tooltip hover" aria-label="<?php echo esc_html_e( 'Dead line', 'task-manager' ); ?>">
								<i class="far fa-calendar-alt"></i>
								<span><?php echo esc_html( $task->data['last_history_time']->data['due_date']['rendered']['date'] ); ?></span>
							</span>
						<?php endif; ?>
					<?php endif; ?>

					<span class="wpeo-task-time-info wpeo-tooltip-event" aria-label="<?php echo esc_attr( $task_time_info_human_readable ); ?>">
						<i class="far fa-clock"></i>
						<span class="elapsed" ><?php echo esc_html( $task_time_info ); ?></span>
					</span>
				</li>
				
				<?php if ( $task->data['parent'] ) : ?>
					<li class="wpeo-task-parent">
						<i class="far fa-link"></i>
						<a target="_blank" href="<?php echo admin_url( 'post.php?post=' . $task->data['parent_id'] . '&action=edit' ); ?>">
							<?php echo esc_html( $task->data['parent']->post_title ); ?>
						</a>
					</li>
				<?php endif; ?>
				
				<?php echo apply_filters( 'tm_task_header_summary', '', $task ); // WPCS: XSS ok. ?>
			</ul>
		</div>

		<div class="wpeo-dropdown wpeo-task-setting" data-parent="toggle" data-target="content" data-mask="wpeo-project-task">
			<span class="wpeo-button button-transparent dropdown-toggle" ><i class="fa fa-ellipsis-v"></i></span>
			<div class="dropdown-content task-header-action"><?php \eoxia\View_Util::exec( 'task-manager', 'task', 'backend/toggle-content', array( 'task' => $task ) ); ?></div>
		</div>
	</div>

	<ul class="wpeo-task-filter" >
		<?php echo apply_filters( 'tm_task_header', '', $task ); // WPCS: XSS ok. ?>
	</ul>
</div>
