<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wrap wpeo-project-wrap">
	<div class="wpeo-project-dashboard">
		<p class="alignright"><?php esc_html_e( 'Total time past', 'task-manager' ); ?> : <?php echo esc_html( $total_time_elapsed ); ?> / <?php echo esc_html( $total_time_estimated ); ?></p>
	
	</div>


	<?php
	if ( ! empty( $tasks ) ) :
		foreach ( $tasks as $key => $data ) :
			?>
			<?php if ( ! empty( $data['title'] ) ) : ?><hr/><h2><?php echo esc_html( $data['title'] ); ?></h2><?php endif; ?>
			<div class="list-task">
				<?php \task_manager\Task_Class::g()->display_tasks( $data['data'] ); ?>
			</div>
			<?php
		endforeach;
	endif;
	?>
</div>
