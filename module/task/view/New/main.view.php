<?php
/**
 * La vue principale qui s'occupe de la Navigation des EPI + Affichage du tableau et son contene.
 *
 * @package   TheEPI
 * @author    Jimmy Latour <jimmy@evarisk.com> && Nicolas Domenech <nicolas@eoxia.com>
 * @copyright 2019 Evarisk
 * @since     0.2.0
 * @version   0.7.0
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array  $tasks    Les données des Tâches.
 */
?>
<div class="wpeo-table table-flex table-projects">
	<div class="table-row table-header">
		<div class="table-cell table-25"></div>
		<div class="table-cell table-300" data-title="<?php esc_attr_e( 'Project Name', 'task-manager' ); ?>"><span><?php esc_html_e( 'Project Name', 'task-manager' ); ?></span></div>
		<div class="table-cell table-50" data-title="<?php esc_attr_e( 'ID', 'task-manager' ); ?>"><span><?php esc_html_e( 'ID', 'task-manager' ); ?></span></div>
		<div class="table-cell table-100" data-title="<?php esc_attr_e( 'Last Update', 'task-manager' ); ?>"><span><?php esc_html_e( 'Last Update', 'task-manager' ); ?></span></div>
		<div class="table-cell table-100" data-title="<?php esc_attr_e( 'Time', 'task-manager' ); ?>"><span><?php esc_html_e( 'Time', 'task-manager' ); ?></span></div>
		<div class="table-cell table-150" data-title="<?php esc_attr_e( 'Creation Date', 'task-manager' ); ?>"><?php esc_html_e( 'Creation Date', 'task-manager' ); ?></span></div>
		<div class="table-cell table-150" data-title="<?php esc_attr_e( 'End Date', 'task-manager' ); ?>"><span><?php esc_html_e( 'End Date', 'task-manager' ); ?></span></div>
		<div class="table-cell table-100" data-title="<?php esc_attr_e( 'Affiliated With', 'task-manager' ); ?>"><span><?php esc_html_e( 'Affiliated With', 'task-manager' ); ?></span></div>
		<div class="table-cell table-150" data-title="<?php esc_attr_e( 'Categories', 'task-manager' ); ?>"><span><?php esc_html_e( 'Categories', 'task-manager' ); ?></span></div>
		<div class="table-cell table-100" data-title="<?php esc_attr_e( 'State', 'task-manager' ); ?>"><span><?php esc_html_e( 'State', 'task-manager' ); ?></span></div>
		<div class="table-cell table-75" data-title="<?php esc_attr_e( 'Attachments', 'task-manager' ); ?>"><span><?php esc_html_e( 'Attachments', 'task-manager' ); ?></span></div>
		<div class="table-cell table-100" data-title="<?php esc_attr_e( 'Project Author', 'task-manager' ); ?>"><span><?php esc_html_e( 'Project Author', 'task-manager' ); ?></span></div>
		<div class="table-cell table-200" data-title="<?php esc_attr_e( 'Related Users', 'task-manager' ); ?>"><span><?php esc_html_e( 'Related Users', 'task-manager' ); ?></span></div>
		<div class="table-cell table-50"></div>
	</div>

	<?php
	if ( ! empty( $tasks ) ) :
		foreach ( $tasks as $task ) :
			$history_time_schema = History_Time_Class::g()->get(
				array(
					'schema' => true,
				),
				true
			);
			View_Util::exec(
				'task-manager',
				'task',
				'New/task',
				array(
					'task'         => $task,
					'with_wrapper' => $with_wrapper,
					'hide_tasks'   => $hide_tasks,
					'history_time_schema' => $history_time_schema,
				)
			);
		endforeach;
	endif;
	?>
</div>
