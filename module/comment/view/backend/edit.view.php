<?php
/**
 * Edition d'un commentaire dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.8.0
 * @copyright 2018 Eoxia.
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="comment new">
	<input type="hidden" name="comment_id" value="<?php echo esc_attr( $comment->data['id'] ); ?>" />
	<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />
	
	<div>
		<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="20"]' ); ?>
	</div>
	
	<div class="wpeo-gridlayout grid-3">
		<div class="gridw-2">
			<input type="hidden" name="content" value="<?php echo esc_attr( $comment->data['content'] ); ?>" />
			<div contenteditable="true" class="content"></div>
			<?php if ( empty( $comment->data['id'] ) ) : ?>
				<span class="placeholder"><?php esc_html_e( 'Your comment here...', 'task-manager' ); ?></span>
			<?php endif; ?>
		</div>
		
		<div class="fa-layers fa-fw save-icon wpeo-util-hidden">
			<i class="fas fa-circle"></i>
			<i class="fa-inverse fas fa-save" data-fa-transform="shrink-6"></i>
		</div>
	
		<div class="group-date wpeo-util-hidden">
			<input type="hidden" class="mysql-date" name="mysql_date" value="<?php echo current_time( 'mysql' ); ?>" />
			<input type="text" class="date" value="<?php echo current_time( 'd/m/Y' ); ?>" />
		</div>
		
		<div>
			<input type="text" name="time" class="wpeo-util-hidden" />
		</div>
		
	
	</div>
</li>
