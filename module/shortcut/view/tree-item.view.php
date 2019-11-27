<?php
/**
 * Item dans l'arbre de navigation.
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

defined( 'ABSPATH' ) || exit;

if ( 'folder' === $shortcut['type'] ) :
	?>
	<div class="dropable shortcut folder item item-<?php echo esc_attr( $shortcut['id'] ); ?>" data-id="<?php echo esc_attr( $shortcut['id'] ); ?>">
		<i class="arrow-icon fas fa-chevron-right"></i>
		<i class="folder-icon fa fa-folder"></i>
		<span class="label"><?php echo esc_attr( $shortcut['label'] ); ?></span>
	</div>
	<?php
endif;

