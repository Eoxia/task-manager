<?php
/**
 * The main view for the dropdown content.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-grid grid-4">
	<?php
	\eoxia\View_Util::exec(
		'task-manager',
		'owner',
		'backend/list',
		array(
			'users'   => $users,
			'task_id' => $task_id,
		)
	);
	?>
</div>
