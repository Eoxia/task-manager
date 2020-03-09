<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! empty( $projects ) ) :
	foreach ( $projects as $project ) :
		?>
		<div class="tm-project" data-id="<?php echo esc_attr( $project->data['id'] ); ?>">
			<?php
			View_Util::exec(
				'task-manager',
				'support',
				'frontend/project-header-list',
				array(
					'project' => $project,
				)
			);

			View_Util::exec(
				'task-manager',
				'support',
				'frontend/tasks',
				array(
					'project'     => $project,
					'current_url' => $current_url,
				)
			);
			?>
		</div>
	<?php endforeach;
endif; ?>
