<?php
/**
 * Affichage de la table dans la section configuration de task manager
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<table class="list wpeo-table">
  <thead>
    <tr>
      <th class="task" data-title="<?php esc_html_e( 'Colors', 'task-manager' ); ?>"><?php esc_html_e( 'Colors', 'task-manager' ); ?></th>
      <th class="point" data-title="<?php esc_html_e( 'From', 'task-manager' ); ?>"><?php esc_html_e( 'From', 'task-manager' ); ?></th>
      <th class="content" data-title="<?php esc_html_e( 'To', 'task-manager' ); ?>"><?php esc_html_e( 'To', 'task-manager' ); ?></th>
      <th class="min" data-title="<?php esc_html_e( 'Delete', 'task-manager' ); ?>">
				<?php esc_html_e( 'Delete', 'task-manager' ); ?>
      </th>
    </tr>
  </thead>

  <tbody class="body-indicator-client-settings" >

    <?php
			\eoxia\View_Util::exec(
				'task-manager',
				'setting',
				'indicatorclient/table-callline'
			);
    ?>

    <?php
        \eoxia\View_Util::exec(
          'task-manager',
          'setting',
          'indicatorclient/table-newline'
        );
    ?>

  </tbody>

</table>
