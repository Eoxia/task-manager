<?php
/**
 * La vue pour afficher mes tâches dans le frontend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="list-task">
	<div class="grid-col grid-col--1"></div>
	<div class="grid-col grid-col--2"></div>

	<?php if ( ! empty( $tasks ) && ! empty( $tasks[0] ) ) : ?>
			<?php
			foreach ( $tasks as $task ) :
				\eoxia\View_Util::exec(
					'task-manager',
					'task',
					'frontend/task',
					array(
						'task' => $task,
					)
				);
			endforeach;
			?>
	<?php endif; ?>
</div>
