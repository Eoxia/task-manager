<?php
/**
 * L'affichage dans le mode 'grille' d'un point complété.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="event-title"><?php echo '#' . $element->data['id'] . ' ' . $element->data['content']; ?></span>
