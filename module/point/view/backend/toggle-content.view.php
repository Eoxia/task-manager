<?php
/**
 * Les propriétés d'un point.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.4.0-ford
 * @version 1.4.0-ford
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div>
	<div class="task-informations">
		<?php echo do_shortcode( '[task_avatar ids="' . $point->author_id . '" size="50"]' ); ?>
		<?php echo esc_html( $point->date ); ?>
	</div>
</div>

<ul class="actions">
	<li class="action-delete tooltip hover"
			aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
			data-action="delete_point"
			data-message-delete="<?php echo esc_attr( 'Delete this point ?', 'task-manager' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_point' ) ); ?>"
			data-id="<?php echo esc_attr( $point->id ); ?>">
		<span><i class="fa fa-trash"></i></span>
	</li>
</ul>

<div class="move-to">
	<div class="form">
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $point->id ); ?>" />
		<input type="hidden" name="action" value="move_task_to" />
		<?php wp_nonce_field( 'move_task_to' ); ?>

		<label for="move_task"><?php esc_html_e( 'Move the task to', 'task-manager' ); ?></label>
		<div class="form-fields">
			<input type="text" class="search-parent" />
			<input type="hidden" name="to_element_id" />
			<input type="button" class="action-input" data-loader="form" data-parent="form" value="<?php esc_html_e( 'OK', 'task-manager' ); ?>" />
		</div>
		<div class="list-posts">
		</div>
	</div>
</div>
