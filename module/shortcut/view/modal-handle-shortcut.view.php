<?php
/**
 * Gestion des raccourcis.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2015-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   GPLv3 <https://spdx.org/licenses/GPL-3.0-or-later.html>
 *
 * @package   EO_Framework\EO_Search\Template
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-form hidden create-folder-form">
	<div class="form-element">
		<div class="form-field-container">
			<input type="text" class="form-field" name="folder_name" placeholder="<?php esc_html_e( 'Folder name', 'task-manager' ); ?>" />
			<span>
				<div class="wpeo-button button-main action-input" data-parent="wpeo-form" data-action="create_folder_shortcut">
					<span>Create</span>
				</div>
			</span>
		</div>
	</div>
</div>

<div class="wpeo-button button-main create-folder">
	<span><?php esc_html_e( 'Create Folder', 'task-manager' ); ?></span>
</div>

<div class="shortcuts-content">
	<?php
	\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'tree', array(
		'shortcuts' => $shortcuts[0]['child'],
	) );
	?>

	<?php
	\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'modal-handle-shortcut-items', array(
		'shortcuts' => $shortcuts[0]['child'],
		'level'     => 0,
		'key'       => 0,
	) );
	?>


</div>
