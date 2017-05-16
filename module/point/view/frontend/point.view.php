<?php
/**
 * La vue d'un point dans le frontend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="point <?php echo ! empty( $point->id ) ? esc_attr( 'edit' ): ''; ?>" data-id="<?php echo esc_attr( $point->id ); ?>">
	<ul class="point-container">
		<li class="point-toggle">
			<?php if ( ! empty( $point->id ) ) : ?>
				<span data-action="<?php echo esc_attr( 'load_front_comments' ); ?>"
							data-task-id="<?php echo esc_attr( $parent_id ); ?>"
							data-point-id="<?php echo esc_attr( $point->id ); ?>"
							data-module="frontendSupport"
							data-before-method="beforeLoadComments"
							class="animated dashicons dashicons-arrow-right-alt2 action-attribute"></span>

				<span class="wpeo-block-id">#<?php echo esc_attr( $point->id ); ?></span>
			<?php endif; ?>
		</li>

		<li class="point-content content">
			<span><?php echo $point->content; ?></span>
			(<?php echo esc_html( $point->count_comments ); ?>)
		</li>

		<li class="point-action">
			<div class="wpeo-point-time">
				<span class="dashicons dashicons-clock"></span>
				<span class="wpeo-time-in-point"><?php echo esc_attr( $point->time_info['elapsed'] ); ?></span>
			</div>
		</li>
	</ul>

	<ul class="comments hidden" data-id="<?php echo esc_attr( $point->id ); ?>"></ul>
</div>
