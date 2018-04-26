<?php
/**
 * Le bouton pour créer un temps rapide.
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

<span class="wpeo-modal-event"
			data-action="load_popup_quick_time"
			data-class="popup-quick-time"
			data-title="<?php esc_html_e( 'Quick time', 'task-manager-wpshop' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_popup_quick_time' ) ); ?>"><?php esc_html_e( 'Quick time', 'task-manager' ); ?></span>
