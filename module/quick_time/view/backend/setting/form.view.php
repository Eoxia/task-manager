<?php
/**
 * Le formulaire pour ajouter une configuration des temps rapides.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="item form">
	<input type="hidden" name="action" value="add_config_quick_time" />
	<?php wp_nonce_field( 'add_config_quick_time' ); ?>

	<li class="task">
		<div class="form-fields">
			<input type="text" class="quick-time-search-task" placeholder="<?php echo esc_attr_e( 'Name/ID Task', 'task-manager' ); ?>" />
			<input type="hidden" name="task_id" />
		</div>
		<div class="list-posts">
		</div>
	</li>
	<li class="point">
		<select name="point_id">
			<option value="0"><?php echo esc_html_e( 'Choose point', 'task-manager' ); ?></option>
		</select>
	</li>
	<li class="content"><textarea name="content" rows="1" placeholder="<?php echo esc_attr_e( 'Default comment', 'task-manager' ); ?>"></textarea></li>
	<li class="actions">
		<div class="action-input wpeo-button button-progress button-blue button-square-20 button-rounded" data-parent="form">
			<span class="button-icon fa fa-plus"></span>
		</div>
	</li>
</ul>
