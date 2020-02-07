<?php
/**
 * La vue principale des tâches dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; } ?>

<div class="tm-auto-complete-user">
	<?php echo do_shortcode( '[task_manager_dropdown_users]' ); ?>
</div>

<div class="list-task">
	<?php Task_Class::g()->display( $tasks ); ?>
</div>

<div style="margin:auto; text-align: center;">
	<?php
	if ( count( $tasks ) == $number_tasks ) :
		?>
		<div class="button-grey  more-button button-disable wpeo-button">
		<span class="text"><?php esc_html_e( 'No more entries', 'task-manager' ); ?></span>
		<span class="current"><?php echo count( $tasks ); ?></span><span>/</span><span class="total"><?php echo esc_attr( $number_tasks ); ?></span>
		</div>
	<?php
	else:
		?>
		<div class="button-main more-button load-more-button wpeo-button">
			<span class="text"><?php esc_html_e( 'Load more entries', 'task-manager' ); ?></span>
			<span class="current"><?php echo count( $tasks ); ?></span><span>/</span><span class="total"><?php echo esc_attr( $number_tasks ); ?></span>
		</div>
		<?php
	endif;
	?>
</div>
