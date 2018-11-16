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
} ?>

<div class="wpeo-task-header">
	<div class="wpeo-task-main-header">
		<div class="wpeo-task-author"><?php echo do_shortcode( '[task_manager_owner_task task_id="' . $task->data['id'] . '" owner_id="' . $task->data['user_info']['owner_id'] . '"]' ); ?></div>

		<div class="wpeo-task-main-info" >
			<div class="wpeo-task-title">
				<div contenteditable="true" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title' ) ); ?>" class="wpeo-project-task-title"><?php echo trim( $task->data['title'] ); ?></div>
			</div>
			<ul class="wpeo-task-summary" >
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
