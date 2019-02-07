<?php
/**
 * Gestion de la navigation
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
 * Gestion de la navigation.
 */
class Navigation_Class extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	protected function construct() {}

	/**
	 * Récupères les noms des catégories et utilisateurs pour afficher le résultat de la recherche.
	 *
	 * @since 1.4.0
	 * @version 1.6.0
	 *
	 * @param  string $term                   Le terme de la recherche.
	 * @param  string $status                 Peut être any ou archive.
	 * @param  string $task_id                  ID de la tache.
	 * @param  string $point_id               ID du point.
	 * @param  string $post_parent_id         ID du post parent.
	 * @param  string $categories_id L'ID des catégories sélectionnées. Ex: x,y,i.
	 * @param  string $user_id   L'ID des utilisateurs séléctionnés. Ex: x,y,i.
	 * @return array {
	 *         Les propriétés du tableau.
	 *         @type string $term Le terme de la recherche.
	 *         @type string $categories_searched Le nom des catégories séparées par des virgules.
	 *         @type string $follower_searched   Le nom des utilisateurs séparés par des virgules.
	 *         @type bool   $have_search         Si une recherche à eu lieu ou pas.
	 * }
	 */
	public function get_search_result( $term, $status, $task_id, $point_id, $post_parent_id, $categories_id, $user_id ) {
		$have_search = false;

		$categories_selected = array();

		if ( ! empty( $term ) || ! empty( $categories_id ) || ! empty( $user_id ) ) {
			$have_search = true;
		}

		if ( ! empty( $categories_id ) ) {
			$categories_selected = Tag_Class::g()->get(
				array(
					'term_taxonomy_id' => explode( ',', $categories_id ),
				)
			);
			$have_search         = true;
		}

		$categories_searched = '';
		$follower_searched   = '';

		if ( ! empty( $categories_selected ) ) {
			foreach ( $categories_selected as $categorie ) {
				$categories_searched .= $categorie->data['name'] . ', ';
			}
		}

		$categories_searched = substr( $categories_searched, 0, -2 );

		if ( ! empty( $user_id ) ) {
			$follower = Follower_Class::g()->get(
				array(
					'include' => $user_id,
				),
				true
			);

			$follower_searched = $follower->data['displayname'];
			$have_search       = true;
		}

		$post_parent_searched = '';

		$post_parent = null;

		if ( ! empty( $post_parent_id ) ) {
			$post_parent = get_post( $post_parent_id );

			$post_parent_searched = $post_parent->post_title;
			$have_search          = true;
		}

		return array(
			'term'                 => $term,
			'task_id'              => $task_id,
			'point_id'             => $point_id,
			'status'               => $status,
			'categories_id'        => $categories_id,
			'categories_searched'  => $categories_searched,
			'user_id'              => $user_id,
			'follower_searched'    => $follower_searched,
			'post_parent'          => $post_parent,
			'post_parent_id'       => $post_parent_id,
			'post_parent_searched' => $post_parent_searched,
			'have_search'          => $have_search,
		);
	}

	/**
	 * Récupères le résultat de la recherche et appel la vue search-results.
	 *
	 * @since 1.4.0
	 * @version 1.4.0
	 *
	 * @param  string $term                   Le terme de la recherche.
	 * @param  string $status                 Peut être any ou archive.
	 * @param  string $task_id                  ID de la tache.
	 * @param  string $point_id               ID du point.
	 * @param  string $post_parent         ID du post parent.
	 * @param  string $categories_id L'ID des catégories sélectionnées. Ex: x,y,i.
	 * @param  string $user_id   L'ID des utilisateurs séléctionnés. Ex: x,y,i.
	 * @param  string $display_button   Affichage du button.
	 * @return void
	 */
	public function display_search_result( $term, $status, $task_id, $point_id, $post_parent, $categories_id, $user_id, $display_button = true ) {
		$data = $this->get_search_result( $term, $status, $task_id, $point_id, $post_parent, $categories_id, $user_id );

		\eoxia\View_Util::exec(
			'task-manager',
			'navigation',
			'backend/search-results',
			array(
				'term'                 => $data['term'],
				'task_id'              => $data['task_id'],
				'point_id'             => $data['point_id'],
				'post_parent_searched' => $data['post_parent_searched'],
				'status'               => $data['status'],
				'categories_searched'  => $data['categories_searched'],
				'follower_searched'    => $data['follower_searched'],
				'have_search'          => 1, // @update 01/02/2019 $data['have_search'],
				'display_button'       => $display_button,
				'data'                 => $data,
			)
		);
	}
}

Navigation_Class::g();
