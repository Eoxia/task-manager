<?php
/**
 * Vu de l'input destiné à insert le temps dans la modal .
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="action-input wpeo-button button-main button-disable"
	data-parent="wpeo-modal"
	data-action="edit_point"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"><?php esc_html_e( 'Confirm', 'task-manager' ); ?>
</span>
