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

<tr class="shortcut-view">
  <td><?php echo esc_html( $shortcut['label'] ); ?></td>
  <td><?php echo esc_html( $shortcut['info']['task_id'] ); ?></td>
  <td><?php echo esc_html( $shortcut['info']['point_id'] ); ?></td>
  <td><?php echo esc_html( $shortcut['info']['term'] ); ?></td>
  <td><?php echo esc_html( $shortcut['info']['follower_searched'] ); ?></td>
  <td><?php echo esc_html( $shortcut['info']['categories_searched'] ); ?></td>
  <td>
    <?php if ( 'wpshop_shop_order' != $shortcut['info']['post_parent'] ) : ?>
      <?php echo esc_html( $shortcut['info']['post_parent_searched'] ); ?>
    <?php endif; ?>
  </td>
  <td>
    <?php if ( 'wpshop_shop_order' == $shortcut['info']['post_parent'] ) : ?>
      <?php echo esc_html( $shortcut['info']['post_parent_searched'] ); ?>
    <?php endif; ?>
  </td>
  <td>
    <div class="wpeo-dropdown">
      <div class="dropdown-toggle wpeo-button button-progress button-grey button-square-30 button-rounded">
        <span class="button-icon fas fa-ellipsis-v" aria-hidden="true"></span>
      </div>
      <ul class="dropdown-content" style='width : auto'>
        <li class="dropdown-item wpeo-tooltip-event action-attribute"
          data-action="display_edit_shortcut_name"
          data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_edit_shortcut_name' ) ); ?>"
          data-key="<?php echo esc_attr( $key ); ?>"
          style="text-align: center;"
          aria-label="<?php esc_html_e( 'Edit name', 'task-manager' ); ?>">
          <i class="fas fa-pen"></i>
        </li>
        <li class="dropdown-item" style="text-align: center;">
          <?php if ( $key > 0 ) : // 'My tasks' != $shortcut['label'] && 'Mes tâches' != $shortcut['label'] ?>
            <!--  wpeo-button button-progress button-grey button-square-30 button-rounded -->
            <div class="action-delete wpeo-tooltip-event"
              data-action="delete_shortcut"
              data-message-delete="<?php echo esc_attr_e( 'Are you sure to delete this shorcut ?', 'task-manager' ); ?>"
              data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_shortcut' ) ); ?>"
              data-key="<?php echo esc_attr( $key ); ?>"
              data-loader="shortcut-view"
              aria-label="<?php esc_attr_e( 'Delete this shortcut', 'task-manager' ); ?>">
              <span class="button-icon fa fa-times" aria-hidden="true"></span>
            </div>
          <?php else : ?>
            <!-- button-event wpeo-button button-progress button-disable button-square-30 button-rounded -->
            <div class="wpeo-tooltip-event"
              aria-label="<?php esc_attr_e( 'Can\'t be deleted', 'task-manager' ); ?>">
              <span class="button-icon fa fa-times" aria-hidden="true"></span>
            </div>
          <?php endif; ?>
        </li>
      </ul>
    </div>

  </td>
</tr>
