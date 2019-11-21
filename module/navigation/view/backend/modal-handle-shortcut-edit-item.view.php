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

<div class="table-cell">
	<?php
	if ( ! empty( $shortcut['type'] ) && $shortcut['type'] == 'folder' ) :
		?><i class="fas fa-folder"></i><?php
	endif;

	if ( empty( $shortcut['type'] ) || ( ! empty( $shortcut['type'] ) && 'link' == $shortcut['type'] ) ) :
		?><i class="fas fa-link"></i><?php
	endif;
	?>
</div>
<div class="table-cell">
    <input type="text" name="name" value="<?php echo esc_html( $shortcut['label'] ); ?>" />
    <input type="hidden" name="old_name" value="<?php echo esc_html( $shortcut['label'] ); ?>" />
</div>
<div class="table-cell"><?php echo esc_html( $shortcut['info']['task_id'] ); ?></div>
<div class="table-cell"><?php echo esc_html( $shortcut['info']['point_id'] ); ?></div>
<div class="table-cell"><?php echo esc_html( $shortcut['info']['term'] ); ?></div>
<div class="table-cell"><?php echo esc_html( $shortcut['info']['follower_searched'] ); ?></div>
<div class="table-cell"><?php echo esc_html( $shortcut['info']['categories_searched'] ); ?></div>
<div class="table-cell"></div>
<?php if ( 'wpshop_shop_order' != $shortcut['info']['post_parent'] ) : ?>
  <?php echo esc_html( $shortcut['info']['post_parent_searched'] ); ?>
<?php endif; ?>
</div>
<div class="table-cell">
<?php if ( 'wpshop_shop_order' == $shortcut['info']['post_parent'] ) : ?>
  <?php echo esc_html( $shortcut['info']['post_parent_searched'] ); ?>
<?php endif; ?>
</div>
<div class="table-cell">
	<div class="wpeo-button button-blue action-input"
	data-parent="table-row"
	data-action="edit_shortcut_name"
	data-key="<?php echo esc_attr( $key ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_shortcut_name' ) ); ?>">
	  <i class="fas fa-save"></i>
	</div>
</div>
