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

		$categories_searched = array();
		$follower_searched   = '';

		if ( ! empty( $categories_selected ) ) {
			foreach ( $categories_selected as $categorie ) {
				$categories_searched[] = array(
					'id' => $categorie->data['term_taxonomy_id'],
					'name' => $categorie->data['name'],
				);
			}
		}

		//$categories_searched = substr( $categories_searched, 0, -2 );

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
//			'backend/navigation-button-shortcut',
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

	public function cache_dropdown_customer() {
		ob_start();
		$customers = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'wpshop_customers',
			'post_status'    => 'draft',
			'orderby'        => 'title',
		) );

		if ( ! empty( $customers ) ) {
			foreach ( $customers as &$customer ) {

				$author_id = $customer->post_author;
				$user_ids = get_user_meta( $customer->ID, '_wpscrm_associated_user', true );
				$user_ids = array_merge( array( $author_id ), is_array( $user_ids ) ? $user_ids : array() );

				$customer->users = array();

				$customer->content = strtolower(trim( str_replace( ' ', '', $customer->post_title ) ));
				$customer->content_title = strtolower(trim( str_replace( ' ', '', $customer->post_title ) ));

				if ( ! empty( $user_ids ) ) {
					$customer->users = get_users( array( 'include' => $user_ids ) );
				}

				if ( ! empty( $customer->users ) ) {
					foreach ( $customer->users as $user ) {
						$customer->content .= strtolower( trim( str_replace( ' ', '', $user->data->display_name . $user->data->user_email ) ) );
					}
				}
			}
		}

		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/dropdown-customers', array(
			'customers' => $customers,
		) );
		$content = ob_get_clean();
		file_put_contents( str_replace( '\\', '/', PLUGIN_TASK_MANAGER_PATH ) . 'module/navigation/view/backend/dropdown-customers-cache.view.php', $content, LOCK_EX );
	}

	public function dropdown_customer() {
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/dropdown-customers-cache' );
	}

	public function get_data_shortcut( $shortcut ){
		$shortcut['link'] = parse_url( $shortcut['link'] );
		parse_str( $shortcut['link']['query'], $query );

		$data                   = array();
		$query['term']          = ! empty( $query['term'] ) ? sanitize_text_field( $query['term'] ) : '';
		$query['task_id']       = ! empty( $query['task_id'] ) ? (int) $query['task_id'] : '';
		$query['point_id']      = ! empty( $query['point_id'] ) ? (int) $query['point_id'] : '';
		$query['post_parent']   = ! empty( $query['post_parent'] ) ? (int) $query['post_parent'] : 0;
		$query['categories_id'] = ! empty( $query['categories_id'] ) ? sanitize_text_field( $query['categories_id'] ) : '';
		$query['user_id']       = ! empty( $query['user_id'] ) ? (int) $query['user_id'] : '';

		$shortcut['info'] = Navigation_Class::g()->get_search_result( $query['term'], 'any', $query['task_id'], $query['point_id'], $query['post_parent'], $query['categories_id'], $query['user_id'] );

		return $shortcut;
	}
}

Navigation_Class::g();
