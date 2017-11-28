<?php
/**
 * Gestion de la navigation
 *
 * @package Task Manager
 * @subpackage Module/navigation
 *
 * @since 1.0.0
 * @version 1.4.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion de la navigation.
 */
class Navigation_Class extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {}

	/**
	 * Récupères les noms des catégories et utilisateurs pour afficher le résultat de la recherche.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  string $term                   Le terme de la recherche.
	 * @param  string $categories_id_selected L'ID des catégories sélectionnées. Ex: x,y,i.
	 * @param  string $follower_id_selected   L'ID des utilisateurs séléctionnés. Ex: x,y,i.
	 * @return array {
	 *         Les propriétés du tableau.
	 *         @type string $term Le terme de la recherche.
	 *         @type string $categories_searched Le nom des catégories séparées par des virgules.
	 *         @type string $follower_searched   Le nom des utilisateurs séparés par des virgules.
	 *         @type bool   $have_search         Si une recherche à eu lieu ou pas.
	 * }
	 */
	public function get_search_result( $term, $categories_id_selected, $follower_id_selected ) {
		$have_search = false;

		$categories_selected = array();

		if ( ! empty( $term ) || ! empty( $categories_id_selected ) || ! empty( $follower_id_selected ) ) {
			$have_search = true;
		}

		if ( ! empty( $categories_id_selected ) ) {
			$categories_selected = Tag_Class::g()->get( array(
				'term_taxonomy_id' => explode( ',', $categories_id_selected ),
			) );
		}

		$categories_searched = '';
		$follower_searched = '';

		if ( ! empty( $categories_selected ) ) {
			foreach ( $categories_selected as $categorie ) {
				$categories_searched .= $categorie->name . ', ';
			}
		}

		$categories_searched = substr( $categories_searched, 0, -2 );

		if ( ! empty( $follower_id_selected ) ) {
			$follower = Follower_Class::g()->get( array(
				'include' => $follower_id_selected,
			), true );

			$follower_searched = $follower->displayname;
		}

		return array(
			'term' => $term,
			'categories_searched' => $categories_searched,
			'follower_searched' => $follower_searched,
			'have_search' => $have_search,
		);
	}

	/**
	 * Récupères le résultat de la recherche et appel la vue search-results.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  string $term                   Le terme de la recherche.
	 * @param  string $categories_id_selected L'ID des catégories sélectionnées. Ex: x,y,i.
	 * @param  string $follower_id_selected   L'ID des utilisateurs séléctionnés. Ex: x,y,i.
	 * @return void
	 */
	public function display_search_result( $term, $categories_id_selected, $follower_id_selected ) {
		$data = $this->get_search_result( $term, $categories_id_selected, $follower_id_selected );

		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/search-results', array(
			'term' => $data['term'],
			'categories_searched' => $data['categories_searched'],
			'follower_searched' => $data['follower_searched'],
			'have_search' => $data['have_search'],
		) );
	}
}

Navigation_Class::g();
