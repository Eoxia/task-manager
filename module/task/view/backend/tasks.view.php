<?php
/**
 * Parcours toutes les tâches et appel la vue "task".
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

<?php
if ( ! empty( $tasks ) ) :
	foreach ( $tasks as $task ) :
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task', array(
			'task' => $task,
			'with_wrapper' => $with_wrapper,
		) );
	endforeach;
endif;
?>
