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

<?php if ( ! empty( $tasks ) && ! empty( $tasks[0] ) ) : ?>
	<div class="grid-item wpeo-project-task">
		<?php
		foreach ( $tasks as $task ) :
			View_Util::exec( 'task', 'frontend/task', array(
				'task' => $task,
			) );
		endforeach;
		?>
	</div>
<?php endif; ?>
