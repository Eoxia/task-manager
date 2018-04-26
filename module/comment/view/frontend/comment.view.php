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
	<ul class="wpeo-comment-container">
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="20"]' ); ?></li>
		<li class="wpeo-comment-date"><?php echo esc_html( $comment->data['date']['rendered']['date_human_readable'] ); ?></li>
		<li class="wpeo-comment-time"><span class="dashicons dashicons-clock"></span> <?php echo esc_html( $comment->data['time_info']['elapsed'] ); ?></li>
		<li class="wpeo-comment-content"><?php echo nl2br( $comment->data['content'] ); ?></li>
	</ul>
</li>
