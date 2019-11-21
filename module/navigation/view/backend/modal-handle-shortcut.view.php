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

<div class="wpeo-table table-flex table-10">
	<div class="table-row table-header">
		<div class="table-cell"></div>
		<div class="table-cell"><?php esc_html_e( 'Nom', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Task ID', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Point ID', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Terme', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Utilisateur', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Catégories', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Client', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Commande', 'task-manager' ); ?></div>
		<div class="table-cell"><?php esc_html_e( 'Action', 'task-manager' ); ?></div>
	</div>
	<?php
	$i = 0;
	if ( ! empty( $shortcuts['wpeomtm-dashboard'] ) ) :
		foreach ( $shortcuts['wpeomtm-dashboard'] as $key => $shortcut ) :
			?>
			<div class="table-row shortcut-sortable shortcut-dropzone" draggable="true">
				<input type="hidden" name="order_shortcut[<?php echo esc_attr( $i ); ?>]" value="<?php echo esc_attr( $key ); ?>" />
				<?php
				\eoxia\View_Util::exec(
					'task-manager',
					'navigation',
					'backend/modal-handle-shortcut-item',
					array(
						'shortcut' => $shortcut,
						'key' => $key
					)
				);

				$i++;
				?>
			</div>
			<?php
		endforeach;
	endif;
	?>
</div>
