<?php
/**
 * Affichage de la liste des utilisateurs pour affecter les capacités
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
} ?>

<tr class="user-row">
	<td><?php echo do_shortcode( '[task_avatar ids="' . $user->data['id'] . '"]' ); ?></td>
	<td class="padding"><span><strong><?php echo esc_html( \eoxia\User_Class::g()->element_prefix . $user->data['id'] ); ?><strong></span></td>
	<td class="padding"><span><?php echo esc_html( $user->data['email'] ); ?></span></td>
	<td class="padding"><span><?php echo esc_html( implode( ', ', $user->wordpress_user->roles ) ); ?></span></td>
	<td>
		<input <?php echo ( $has_capacity_in_role ) ? 'disabled' : ''; ?> <?php echo ( $user->wordpress_user->has_cap( 'manage_task_manager' ) ) ? 'checked' : ''; ?> name="users[<?php echo esc_attr( $user->data['id'] ); ?>][capability]" id="have_capability_<?php echo esc_attr( $user->data['id'] ); ?>" type="checkbox" /><label for="have_capability_<?php echo esc_attr( $user->data['id'] ); ?>"><?php esc_html_e( 'Right for Task Manager', 'task-manager' ); ?></label>
	</td>
</tr>
