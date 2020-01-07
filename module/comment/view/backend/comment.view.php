<?php
/**
 * Un commentaire dans le backend.
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

<div class="table-column">
	<div class="table-row" data-id="<?php echo $comment->data['id']; ?>"
	     data-post-id="<?php echo esc_attr( $task_id ); ?>"
	     data-parent-id="<?php echo esc_attr( $point_id ); ?>"
	     data-nonce="<?php echo wp_create_nonce( 'edit_comment' ); ?>">
		<div class="table-cell">
			<div class="table-cell-container comment-title" contenteditable="true"><?php echo $comment->data['content']; ?></div>
		</div>

		<div class="table-cell"><?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="40"]' ); ?></div>
		<div class="table-cell">26/11/2019 10h12</div>
		<div class="table-cell">30</div>
		<div class="table-cell"><span><i class="fas fa-ellipsis-v"></i></span></div>
	</div>
</div>

