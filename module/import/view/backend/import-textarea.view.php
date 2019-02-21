<?php
/**
 * La vue d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="tm-import-add-keyword" >
	<div class="wpeo-button button-blue" data-type="task" >
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Task', 'task-manager' ); ?></span>
	</div>
	<div class="wpeo-button button-blue" data-type="point" >
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Point', 'task-manager' ); ?></span>
	</div>
	<?php /*<div class="wpeo-button button-blue" data-type="comment" >
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Comment', 'task-manager' ); ?></span>
	</div>*/ ?>
</div>
<textarea name="content" ><?php echo esc_html( $default_content ); ?></textarea>
