<?php
/**
 * La vue pour afficher mes tâches dans le frontend.
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
	<?php if ( ! empty( $tasks ) && ! empty( $tasks[0] ) ) : ?>
			<?php
			foreach ( $tasks as $task ) :
				\eoxia\View_Util::exec( 'task-manager', 'task', 'frontend/task', array(
					'task' => $task,
				) );
			endforeach;
			?>
	<?php endif; ?>
</div>
