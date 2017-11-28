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
	<p class="red"><?php esc_html_e( 'La capacité "manage_task_manager" est appliqué sur tous les utilisateurs dont le rôle est abonnés. Vous devez supprimer la capacité "manage_task_manager" sur celui-ci pour pouvoir gérer manuellement ce droit par utilisateur', 'task-manager' ); ?></p>
	<?php
endif;
