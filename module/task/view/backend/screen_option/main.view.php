<?php
/**
 * La vue principale des options dans la section client.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.10
 * @version 1.10
 * @copyright 2019 Eoxia
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; } ?>

	<div class="pmg-sotut-container">
		<h5><?php esc_html_e( 'Number task per page', 'task-manager' ); ?></h5>

		<p style="float:left">
			<input type="number" class="normal-text" name="task_page" value="<?php echo esc_attr( $value_task )  ?>" />
		</p>

		<p style="margin-top: 0px;">
			<button class="button button-primary action-input"
			data-parent="pmg-sotut-container"
			data-action="update_task_per_page_user">
				<?php esc_html_e( 'Applicate', 'task-manager' ); ?>
			</button>
		</p>
	</div>
