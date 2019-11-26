<?php
/**
 * Formulaire édition raccourcis
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


<div class="shortcut edit <?php echo $shortcut['type'] == 'folder' ? 'folder' : ''; ?>" data-id="<?php echo $shortcut['id']; ?>">

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

	<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
	<input type="hidden" name="id" value="<?php echo $shortcut['id']; ?>" />
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

	<span class="label"><input type="text" name="name" value="<?php echo esc_html( $shortcut['label'] ); ?>" /></span>

	<div class="wpeo-button button-blue action-input"
		 data-parent="shortcut"
		 data-action="edit_shortcut_name"
		 data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_shortcut_name' ) ); ?>">
		<i class="fas fa-save"></i>
	</div>
</div>
