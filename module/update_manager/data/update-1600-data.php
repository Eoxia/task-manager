<?php
/**
 * Les données pour la MAJ 1.6.0
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

/**
 * Si vous faites une mise à jour sur une de ses actions, il faut obligatoirement préciser pourquoi.
 * 1) Incrémenter la version avec la version courante.
 * 2) Faites un descriptif de votre modification.
 *
 * Exemples:
 * 'action'            => 'task_manager_update_1600_task_compiled_time',
 * 'title'             => __( 'What the update will do.', 'task-manager' ),
 * 'description'       => __( 'Details for update human friendly.', 'task-manager' ),
 * 'since'             => '1.6.0',
 * 'version'           => '1.7.0',
 * 'description_1.7.0' => 'Correction du warning de l'index du tableau X..'
 * 'update_index'      => 'XXXa',
 */

$datas = array(
	array(
		'action'         => 'task_manager_update_1600_lost_datas',
		'title'          => __( 'Fix problems with points/comments that are orphelans', 'task-manager' ),
		'description'    => __( 'Some points/comments may have problems on the comment post ID, this update will fix them', 'task-manager' ),
		'since'          => '1.6.0',
		'version'        => '1.6.0',
		'update_index'   => '160a',
		'count_callback' => '',
	),
	array(
		'action'         => 'task_manager_update_1600_points',
		'title'          => __( 'Update point data in database', 'task-manager' ),
		'description'    => __( 'Change the `comment_type` field and the `comment_approved` field for points', 'task-manager' ),
		'since'          => '1.6.0',
		'version'        => '1.6.0',
		'update_index'   => '160b',
		'count_callback' => '\task_manager\Update_1600::callback_task_manager_update_1600_calcul_number_points',
	),
	array(
		'action'         => 'task_manager_update_1600_comments',
		'title'          => __( 'Update comments data in database', 'task-manager' ),
		'description'    => __( 'Change the `comment_type` field and the `comment_approved` field for comments', 'task-manager' ),
		'since'          => '1.6.0',
		'version'        => '1.6.0',
		'update_index'   => '160c',
		'count_callback' => '\task_manager\Update_1600::callback_task_manager_update_1600_calcul_number_comments',
	),
	array(
		'action'         => 'task_manager_update_1600_history_time',
		'title'          => __( 'Update history time data in database', 'task-manager' ),
		'description'    => __( 'Clean meta datas of previsionnal times in database', 'task-manager' ),
		'since'          => '1.6.0',
		'version'        => '1.6.0',
		'update_index'   => '160d',
		'count_callback' => '',
	),
	array(
		'action'         => 'task_manager_update_1600_comment_status',
		'title'          => __( 'Update comment approved in database', 'task-manager' ),
		'description'    => __( 'Check and change the `comment_type` field and the `comment_approved` field for comments used in task manager', 'task-manager' ),
		'since'          => '1.6.0',
		'version'        => '1.6.0',
		'update_index'   => '160e',
		'count_callback' => '',
	),
	array(
		'action'         => 'task_manager_update_1600_archived_task',
		'title'          => __( 'Clean archived tasks', 'task-manager' ),
		'description'    => __( 'Remove the archive tag and change the status to archive if it is not already the case', 'task-manager' ),
		'since'          => '1.6.0',
		'version'        => '1.6.0',
		'update_index'   => '160f',
		'count_callback' => '',
	),
);
