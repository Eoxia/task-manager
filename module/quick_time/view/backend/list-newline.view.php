<?php
/**
 * Ligne d'ajout d'un quicktime
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0 - BETA
 * @version 1.9.0 - BETA
 * @copyright 2015-2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<tr class="item form" id="tm_quicktime_create_new">
  <input type="hidden" name="action" value="add_config_quick_time" />
  <?php wp_nonce_field( 'add_config_quick_time' ); ?>
  <td class="task">
    <div class="form-fields">
      <input type="text" class="quick-time-search-task" placeholder="<?php echo esc_attr_e( 'Name/ID Task', 'task-manager' ); ?>" />
      <input type="hidden" name="task_id" id="tm_quicktime_stack_taskid_secretely"/>
    </div>
    <div class="list-posts">
    </div>
  </td>
  <td class="point wpeo-tooltip-event" style='max-width : 150px'>
		<select name="point_id" id="tm_quicktime_select_point_id" data-default = '<?php echo esc_html_e( 'Choose point', 'task-manager' ); ?>' style='max-width : 100px'>
      <option value="0"><?php echo esc_html_e( 'Choose point', 'task-manager' ); ?></option>
    </select>
  </td>
  <td class="content" data-title="<?php esc_html_e( 'Comment', 'task-manager' ); ?>">
    <textarea id="tm_quicktime_textarea_" name="content" rows="1" placeholder="<?php echo esc_attr_e( 'Default comment', 'task-manager' ); ?>"></textarea>
  </td>
  <td class="min" data-title="<?php esc_html_e( 'min.', 'task-manager' ); ?>">

  </td>
  <td class="action">

  </td>
	<td>
	</td>
	<td>
	<span class="wpeo-button button-disable button-progress action-input" id="tm_validate_quicktime_line"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'add_config_quick_time' ) ); ?>"
		data-action="add_config_quick_time"
		data-parent="form-quicktime">
		<span class="button-icon fa fa-plus" aria-hidden="true"></span>
	</span>

	</td>
</tr>
