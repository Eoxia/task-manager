<?php
/**
 * La vue pour afficher une catégorie en bas d'une tâche.
 *
 * @since 0.1.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="wpeo-tag active"><?php echo esc_attr( $tag->data['name'] ); ?></li>
