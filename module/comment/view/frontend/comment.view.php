<?php
/**
 * Un commentaire dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<li class="comment view">

	<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="40"]' ); ?>

	<div class="comment-container">

		<div class="comment-content">
			<div class="comment-content-text">
				<?php echo nl2br( $comment->data['rendered'] ); ?>
			</div>

			<?php echo apply_filters( 'tm_comment_advanced_view', '', $comment ); ?>
		</div><!-- .comment-content -->
	</div>
</li>
