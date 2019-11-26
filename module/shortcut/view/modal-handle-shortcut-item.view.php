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

<div class="shortcut <?php echo $shortcut['type'] == 'folder' ? 'folder' : ''; ?>" draggable="true" data-id="<?php echo $shortcut['id']; ?>">
<?php
if ( ! empty( $parent_id ) ) :
	?>
	<input type="hidden" class="order_input" name="order_shortcut[<?php echo $parent_id; ?>][<?php echo $key; ?>]" value="<?php echo $shortcut['id']; ?>" />
	<?php
else :
	?>
	<input type="hidden" class="order_input" name="order_shortcut[0][<?php echo $key; ?>]" value="<?php echo esc_attr( $shortcut['id'] ); ?>" />
	<?php
endif;
?>

<span class="icon">
	<?php
	if ( ! empty( $shortcut['type'] ) && $shortcut['type'] == 'folder' ) :
		?><i class="fas fa-folder"></i><?php
	endif;

	if ( empty( $shortcut['type'] ) || ( ! empty( $shortcut['type'] ) && 'link' == $shortcut['type'] ) ) :
		?><i class="fas fa-link"></i><?php
	endif;
	?>
</span>

<span class="label"><?php echo esc_html( $shortcut['label'] ); ?></span>

<div class="wpeo-dropdown">
  <div class="dropdown-toggle wpeo-button button-progress button-grey button-square-30 button-rounded">
	<span class="button-icon fas fa-ellipsis-v" aria-hidden="true"></span>
  </div>
  <ul class="dropdown-content" style='width : auto'>
	<li class="dropdown-item wpeo-tooltip-event action-attribute"
	  data-action="display_edit_shortcut_name"
	  data-nonce="<?php echo esc_attr( wp_create_nonce( 'display_edit_shortcut_name' ) ); ?>"
	  data-id="<?php echo esc_attr( $shortcut['id'] ); ?>"
		data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
	  style="text-align: center;"
	  aria-label="<?php esc_html_e( 'Edit name', 'task-manager' ); ?>">
	  <i class="fas fa-pen"></i>
	</li>
	<li class="dropdown-item" style="text-align: center;">
	  <?php if ( $shortcut['type'] != 'folder' ) : // 'My tasks' != $shortcut['label'] && 'Mes tâches' != $shortcut['label'] ?>
		<!--  wpeo-button button-progress button-grey button-square-30 button-rounded -->
		<div class="action-delete wpeo-tooltip-event"
		  data-action="delete_shortcut"
		  data-message-delete="<?php echo esc_attr_e( 'Are you sure to delete this shorcut ?', 'task-manager' ); ?>"
		  data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_shortcut' ) ); ?>"
		  data-id="<?php echo esc_attr( $shortcut['id'] ); ?>"
			 data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
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

</div>
