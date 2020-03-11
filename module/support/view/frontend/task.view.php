<?php
/**
 * Le contenu de la tâche demande du module support de Task Manager.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 3.0.1
 * @version 3.0.1
 * @copyright 2015-2020 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<a class="tm-task" href="<?php echo esc_attr( $current_url . '&project_id=' . $project->data['id'] . '&task_id=' . $task->data['id'] ); ?>">
	<div class="task-header">
		<span class="header-id"><i class="fas fa-hashtag"></i> <?php echo esc_attr( $task->data['id'] ); ?></span>
		<span class="header-time"><i class="far fa-clock"> <?php echo esc_attr( $task->data['time_info']['elapsed'] ); ?></i></span>
	</div>
	<div class="task-content">
		<?php echo esc_attr( $task->data['content'] ); ?>
	</div>
</a>
