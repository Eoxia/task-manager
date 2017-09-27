<?php
/**
 * Gestion de la navigation
 *
 * @package Task Manager
 * @subpackage Module/navigation
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

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

	public function get_search_result( $term, $categories_id_selected, $follower_id_selected ) {
		$have_search = false;

		$categories_selected = array();

		if ( ! empty( $term ) || ! empty( $categories_id_selected ) || ! empty( $follower_id_selected ) ) {
			$have_search = true;
		}

		$categories_id_selected = get_term_by( 'term_taxonomy_id', $categories_id_selected, 'wpeo_tag' );

		if ( ! empty( $categories_id_selected ) ) {
			$categories_selected = Tag_Class::g()->get( array(
				'include' => $categories_id_selected->term_id,
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
