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

<table class="wpeo-table">
	<thead>
		<tr>
			<th data-title="Nom"><?php esc_html_e( 'Nom', 'task-manager' ); ?></th>
			<th data-title="Task"><?php esc_html_e( 'Task ID', 'task-manager' ); ?></th>
			<th data-title="Point"><?php esc_html_e( 'Point ID', 'task-manager' ); ?></th>
			<th data-title="Terme"><?php esc_html_e( 'Terme', 'task-manager' ); ?></th>
			<th data-title="Utilisateur"><?php esc_html_e( 'Utilisateur', 'task-manager' ); ?></th>
			<th data-title="Catégories"><?php esc_html_e( 'Catégories', 'task-manager' ); ?></th>
			<th data-title="Client"><?php esc_html_e( 'Client', 'task-manager' ); ?></th>
			<th data-title="Commande"><?php esc_html_e( 'Commande', 'task-manager' ); ?></th>
			<th data-title="Action"><?php esc_html_e( 'Action', 'task-manager' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( ! empty( $shortcuts['wpeomtm-dashboard'] ) ) :
			foreach ( $shortcuts['wpeomtm-dashboard'] as $key => $shortcut ) :
				\eoxia\View_Util::exec(
					'task-manager',
					'navigation',
					'backend/modal-handle-shortcut-item',
					array(
						'shortcut' => $shortcut,
						'key' => $key
					)
				);
			endforeach;
		endif;
		?>
	</tbody>
</table>
