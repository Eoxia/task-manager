<?php
/**
 * La vue principale des points dans le backend.
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

<ul class="wpeo-task-point wpeo-task-point-sortable">
	<?php
	if ( ! empty( $points_uncompleted ) ) :
		foreach ( $points_uncompleted as $point ) :
			View_Util::exec( 'point', 'backend/point', array(
				'point' => $point,
				'parent_id' => $point->post_id,
			) );
		endforeach;
	endif;

	View_Util::exec( 'point', 'backend/point', array(
		'point' => $point_schema,
		'parent_id' => $task_id,
	) );
	?>
</ul>
