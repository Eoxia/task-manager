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
} ?><ul>
	<li class="action-input"
		data-before=
		data-filter-args="follower_id_selected=<?php echo esc_attr( get_current_user_id() ); ?>"
		data-action="search"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_my_task' ) ); ?>"><?php esc_html_e( 'My task', 'task-manager' ); ?></li>

	<?php echo apply_filters( 'task_manager_navigation_after', '' ); // WPCS: XSS ok. ?>
</ul>
