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

<li class="comment">
	<ul>
		<li><?php echo esc_html( $comment->date ); ?></li>
		<li><?php echo esc_html( $comment->content ); ?></li>
		<li><?php echo esc_html( $comment->time_info['elapsed'] ); ?></li>
		<li>
			<span data-action="load_edit_view_comment"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_edit_view_comment' ) ); ?>"
				data-id="<?php echo esc_attr( $comment->id ); ?>"
				class="dashicons dashicons-edit action-attribute"></span>

			<span data-action="delete_comment"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_comment' ) ); ?>"
				data-id="<?php echo esc_attr( $comment->id ); ?>"
				class="dashicons dashicons-no action-delete"></span>
		</li>
	</ul>
</li>
