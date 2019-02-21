<?php
/**
 * L'affichage dans le mode 'grille' d'un point créé.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><p><?php echo '#' . $element->data['id'] . ' ' . $element->data['content']; ?></p>
