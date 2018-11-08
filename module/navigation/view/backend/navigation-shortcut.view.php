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

<ul>
	<?php
	if ( ! empty( $shortcuts ) ) :
		foreach ( $shortcuts as $shortcut ) :
			?>
			<li><a href="<?php echo admin_url( $shortcut['page'] . $shortcut['link'] ); ?>"><?php echo esc_html( $shortcut['label'] ); ?></li>
			<?php
		endforeach;
	endif;
	
	echo apply_filters( 'task_manager_navigation_after', '' ); // WPCS: XSS ok. ?>
</ul>
