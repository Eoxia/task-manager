<?php
/**
 * La vue pour afficher une catégorie en bas d'une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<li class="wpeo-tag active"><?php echo esc_attr( $tag->name ); ?></li>
