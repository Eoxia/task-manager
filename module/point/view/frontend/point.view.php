<?php
/**
 * La vue d'un point dans le frontend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="point <?php echo ! empty( $point->data['id'] ) ? esc_attr( 'edit' ) : ''; ?>"
	data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
	data-point-state="<?php echo esc_attr( ! empty( $point->data['completed'] ) ? 'completed' : 'uncompleted' ); ?>" >

	<ul class="point-container">
		<li class="point-content content">

			<div class="wpeo-point-new-contenteditable" contenteditable="false"><?php echo trim( $point->data['content'] ); ?></div>

			<ul class="wpeo-point-summary">
				<li class="wpeo-block-id"><i class="fas fa-hashtag"></i> <?php echo esc_attr( $point->data['id'] ); ?></li>
				<li class="wpeo-point-time">
					<i class="fas fa-clock"></i>
					<span class="wpeo-time-in-point"><?php echo esc_attr( $point->data['time_info']['elapsed'] ); ?></span>
				</li>
				<li>
					<i class="fas fa-comment-dots"></i>
					<?php echo esc_html( $point->data['count_comments'] ); ?>
				</li>
			</ul>

		</li>
	</ul>

	<ul class="comments" style="display: none;" data-id="<?php echo esc_attr( $point->data['id'] ); ?>"></ul>
</div>
