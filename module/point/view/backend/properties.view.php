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
		<?php esc_html_e( 'Créateur du point', 'task-manager' ); ?>
		<div class="user" style="width: 32px; height: 32px;">
			<img class="avatar avatar-32" src="<?php echo esc_attr( get_avatar_url( $point->author->id, array(
				'size' => 32,
				'default' => 'blank',
			)	) ); ?>" />
			<div class="wpeo-avatar-initial"><span><?php echo esc_html( $point->author->initial ); ?></span></div>
		</div>
		<?php echo esc_html( $point->author->display_name ); ?>
	</li>
	<li>
		<?php esc_html_e( 'Date de création', 'task-manager' ); ?>
		<?php echo $point->date; ?>
	</li>
</ul>

<form class="form" action="<?php esc_attr( admin_url( 'admin-ajax' ) ); ?>" method="POST">

	<input type="hidden" name="task_id" value="<?php echo esc_attr( $point->post_id ); ?>" />
	<input type="hidden" name="point_id" value="<?php echo esc_attr( $point->id ); ?>" />
	<input type="hidden" name="action" value="move_point_to" />
	<?php wp_nonce_field( 'move_point_to' ); ?>

	<label for="move_task"><?php esc_html_e( 'Move the point to', 'task-manager' ); ?></label>
	<input type="text" class="search-task" />
	<input type="hidden" name="to_task_id" />
	<input type="button" class="action-input" data-parent="form" value="<?php esc_html_e( 'Move', 'task-manager' ); ?>" />
	<div class="list-tasks">
	</div>
</form>
