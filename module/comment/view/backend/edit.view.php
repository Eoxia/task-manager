<?php
/**
 * Edition d'un commentaire le backend.
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

<li class="comment">
	<ul class="wpeo-comment-container">
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->author_id . '" size="16"]' ); ?></li>
		<li class="wpeo-comment-date"><span class="fa fa-calendar-o"></span> <input type="text" name="date" value="<?php echo esc_attr( $comment->date ); ?>" /></li>
		<li class="wpeo-comment-content"><input type="text" name="content" value="<?php echo esc_attr( $comment->content ); ?>"/></li>
		<li class="wpeo-comment-time"><span class="fa fa-clock-o"></span> <input type="text" name="time" value="<?php echo esc_attr( $comment->time_info['elapsed'] ); ?>" /></li>
		<li class="wpeo-save-point"><i data-parent="edit" class="action-input fa fa-floppy-o" aria-hidden="true"></i></li>
	</ul>
</li>
