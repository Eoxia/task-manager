<?php
/**
 * Affichage de la table dans la section configuration de task manager
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
}
?>

<tr>


  <td>
		<input type="color" id='setting-indicator-client-update-color' value="<?= $color ?>"/>
  </td>

  <td>
		<?= $numberfrom ?>
 	</td>

  <td>
	 <?= $numberto ?>
 </td>

  <td>
		<span class="wpeo-button button-red button-progress action-attribute"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_user_settings_indicator_client' ) ); ?>"
			data-action="delete_user_settings_indicator_client"
			data-key="<?= $key + 1 ?>">
			<span class="button-icon fa fa-times" aria-hidden="true"></span>
		</span>
	 </td>

</tr>
