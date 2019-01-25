<?php
/**
 * Message d'action lors de la création d'un commentaire.
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
} ?>&nbsp;<?php esc_html_e( 'added a new comment on the point', 'task-manager' ); ?> <span style="font-style: italic;"><?php echo '#' . $element->data['parent']->data['id'] . ' ' . $element->data['parent']->data['content']; ?></span>
