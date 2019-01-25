<?php
/**
 * Fichier de gestion des filtres permettant l'utilisation de "Tag" dans les tâches
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des filtres permettant l'utilisation de "Tag" dans les tâches
 */
class Tag_Filter {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		/** Ajout d'onglets dans le tableau de bord des tâches */
		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_filter_dashboard_tabs' ), 13, 1 );
		add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 12, 2 );

		add_filter( 'task_footer', array( $this, 'callback_task_footer' ), 5, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_footer' ), 11, 2 );
	}

	/**
	 * Fonction de callback permettant l'affichage d'onglet supplémentaire pour les tags dans le tableau de bord des tâches
	 *
	 * @param  string $current_tab_html Les onglets actuellement présent.
	 *
	 * @return string         Les onglets modifié pour le module des tags
	 */
	public function callback_filter_dashboard_tabs( $current_tab_html ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'tag', 'backend/filter-tab' );
		$current_tab_html .= ob_get_clean();

		return $current_tab_html;
	}

	/**
	 * Fonction de rappel permettant l'affichage du champs de recherche/ajout d'un tag dans le tableau de bord des tâches
	 *
	 * @param  string $current_search_html La sortie html courante pour la recherche dans le tableau de bord des tâches.
	 *
	 * @return string         La nouvelle sortie html pour la recherche dans le tableau de bord des tâches
	 */
	public function callback_task_manager_dashboard_search( $current_search_html ) {
		$list_tag = Tag_Class::g()->get();

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'tag',
			'backend/filter-search',
			array(
				'list_tag' => $list_tag,
			)
		);
		$current_search_html .= ob_get_clean();

		return $current_search_html;
	}

	/**
	 * Affichage des tags dans chaque tâches pour affectation à la tâche
	 *
	 * @param  string    $current_task_footer_html  Le contenu html de la tâche que l'on souhaite modifier.
	 * @param  WP_Object $task                      La tâche pour laquelle il faut récupèrer les tags déjà associés.
	 *
	 * @return string                              Le footer de la tâche auquel les tags ont été ajoutés
	 */
	public function callback_task_footer( $current_task_footer_html, $task ) {
		$list_tag = Tag_Class::g()->get(
			array(
				'post_id' => $task->data['id'],
			)
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'tag',
			'backend/tags-wrapper',
			array(
				'object'   => $task,
				'list_tag' => $list_tag,
			)
		);
		$current_task_footer_html .= ob_get_clean();

		return $current_task_footer_html;
	}

}

new Tag_Filter();
