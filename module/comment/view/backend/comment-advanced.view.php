<?php
/**
 * Information avancée des commentaire.
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
	<div class="group-date">
		<i class="fas fa-calendar-alt"></i> <?php echo esc_html( $comment->data['date']['rendered']['date_human_readable'] ); ?>
	</div>

	<div class="wpeo-comment-time">
		<i class="fas fa-clock"></i> <?php echo esc_html( $comment->data['time_info']['elapsed'] ); ?>
	</div>
</div>
