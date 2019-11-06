<?php
/**
 * Gestion des raccourcis.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019 Eoxia <dev@eoxia.com>.
 *
 * @license   GPLv3 <https://spdx.org/licenses/GPL-3.0-or-later.html>
 *
 * @package   EO_Framework\EO_Search\Template
 *
 * @since     1.11.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<tr class="edit-shortcut">
  <td>
    <input type="text" name="name" value="<?php echo esc_html( $shortcut['label'] ); ?>" />
    <input type="hidden" name="old_name" value="<?php echo esc_html( $shortcut['label'] ); ?>" />
  </td>
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
    <div class="wpeo-button button-blue action-input"
    data-parent="edit-shortcut"
    data-action="edit_shortcut_name"
    data-key="<?php echo esc_attr( $key ); ?>"
    data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_shortcut_name' ) ); ?>">
      <i class="fas fa-save"></i>
    </div>
  </td>
</tr>
