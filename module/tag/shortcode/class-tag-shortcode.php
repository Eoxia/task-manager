<?php
/**
 * Gestion des shortcodes en relation aux catégories.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des shortcodes en relation aux catégories.
 */
class Tag_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 *
	 * @since 1.0.0
	 * @version 1.3.6
	 */
	public function __construct() {
		add_shortcode( 'task_manager_task_tag', array( $this, 'callback_task_manager_task_tag' ) );
	}

	/**
	 * Permet d'afficher les catégories dans le footer d'une tâche.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @return HTML Le code HTML permettant l'affichage de la liste des tags associés à une tâche.
	 */
	public function callback_task_manager_task_tag( $param ) {
		$task_id = ! empty( $param['task_id'] ) ? (int) $param['task_id'] : 0;

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$tags = array();
		if ( ! empty( $task->data['taxonomy'][ Tag_Class::g()->get_type() ] ) ) {
			$tags = Tag_Class::g()->get(
				array(
					'include' => $task->data['taxonomy'][ Tag_Class::g()->get_type() ],
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'tag',
			'backend/main',
			array(
				'task' => $task,
				'tags' => $tags,
			)
		);

		return ob_get_clean();
	}

}

new Tag_Shortcode();
