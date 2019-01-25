<?php
/**
 * Le bouton "réglage".
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="wpeo-button button-light wpeo-modal-event setting-quick-time-button"
	data-action="open_setting_quick_time"
	data-class="setting-quick-time"
	data-title="<?php echo esc_attr_e( 'Setting of the add quick time list', 'task-manager' ); ?>">
	<span class="fa fa-cog" aria-hidden="true"></span>
</span>
