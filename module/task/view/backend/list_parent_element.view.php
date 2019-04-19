<?php
/**
 * La vue principale des tâches dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; }
	?>

		<div class="wpeo-tag">
			<label>
				<i class="fas fa-search"></i>
				<input type="hidden" class="task_search-taxonomy" name="parent_id"/>
				<input type="text" class="tm_task_autocomplete_parent" placeholder="<?php echo esc_html( 'Search ... ', 'task-manager') ?>" />
			</label>
			<ul style="background-color: #c6c6c6; cursor: pointer; position: absolute; opacity: 1; z-index: 5;">
				<?php foreach( $data as $key => $element ): ?>
					<?php if( ! $element[ 'id' ] ): ?>
						<li style="font-size: 18px; background-color: #A9A9A9">
							<?php echo esc_html( $element['value'] ); ?>
						</li>
					<?php else: ?>
						<li class="tm_list_parent_li_element" data-id="<?php echo esc_html( $element['id'] ); ?>" style="font-size: 15px; padding: 2px;">
							<?php echo esc_html( $element['value'] ); ?>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
