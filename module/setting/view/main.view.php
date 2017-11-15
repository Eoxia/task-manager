<?php
/**
 * Gestion des onglets dans la page "task-manager-setting".
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Evarisk
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap">
	<h1><?php esc_html_e( 'Task Manager settings', 'task-manager' ); ?></h1>

	<div class="wpeo-project-wrap">
		<div id="task-manager-capability" class="tab-content">
			<?php \eoxia\View_Util::exec( 'task-manager', 'setting', 'capability/main' ); ?>
		</div>
	</div>
</div>
