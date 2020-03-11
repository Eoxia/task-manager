<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm-comment">
	<div class="comment-content">
		<?php echo $comment->data['content']; ?>
	</div>
	<div class="comment-footer">
		<div class="comment-author">
			<?php echo get_avatar( $comment->data['author_id'], 30 ); ?>
			<span class="author-name"><?php echo esc_html(  $comment->data['author_nicename'] ); ?>, le <?php echo esc_html( $comment->data['date']['rendered']['date_human_readable'] ); ?></span>
		</div>
		<div class="comment-time"><i class="far fa-clock"></i> <?php echo $comment->data['time_info']['elapsed']; ?></div>
	</div>
</div>
