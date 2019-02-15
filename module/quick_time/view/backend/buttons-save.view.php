<?php
/**
 * Le bouton "sauvegarde" situé dans le footer.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<div id='tm_quicktime_button_save'>
	<div class="wpeo-button button-disable button-progress action-input tm_quickpoint_add_time"
	  data-parent="modal-container"
	  data-action="quick_time_add_comment"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'quick_time_add_comment' ) ) ?>">
	  <span class="button-icon fa fa-save" aria-hidden="true"></span>
	</div>
</div>
