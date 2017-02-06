<?php
/**
 * Fichier d'affichage pour le temps passé sur la tâche
 *
 * @package task-manager
 */

/**
 * Le temps en jour / heure
 *
 * @var string
 */
$format = '%hh %imin';
if ( 1440 <= $task->option['time_info']['elapsed'] ) {
	$format = '%aj ' . $format;
}
$human_readable_time = taskmanager\util\wpeo_util::minutes_to_time( $task->option['time_info']['elapsed'], $format );

?><li class="wpeo-task-elapsed">
	<i class="dashicons dashicons-clock"></i>
	<span class="elapsed"><?php echo esc_html( $human_readable_time . ' (' . $task->option['time_info']['elapsed'] . 'min)' ); ?></span>
</li>
