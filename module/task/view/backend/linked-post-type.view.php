<?php
/**
 * Vue des informations supplémentaire dans le sommaire des tâches.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   TaskManager\Templates
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<?php if( ! empty( $task->data['parent_id'] ) ): ?>
	<ul class="wpeo-ul-parent">
		<li class="wpeo-task-parent">
			<span class="wpeo-task-link">
				<i class="far fa-link"></i>
			</span>
			<a class="wpeo-tooltip-event"
				aria-label="<?php echo esc_attr( $task->data['parent']->post_title ); ?>"
				target="_blank" href="<?php echo admin_url( 'post.php?post=' . $task->data['parent_id'] . '&action=edit' ); ?>">
				<?php echo esc_html( $task->data['parent']->displayed_post_title ); ?>
			</a>
		</li>
		<li style="float: right; margin-top: -28px; cursor : pointer">
			<span class="wpeo-task-link tm-task-delink-parent" data-id="<?php echo esc_html( $task->data[ 'id' ] ); ?>">
				<i class="fas fa-unlink"></i>
			</span>
		</li>
	</ul>

<?php else: ?>
	<ul class="wpeo-ul-parent wpeo-tag-wrap">
		<li class="wpeo-task-parent-add" style="display : none">
			<span class="wpeo-tag">
				<i class="fas fa-search"></i>
				<input type="hidden" class="task_search-taxonomy" name="taxonomy_id"/>
				<input type="text" class="task-search-taxonomy ui-autocomplete-input" placeholder="<?php echo esc_html( 'Link taxonomy', 'task-manager'); ?>" autocomplete="off" />
			</span>
		</li>
		<li class="wpeo-tag add_parent_to_task wpeo-button button-grey button-radius-3"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_all_task_parent_data' ) ); ?>"
		data-request_send="false"
		data-id="<?php echo esc_html( $task->data[ 'id' ] ) ?>">
			<i class="far fa-link"></i>
			<i class="fas fa-plus"></i>
		</li>
	</ul>
<?php endif; ?>
