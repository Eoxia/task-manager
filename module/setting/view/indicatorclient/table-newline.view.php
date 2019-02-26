<?php
/**
 * Ligne d'ajout de la table
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


<tr class="item form setting-indicator-client">
  <td class="task">
			<input type="hidden" id='setting-indicator-client-update-hidden' name="color" value="#ff0000"/>
      <input type="color" id='setting-indicator-client-update-color' value="#ff0000"/>
  </td>
  <td class="point">
		<input type="number" id='setting-indicator-client-input' name="numberfrom">
  </td>

  <td>
  </td>

  <td>
		<span class="wpeo-button button-disable button-progress action-input"
			id="setting-indicator-client-button"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_user_settings_indicator_client' ) ); ?>"
			data-action="update_user_settings_indicator_client"
			data-parent="setting-indicator-client">
			<span class="button-icon fa fa-plus" aria-hidden="true"></span>
		</span>

  </td>

</tr>
