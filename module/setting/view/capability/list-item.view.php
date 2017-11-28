<?php
/**
 * Affichage de la liste des utilisateurs pour affecter les capacités
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
} ?>

<tr class="user-row">
	<td><div class="avatar" style="background-color: #<?php echo esc_attr( $user->avatar_color ); ?>;"><span><?php echo esc_html( $user->initial ); ?></span></div></td>
	<td class="padding"><span><strong><?php echo esc_html( \eoxia\User_Class::g()->element_prefix . $user->id ); ?><strong></span></td>
	<td class="padding"><span><?php echo esc_html( $user->email ); ?></span></td>
	<td class="padding"><span><?php echo esc_html( implode( ', ', $user->wordpress_user->roles ) ); ?></span></td>
	<td>
		<input <?php echo ( $has_capacity_in_role ) ? 'disabled' : ''; ?> <?php echo ( $user->wordpress_user->has_cap( 'manage_task_manager' ) ) ? 'checked' : ''; ?> name="users[<?php echo esc_attr( $user->id ); ?>][capability]" id="have_capability_<?php echo esc_attr( $user->id ); ?>" type="checkbox" /><label for="have_capability_<?php echo esc_attr( $user->id ); ?>"><?php esc_html_e( 'Droit à Task Manager', 'task-manager' ); ?></label>
	</td>
</tr>
