<?php
/**
 * Ajoutes un bouton "Accéder au profile client" et "Accéder à la tâche".
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.3.0
 * @version 1.3.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<hr />
<p><?php esc_html_e( 'This message below is only visible by you', 'task-manager' ); ?></p>

<h2>
	<?php esc_html_e( 'You have been notified by', 'task-manager' ); ?>&nbsp;
	<span><?php echo esc_html( $current_user->user_email ); ?></span>&nbsp;
	<?php esc_html_e( 'for the task', 'task-manager' ); ?>&nbsp;
	<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $task->data['id'] ) ); ?>">#<?php echo esc_html( $task->data['id'] ); ?></a>&nbsp;
	<?php esc_html_e( 'for the customer', 'task-manager' ); ?>&nbsp;
	<a href="<?php echo esc_attr( admin_url( 'post.php?action=edit&post=' . $task->data['parent_id'] ) ); ?>"><?php echo esc_html( $task->data['title'] ); ?></a>
</h2>

<p><?php esc_html_e( 'This message above is only visible by you', 'task-manager' ); ?></p>
<hr />
