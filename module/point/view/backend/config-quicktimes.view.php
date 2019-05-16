<?php
/**
 * Dans un point => permet à l'utilisateur d'ajouter / supprimer rapidement un quicktime
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0-ford
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm_add_quicktime_point">
	<?php
		$key = Quick_Time_Class::g()->this_point_is_a_quicktime( $task_id, $point_id );
		if( $key == -1 ): ?>

		<div class="action-input"
			data-action="add_config_quick_time"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'add_config_quick_time' ) ); ?>"
			data-parent="tm_add_quicktime_point">
			<i class="fas fa-clock"></i>
			<label><b> <?php esc_html_e( 'Add to quicktimes', 'task-manager' ) ?></b></label>
			<input type="hidden" name="task_id" value="<?php echo esc_attr( $task_id ); ?>" />
			<input type="hidden" name="point_id" value="<?php echo esc_attr( $point_id ); ?>" />
		</div>

	<?php else : ?>

	<div class="action-input"
		data-action="remove_config_quick_time"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'remove_config_quick_time' ) ); ?>"
		data-parent="tm_add_quicktime_point">
		<i class="fas fa-clock"></i>
		<label><b> <?php esc_html_e( 'Remove from quicktimes', 'task-manager' ) ?></b></label>
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task_id ); ?>" />
		<input type="hidden" name="point_id" value="<?php echo esc_attr( $point_id ); ?>" />
		<input type="hidden" name="key" value="<?php echo esc_attr( $key ); ?>" />
	</div>

	<?php endif; ?>

</div>
