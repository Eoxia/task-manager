<?php
/**
 * Template pour l'affichage d'onglets dans le tableau de bord des tâches
 *
 * @package Task Manager
 * @subpackage Module/Task
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="wpeo-button wpeo-button-all-task action-attribute" data-action="load_all_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_archived_task' ) ); ?>"><?php esc_html_e( 'All tasks', 'task-manager' ); ?></li>
