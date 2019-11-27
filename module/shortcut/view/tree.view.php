<?php
/**
 * Création de raccourcis
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

<div class="tree folder">
	<div class="item dropable shortcut folder active item-0" data-level="0" data-key="0" data-parent="true">
		<i class="arrow-icon fas fa-chevron-down"></i>
		<i class="folder-icon fa fa-folder"></i>
		<span class="label">Raccourcis</span>
	</div>

	<div class="descendants">
		<?php

		if ( ! empty( $shortcuts ) ) :
			foreach ( $shortcuts as $key => $shortcut ) :
				\eoxia\View_Util::exec(
					'task-manager',
					'shortcut',
					'tree-item',
					array(
						'shortcut' => $shortcut,
					)
				);
			endforeach;
		endif;
		?>
	</div>
</div>

