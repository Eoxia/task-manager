<?php
/**
 * L'affichage dans le mode 'grille' d'un commentaire créé.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="event-header">
	<span class="event-title"><?php echo '#' . $element->parent->id . ' ' . $element->parent->content; ?></span>
	<span class="event-time"><i class="dashicons dashicons-clock"></i><?php echo ! empty( $element->time_info['elapsed'] ) ? $element->time_info['elapsed'] : 0; ?></span>
</div>
<span class="event-content"><?php echo $element->content; ?></span>
