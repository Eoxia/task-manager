<?php
/**
 * L'affichage dans le mode 'grille' d'un point complété.
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

<span class="event-title" style="width: auto;font-weight: 700;display: block;margin-bottom: .4em;"><?php echo '#' . $element->id . ' ' . $element->content; ?></span>
