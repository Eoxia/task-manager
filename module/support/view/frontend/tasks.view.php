<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $project->uncompleted_tasks ) ) :
	foreach ( $project->uncompleted_tasks as $task ) :
		View_Util::exec(
			'task-manager',
			'support',
			'frontend/task',
			array(
				'project'     => $project,
				'task'        => $task,
				'current_url' => $current_url,
			)
		);
	endforeach;
elseif ( ! empty( $project->completed_tasks ) ) : ?>
	<div class="tm-task-archived">
		<?php
		foreach ( $project->completed_tasks as $task ) :
			View_Util::exec(
				'task-manager',
				'support',
				'frontend/task',
				array(
					'project'     => $project,
					'task'        => $task,
					'current_url' => $current_url,
				)
			);
		endforeach;
		?>
	</div>
<?php endif; ?>
