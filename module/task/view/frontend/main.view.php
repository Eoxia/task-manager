<?php
/**
 * La vue principale des tâches dans le backend.
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

<div class="tm-wrap">
	<?php
	\eoxia\View_Util::exec(
		'task-manager',
		'task',
		'frontend/tasks',
		array(
			'tasks' => $tasks,
		)
	);
	?>
</div>
