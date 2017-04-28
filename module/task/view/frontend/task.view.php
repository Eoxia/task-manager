<?php
/**
 * La vue pour afficher une tâche dans le frontend.
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

<?php if ( ! empty( $task ) ) : ?>
	<div data-id="<?php echo esc_attr( $task->id ); ?>" class="task">
		<header>
			<h2><?php echo '#' . esc_html( $task->id . ' ' . $task->title ); ?></h2>
			<!-- Temps passé, temps estimé / Elapsed time, estimated time -->
			<div class="task-time">
				<span class="dashicons dashicons-clock"></span>
				<strong><?php echo esc_html( $task->time_info['elapsed'] ); ?></strong> /
				<strong><?php echo esc_html( $task->last_history_time->estimated_time ); ?></strong> min
			</div>
		</header>

		<?php Point_Class::g()->display( $task->id, true ); ?>


	</div>
<?php endif; ?>
