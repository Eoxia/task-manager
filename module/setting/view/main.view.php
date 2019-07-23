<?php
/**
 * Gestion des onglets dans la page "task-manager-setting".
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.6.0
 * @copyright 2015-2018 Evarisk
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap wpeo-wrap">
	<h1><?php esc_html_e( 'Task Manager settings', 'task-manager' ); ?></h1>

	<div class="tm-wrap">

		<div class="wpeo-tab">
			<ul class="tab-list">
				<li class="tab-element tab-active" data-target="general-setting">
					<?php esc_html_e( 'General settings', 'task-manager' ); ?>
				</li>

				<li class="tab-element" data-target="right-management">
					<?php esc_html_e( 'Rights management', 'task-manager' ); ?>
				</li>
			</ul>

			<div class="tab-container">
				<div id="general-setting" class="tab-content tab-active">
					<?php
					\eoxia\View_Util::exec(
						'task-manager',
						'setting',
						'general/main',
						array(
							'use_search_in_admin_bar' => $use_search_in_admin_bar,
						)
					);
					?>
				</div>
				<div id="right-management" class="tab-content"><?php \eoxia\View_Util::exec( 'task-manager', 'setting', 'capability/main' ); ?></div>
			</div>
		</div>
	</div>
</div>
