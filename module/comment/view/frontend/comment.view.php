<?php
/**
 * Un commentaire dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package comment
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<li class="comment view">
	<ul class="wpeo-comment-container">
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->author_id . '" size="20"]' ); ?></li>
		<li class="wpeo-comment-date"><?php echo esc_html( mysql2date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $comment->date, true ) ); ?></li>
		<li class="wpeo-comment-time"><span class="fa fa-clock-o"></span> <?php echo esc_html( $comment->time_info['elapsed'] ); ?></li>
		<li class="wpeo-comment-content"><?php echo esc_html( $comment->content ); ?></li>
	</ul>
</li>
