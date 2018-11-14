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
	<li class="dashboard-shortcut shortcut-title"><?php esc_html_e( 'Shortcuts', 'task-manager' ); ?></li>
	<?php
	if ( ! empty( $shortcuts ) ) :
		foreach ( $shortcuts as $shortcut ) :
			$active = '';

			if ( $shortcut['link'] == $url ) :
				$active = ' active ';
			endif;
			?>
			<li class="dashboard-shortcut <?php echo esc_attr( $active ); ?>">
				<a class="wpeo-button button-size-small button-transparent" href="<?php echo admin_url( $shortcut['page'] . $shortcut['link'] ); ?>">
					<?php echo esc_html( $shortcut['label'] ); ?>
				</a>
			</li>
			<?php
		endforeach;
	endif;

	echo apply_filters( 'task_manager_navigation_after', '' ); // WPCS: XSS ok. ?>
</ul>
