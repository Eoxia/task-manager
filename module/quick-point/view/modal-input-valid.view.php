<?php
/**
 * Vu de l'input destiné à insert le temps dans la modal .
 *
 * @author ||||||||
 * @since 1.6.1
 * @version 1.6.1
 * @copyright 2018+
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<input
	class="action-input wpeo-button button-main"
	data-parent="wpeo-modal"
	data-action="edit_point"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"
	type="submit" />
