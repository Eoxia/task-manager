<?php
/**
 * Options dans le profil utilisateur.
 *
 * @package Task Manager
 * @subpackage Module/Follower
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h2><?php esc_html_e( 'Task Manager settings', 'task-manager' ); ?></h2>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="_tm_auto_elapsed_time"><?php esc_html_e( 'Compil time automatically', 'task-manager' ); ?></label></th>
			<td>
				<input type="checkbox" name="_tm_auto_elapsed_time" id="_tm_auto_elapsed_time" value="1" <?php checked( $user->_tm_auto_elapsed_time, true, true ); ?>>
				<p class="description" ><?php esc_html_e( 'Get the time of last comment you enter and fill elapsed time from this time. (You don\'t need to make hard calcul to get your elapsed time ;) ', 'task-manager' ); ?></p>
			</td>
		</tr>
	</tbody>
</table>
