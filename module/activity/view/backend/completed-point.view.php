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

<?php echo '#COMPLETED' . $element->id . ' ' . $element->content; ?>
