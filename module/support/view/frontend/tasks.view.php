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

if ( ! empty( $project->uncompleted_tasks ) ) :
	foreach ( $project->uncompleted_tasks as $task ) :
		?>
		<a href="<?php echo esc_attr( home_url( '/mon-compte/?account_dashboard_part=support&project_id=' . $project->data['id'] . '&task_id=' . $task->data['id'] ) ); ?>"><?php echo esc_attr( $task->data['content'] ); ?></a>
	<?php
	endforeach;
else:
endif;


if ( ! empty( $project->completed_tasks ) ) :
	foreach ( $project->completed_tasks as $task ) :
		?>
		<p><?php echo esc_attr( $task->data['content'] ); ?></p>
	<?php
	endforeach;
else:
endif;
