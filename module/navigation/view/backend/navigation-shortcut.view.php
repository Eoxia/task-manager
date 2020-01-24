<?php
/**
 * Vue pour afficher la barre de recherche.
 *
 * @since 1.8.0
 * @version 1.8.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="tm-dashboard-shortcuts">
	<li class="dashboard-shortcut shortcut-title"><i class="fas fa-star"></i></li>
	<?php
	if ( ! empty( $shortcuts ) ) :
		foreach ( $shortcuts as $key => $shortcut ) :
			\eoxia\View_Util::exec(
				'task-manager',
				'shortcut',
				'shortcut',
				array(
					'shortcut' => $shortcut,
					'url'      => $url,
					'new'      => false,
					'key'      => $key,
				)
			);
		endforeach;
	endif;
	?>
	<li class="wpeo-button button-transparent wpeo-modal-event handle-shortcut"
		data-class="modal-shortcut"
		data-action="load_handle_shortcut"><i class="button-icon fas fa-ellipsis-v"></i></li>

	<?php echo apply_filters( 'task_manager_navigation_after', '' ); // WPCS: XSS ok. ?>
</ul>
