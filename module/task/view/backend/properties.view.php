<?php
/**
 * Les propriétés d'une tâche.
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

<ul>
	<li>
		<?php esc_html_e( 'Créateur de la tâche', 'task-manager' ); ?>
		<?php echo do_shortcode( '[task_avatar ids="' . $task->author_id . '" size="50"]' ); ?>
	</li>
	<li>
		<?php esc_html_e( 'Date de création', 'task-manager' ); ?>
		<?php echo esc_html( $task->date ); ?>
	</li>
</ul>


<div class="form">
	<input type="hidden" name="task_id" value="<?php echo esc_attr( $task->id ); ?>" />
	<input type="hidden" name="action" value="move_task_to" />
	<?php wp_nonce_field( 'move_task_to' ); ?>

	<label for="move_task"><?php esc_html_e( 'Déplacer la tâche vers', 'task-manager' ); ?></label>
	<input type="text" class="search-parent" />
	<input type="hidden" name="to_element_id" />
	<input type="button" class="action-input" data-loader="form" data-parent="form" value="<?php esc_html_e( 'Déplacer', 'task-manager' ); ?>" />
	<div class="list-posts">
	</div>
</div>
