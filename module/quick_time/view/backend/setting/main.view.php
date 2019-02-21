<?php
/**
 * La fenêtre de configuration des ajouts de temps rapides.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="list">
	<?php \eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/setting/form', array() ); ?>

	<?php
	if ( ! empty( $quicktimes ) ) :
		foreach ( $quicktimes as $key => $quick_time ) :
			\eoxia\View_Util::exec(
				'task-manager',
				'quick_time',
				'backend/setting/item',
				array(
					'key'        => $key,
					'quick_time' => $quick_time,
				)
			);
		endforeach;
	endif;
	?>
</div>
