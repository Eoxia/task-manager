<?php
/**
 * Les propriétés d'un point.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul>
	<li>
		<?php esc_html_e( 'Creator of the point', 'task-manager' ); ?>
		<?php echo do_shortcode( '[task_avatar ids="' . $point->author_id . '" size="50"]' ); ?>
	</li>
	<li>
		<?php esc_html_e( 'Creation date', 'task-manager' ); ?>
		<?php echo esc_html( $point->date ); ?>
	</li>
</ul>

<div class="form">
	<input type="hidden" name="task_id" value="<?php echo esc_attr( $point->post_id ); ?>" />
	<input type="hidden" name="point_id" value="<?php echo esc_attr( $point->id ); ?>" />
	<input type="hidden" name="action" value="move_point_to" />
	<?php wp_nonce_field( 'move_point_to' ); ?>

	<label for="move_task"><?php esc_html_e( 'Move the point to', 'task-manager' ); ?></label>
	<input type="text" class="search-task" />
	<input type="hidden" name="to_task_id" />
	<input type="button" class="action-input" data-parent="form" value="<?php esc_html_e( 'Move', 'task-manager' ); ?>" />
	<div class="list-tasks"></div>
</div>
