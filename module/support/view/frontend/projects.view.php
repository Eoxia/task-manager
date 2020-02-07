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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! empty( $projects ) ) :
	foreach ( $projects as $project ) :
		?>
		<div>
			<p><?php echo esc_attr( $project->data['title'] ); ?></p>
			<p><?php echo $project->data['time_info']['elapsed'] . '/' . $project->data['time_info']['estimated_time']; ?></p>
			<p><?php echo $project->readable_tag; ?></p>

			<?php

			\eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/tasks', array(
				'project' => $project,
			) );
			?>
		</div>
		<?php
	endforeach;
else:
endif;
