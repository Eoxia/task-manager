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

defined( 'ABSPATH' ) || exit;

$parent_id = isset ( $parent_id ) ? $parent_id : 0;
$id        = isset ( $id ) ? $id : 0;
?>

<div class="shortcuts folder-<?php echo $id; ?>" data-id="<?php echo $id; ?>"  <?php echo ( $id != 0 ) ? 'style="display: none;"' : ''; ?>>
	<?php
	$i = 0;
	if ( ! empty( $shortcuts ) ) :
		foreach ( $shortcuts as $key => $shortcut ) :
			?>

				<?php

				\eoxia\View_Util::exec(
					'task-manager',
					'shortcut',
					'modal-handle-shortcut-item',
					array(
						'shortcut' => $shortcut,
						'key'      => $key,
						'parent_id' => $parent_id,
					)
				);
				?>
			<?php
			$i++;
		endforeach;
	endif;
	?>
</div>

<?php
if ( ! empty( $shortcuts ) ) :
	foreach ( $shortcuts as $key => $shortcut ) :
		if ($shortcut['type'] == 'folder') :
			\eoxia\View_Util::exec('task-manager', 'shortcut', 'modal-handle-shortcut-items', array(
				'shortcuts' => $shortcut['child'],
				'id'        => $shortcut['id'],
				'parent_id' => $shortcut['id'],
				'key'       => $key,
			) );
		endif;
	endforeach;
endif;
?>
