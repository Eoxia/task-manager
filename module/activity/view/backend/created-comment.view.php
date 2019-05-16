<?php
/**
 * L'affichage dans le mode 'grille' d'un commentaire créé.
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

<div class="event-header">
	<span class="event-title"><?php echo '#' . $element->data['parent']->data['id'] . ' ' . $element->data['parent']->data['content']; ?></span>
	<span class="event-time"><i class="fas fa-clock"></i> <?php echo ! empty( $element->data['time_info']['elapsed'] ) ? $element->data['time_info']['elapsed'] : 0; ?></span>
</div>

<span class="event-content"><?php echo $element->data['content']; ?></span>
