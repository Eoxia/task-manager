<?php
/**
 * Affichage du tableau pour les cohérences des points.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.8.0
 * @copyright 2018 Eoxia.
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap wpeo-wrap">
	<h2><?php esc_html_e( 'Task Manager Tools', 'task-manager' ); ?></h2>

	<div class="wpeo-grid grid-2">
		<div>
			<h3><?php esc_html_e( 'Compile tasks, points, comments', 'task-manager' ); ?></h3>
			<div class="wpeo-button button-main action-attribute" data-action="task_manager_compile_data" data-nonce="<?php echo esc_attr( wp_create_nonce( 'task_manager_compile_data' ) ); ?>">
			<span><?php esc_html_e( 'Compile', 'task-manager' ); ?></span>
		</div>
		</div>

		<div>
			<h3><?php esc_html_e( 'Compile users', 'task-manager' ); ?></h3>
			<div class="wpeo-button button-main action-attribute" data-action="task_manager_compile_users" data-nonce="<?php echo esc_attr( wp_create_nonce( 'task_manager_compile_users' ) ); ?>">
			<span><?php esc_html_e( 'Compile', 'task-manager' ); ?></span>
		</div>
		</div>
	</div>
</div>
