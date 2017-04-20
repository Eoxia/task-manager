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
		<li class="avatar">A</li>
		<li class="wpeo-comment-date"><?php echo esc_html( 'Nom de la personne' ) . ', ' . esc_html( $comment->date ); ?></li>
		<li class="wpeo-comment-time"><span class="fa fa-clock-o"></span> <?php echo esc_html( $comment->time_info['elapsed'] ); ?></li>
		<li class="wpeo-comment-action">
			<div class="toggle wpeo-comment-setting"
					data-parent="toggle"
					data-target="content">

				<div class="action">
					<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Comment options', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
				</div>

				<ul class="content point-header-action">
					<li class="open-popup-ajax"
							data-action="load_point_properties"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point_properties' ) ); ?>"
							data-id="<?php echo esc_attr( $comment->id ); ?>"
							data-parent="wpeo-project-task"
							data-target="popup">
						<span><?php esc_html_e( 'Point properties', 'task-manager' ); ?></span>
					</li>
				</ul>
			</div>
		</li>
		<li class="wpeo-comment-content"><?php echo esc_html( $comment->content ); ?></li>
	</ul>
</li>
