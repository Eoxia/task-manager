<?php
/**
 * Affichage des points en mode 'grille'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><ul class="post-last-activity" >
	<li class="wpeo-modal-event"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>"
		data-action="load_last_activity"
		data-class="last-activity activities"
		data-tasks-id="<?php echo esc_attr( $task_ids_for_history ); ?>"
		data-title="<?php echo esc_attr_e( 'Last activities', 'task-manager' ); ?>" >
		<i class="dashicons dashicons-screenoptions" ></i>
		<?php esc_html_e( 'View complete history', 'task-manager' ); ?>
	</li>
	<li class="activities" >
		<?php echo $last_activity; // WPCS: XSS ok. ?>
	</li>
</ul>
