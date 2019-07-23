<?php
/**
 * Édition avancée des commentaire.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   TaskManager\Templates
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="comment-meta wpeo-form">
	<div class="form-element group-date" data-time="true">
		<label class="form-field-container">
			<input type="hidden" class="mysql-date" name="mysql_date" value="<?php echo $comment->data['date']['raw']; ?>" />
			<span class="form-field-icon-prev"><i class="fas fa-calendar-alt"></i></span>
			<input type="text" class="form-field date" value="<?php echo $comment->data['date']['rendered']['date_time']; ?>" />
		</label>
	</div>

	<?php

	$value = empty( $comment->data['id'] ) && isset( $comment->data['time_info']['calculed_elapsed'] ) ? $comment->data['time_info']['calculed_elapsed'] : $comment->data['time_info']['elapsed'];

	 ?>

	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-clock"></i></span>
			<input type="text" name="time" value="<?php echo esc_attr( $value ); ?>" class="form-field" />
		</label>
	</div>
</div>
