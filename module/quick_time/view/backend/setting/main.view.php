<?php
/**
 * La fenêtre de configuration des ajouts de temps rapides.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="list">
	<ul class="item form">
		<input type="hidden" name="action" value="add_config_quick_time" />
		<?php wp_nonce_field( 'add_config_quick_time' ); ?>

		<li class="task">
			<div class="form-fields">
				<input type="text" class="search-task" placeholder="<?php echo esc_attr_e( 'Name/ID Task', 'task-manager' ); ?>" />
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
		<li class="content"><textarea name="content" placeholder="<?php echo esc_attr_e( 'Default comment', 'task-manager' ); ?>"></textarea></li>
		<li class="actions">
			<div class="action-input wpeo-button button-progress" data-parent="form">
				<span class="dashicons dashicons-plus-alt"></span>
			</div>
		</li>
	</ul>

	<?php
	if ( ! empty( $quick_times ) ) :
		foreach ( $quick_times as $key => $quick_time ) :
			\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/setting/item', array(
				'key'        => $key,
				'quick_time' => $quick_time,
			) );
		endforeach;
	endif;
	?>
</div>
