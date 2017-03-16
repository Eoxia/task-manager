<?php
/**
 * La vue principale des tâches dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="list-task">
	<?php
	if ( ! empty( $tasks ) ) :
		foreach ( $tasks as $task ) :
			View_Util::exec( 'task', 'backend/task', array(
				'task' => $task,
			) );
		endforeach;
	endif;
	?>
</div>
