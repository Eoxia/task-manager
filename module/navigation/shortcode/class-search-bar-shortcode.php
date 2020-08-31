<?php
/**
 * Gestion des shortcodes en relation aux followers.
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
class Search_Bar_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_shortcode( 'task_manager_search_bar', array( $this, 'callback_task_manager_search_bar' ) );
	}

	/**
	 * Permet d'afficher la barre de recherche en haut de page.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @return HTML Le code HTML permettant d'afficher la zone de recherche
	 */
	public function callback_task_manager_search_bar( $param ) {
		global $eo_search;

		$data = Navigation_Class::g()->get_search_result( $param['term'], $param['status'], $param['task_id'], $param['point_id'], $param['post_parent'], $param['categories_id'], $param['users_id'] );
		$categories = Tag_Class::g()->get( array() );

		$param = shortcode_atts(
			array(
				'term'          => $data['term'],
				'status'        => $param['status'],
				'task_id'       => $data['task_id'],
				'point_id'      => $data['point_id'],
				'post_parent'   => $data['post_parent'],
				'categories_id' => $data['categories_id'],
				'users_id'      => $data['user_id'],
			),
			$param,
			'task_manager_search_bar'
		);

		$user_display = '';
		if ( ! empty( $param['users_id'] ) ) {
			$user         = get_userdata( $param['users_id'] );
			$user_display = $user->display_name;
		}

		$eo_search->register_search(
			'tm_search_admin',
			array(
				'class'       => 'user-searchbar',
				'label'        => '',
				'icon'         => 'fa-user',
				'type'         => 'user',
				'name'         => 'user_id',
				'hidden_value' => $data['user_id'],
				'value'        => $user_display,
				'placeholder'  => 'Auteur',
				'args'         => array(
					'role' => 'administrator',
				),
			)
		);

		$parent_display = '';
		$parent_id      = 0;

		if ( ! empty( $param['post_parent'] ) ) {
			$parent = get_post( $param['post_parent'] );
		}

		if ( ! empty( $parent ) && 'wpshop_customers' == $parent->post_type ) {
			$parent_display = $parent->post_title;
			$parent_id      = $parent->ID;
		}

		$eo_search->register_search(
			'tm_search_customer',
			array(
				'label'       => '',
				'icon'        => 'fa-search',
				'type'        => 'post',
				'name'        => 'post_parent',
				'placeholder' => 'Client',
				'hidden_value' => $parent_id,
				'value'        => $parent_display,
				'args'        => array(
					'post_type'   => 'wpshop_customers',
					'post_status' => array( 'publish', 'inherit', 'draft' ),
				),
			)
		);

		$parent_display = '';
		$parent_id      = 0;
		if ( ! empty( $parent ) && 'wpshop_shop_order' == $parent->post_type ) {
			$parent_display = $parent->post_title;
			$parent_id      = $parent->ID;
		}

		$eo_search->register_search(
			'tm_search_order',
			array(
				'label'       => 'Commande',
				'icon'        => 'fa-search',
				'type'        => 'post',
				'name'        => 'post_parent_order',
				'next_action' => 'search_order',
				'value'       => $parent_display,
				'args'        => array(
					'post_type'   => 'wpshop_shop_order',
					'post_status' => array( 'publish', 'inherit', 'draft' ),
				),
			)
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'navigation',
			'backend/navigation-advenced-search',
			array(
				'categories'  => $categories,
				'param'       => $param,
				'eo_search'   => $eo_search,
				'data'        => $data,
			)
		);

		return ob_get_clean();
	}

}

new Search_Bar_Shortcode();
