<?php
/**
 * Gestion des shortcodes en relation aux catégories.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des shortcodes en relation aux catégories.
 */
class Tag_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_shortcode( 'task_manager_task_tag', array( $this, 'callback_task_manager_task_tag' ) );
	}

	/**
	 * Permet d'afficher les catégories dans le footer d'une tâche.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_task_manager_task_tag( $param ) {
		$task_id = ! empty( $param['task_id'] ) ? (int) $param['task_id'] : 0;

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$tags = array();
		if ( ! empty( $task->taxonomy['wpeo_tag'] ) ) {
			$tags = Tag_Class::g()->get( array(
				'include' => $task->taxonomy['wpeo_tag'],
			) );
		}

		\eoxia\View_Util::exec( 'tag', 'backend/main', array(
			'task' => $task,
			'tags' => $tags,
		) );
	}
}

new Tag_Shortcode();
