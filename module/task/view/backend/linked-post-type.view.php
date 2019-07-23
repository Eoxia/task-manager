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

<?php // echo '<pre>'; print_r( $task->data['parent'] ); echo '</pre>'; exit; ?>
<?php if( ! empty( $task->data['parent_id'] ) && ! empty( $task->data[ 'parent' ] ) ):
	if( $task->data[ 'parent' ]->post_type == "digi-risk" ):
			$view = 'digirisk';
		else:
			$view = 'default';
		endif;

		 \eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/parent-item-' . $view,
			array(
				'task' => $task,
			)
		);

	 else: ?>

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
		data-id="<?php echo esc_html( $task->data[ 'id' ] ) ?>"
		style="font-size: initial;">
			<i class="fas fa-link"></i>
			<i class="fas fa-plus"></i>
		</li>
	</ul>
<?php endif; ?>
