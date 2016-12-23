<?php
/**
 * Template pour l'affichage d'onglets dans le tableau de bord des tâches
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><li class="wpeo-button wpeo-button-archived-task"><?php esc_html_e( 'Archived task', 'task-manager' ); ?></li>
