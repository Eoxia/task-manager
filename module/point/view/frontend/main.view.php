<?php
/**
 * La vue principale des points dans le frontend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="points sortable">
	<?php
	if ( ! empty( $points_uncompleted ) ) :
		foreach ( $points_uncompleted as $point ) :
			\eoxia\View_Util::exec(
				'task-manager',
				'point',
				'frontend/point',
				array(
					'comment_id' => $comment_id,
					'point'      => $point,
					'parent_id'  => $point->data['post_id'],
				)
			);
		endforeach;
	endif;
	?>
</div>
