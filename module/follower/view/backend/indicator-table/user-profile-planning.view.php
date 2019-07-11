<?php
/**
 * Options dans le profil utilisateur.
 *
 * @since 1.8.0
 * @version 1.8.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<table class="form-table planninguser">
	<tbody>
		<tr>
			<th><label for="_tm_define_planning"><?php esc_html_e( 'Planning TIME (minute)', 'task-manager' ); ?></label></th>
			<td>
				<?php
					\eoxia\View_Util::exec(
						'task-manager',
						'follower',
						'backend/indicator-table/user-list-contract',
						array(
							'contracts' => $contracts
						)
					);
				?>

				<?php
				/*
					\eoxia\View_Util::exec(
						'task-manager',
						'follower',
						'backend/indicator-table/information-add-row-table',
						array()
					);
					*/
				?>

			</td>
		</tr>
	</tbody>
</table>
