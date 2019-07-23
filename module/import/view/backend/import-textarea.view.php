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
<div class="tm-import-add-keyword" style="display : flex">
	<div class="wpeo-button button-blue" data-type="task" style="margin-right: 8px;">
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Task', 'task-manager' ); ?></span>
	</div>
	<div class="wpeo-button button-blue" data-type="point" style="margin-right: 8px;">
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Point', 'task-manager' ); ?></span>
	</div>
	<?php /*<div class="wpeo-button button-blue" data-type="comment" >
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Comment', 'task-manager' ); ?></span>
	</div>*/ ?>
	<div class="wpeo-button button-blue" data-type="category" style="margin-right: 8px;">
		<i class="button-icon fas fa-plus-circle"></i>
		<span><?php esc_html_e( 'Categorie', 'task-manager' ); ?></span>
	</div>
	<div>
		<?php
		\eoxia\View_Util::exec(
			'task-manager',
			'import',
			'backend/import-tag-button',
			array(
				'tags' => $tags
			)
		);
		?>
	</div>

	<div class="wpeo-button button-grey" data-type="link" data-link="no" style="margin-right: 8px;">
		<input class="tm_link_external" type="hidden" name="link_external" value="no"/>
		<input class="tm_save_backup" type="hidden" value=""/>
		<i class="fas fa-link tm-icon-import-from-url"></i>
	</div>
	<p class="tm-info-import-link" style="display : none">
		<?php esc_html_e( 'Please put a link (.txt)', 'task-manager' ); ?>
		<input type="text" name="tm_import_get_text" data-import="false" value="" style="width: 100%"/>
	</p>
</div>
<textarea name="content" ><?= isset( $default_content ) ? esc_html( $default_content ) : '' ?></textarea>
