<?php
/**
 * La vue principale des points dans le backend.
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

<?php
if ( ! empty( $points ) ) :
	foreach ( $points as $point ) :
		\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
			'point' => $point,
			'task'  => $args['task'],
		) );
	endforeach;
else:
endif;
?>
