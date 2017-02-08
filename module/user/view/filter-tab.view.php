<?php
/**
 * Template pour l'affichage d'onglets dans le tableau de bord des tâches
 *
 * @package Task Manager
 * @subpackage Module/User
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="wpeo-button wpeo-button-my-task wpeo-button-active action-attribute" data-action="load_my_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_archived_task' ) ); ?>"><?php esc_html_e( 'My tasks', 'task-manager' ); ?></li>
<li class="wpeo-button wpeo-button-assigned-task action-attribute" data-action="load_affected_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_archived_task' ) ); ?>"><?php esc_html_e( 'Affected tasks', 'task-manager' ); ?></li>
