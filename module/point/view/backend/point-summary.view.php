<?php
/**
 * Les informations d'un point, ID, Temps, Nombre de commentaire.
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

<li class="wpeo-block-id"><i class="fas fa-hashtag"></i> <?php echo esc_attr( $point->data['id'] ); ?></li>
<li class="wpeo-point-time">
	<i class="fas fa-clock"></i>
	<span class="wpeo-time-in-point"><?php echo esc_attr( $point->data['time_info']['elapsed'] ); ?></span>
</li>
<li>
	<i class="fas fa-comment-dots"></i>
	<span class="number-comments"><?php echo esc_html( $point->data['count_comments'] ); ?></span>
</li>
