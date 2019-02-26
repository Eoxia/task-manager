<?php
/**
 * Gestion de la section "Indicator Client".
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<div class="section-capability">
	<input type="hidden" name="action" value="save_settings_user_indicator_client" />
	<?php wp_nonce_field( 'save_capability_task_manager' ); ?>

	<h3><?php esc_html_e( 'Settings Indicator Client', 'task-manager' ); ?></h3>

	<p><?php esc_html_e( 'Set colors and display of the indicator table in the client section', 'task-manager' ); ?></p>
	<div class="wpeo-grid grid-2">
		<div><?php \eoxia\View_Util::exec( 'task-manager', 'setting', 'indicatorclient/table' ); ?></div>
		<div id="div_setting_indicator_client_color"></div>
	</div>

</div>
