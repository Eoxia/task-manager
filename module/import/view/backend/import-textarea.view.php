<?php
/**
 * La vue d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><textarea name="content" style="width: 100%; height: 350px;" ><?php echo esc_html( $default_content ); ?></textarea>
