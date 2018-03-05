<?php
/**
 * Les données pour la MAJ 1.6.0
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

/**
 * Si vous faites une mise à jour sur une de ses actions, il faut obligatoirement préciser pourquoi.
 * 1) Incrémenter la version avec la version courante.
 * 2) Faites un descriptif de votre modification.
 *
 * Exemples:
 * 'action'            => 'task_manager_update_1600_task_compiled_time',
 * 'description'       => __( 'Create compiled time for all tasks.', 'task-manager' ),
 * 'since'             => '1.6.0',
 * 'version'           => '1.7.0',
 * 'description_1.7.0' => 'Correction du warning de l'index du tableau X..'
 */

$datas = array(
	array(
		'action'      => 'task_manager_update_1600_calcul_number_points',
		'description' => __( 'Count points in database.', 'task-manager' ),
		'since'       => '1.6.0',
		'version'     => '1.6.0',
	),
	array(
		'action'      => 'task_manager_update_1600_points',
		'description' => __( 'Update point data in database', 'task-manager' ),
		'since'       => '1.6.0',
		'version'     => '1.6.0',
	),
	array(
		'action'      => 'task_manager_update_1600_calcul_number_comments',
		'description' => __( 'Count comments in database.', 'task-manager' ),
		'since'       => '1.6.0',
		'version'     => '1.6.0',
	),
	array(
		'action'      => 'task_manager_update_1600_comments',
		'description' => __( 'Update comments data in database', 'task-manager' ),
		'since'       => '1.6.0',
		'version'     => '1.6.0',
	),
	array(
		'action'      => 'task_manager_update_1600_history_time',
		'description' => __( 'Update history time data in database', 'task-manager' ),
		'since'       => '1.6.0',
		'version'     => '1.6.0',
	),
);
