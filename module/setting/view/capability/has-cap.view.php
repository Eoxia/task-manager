<?php
/**
 * Affiches les rôles qui ont les capacités "manage_task_manager".
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Evarisk
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $role_subscriber->capabilities['manage_task_manager'] ) ) :
	?>
	<p class="red"><?php esc_html_e( 'The "manage_task_manager" capability is applied to all users whse role is subsribed. You must delete the "manage_task_manager" ability on this one to be able to manually manage this right per user', 'task-manager' ); ?></p>
	<?php
endif;
