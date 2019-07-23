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

<div class="table-row tm-table-edit">
	<?php
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/user-profile-planning-only',
			array(
				'planning' => $planning,
				'periods'  => $periods,
				'edit'     => $edit,
				'days'     => $days,
				'userid'  => $userid
			)
		);
	?>
</div>
