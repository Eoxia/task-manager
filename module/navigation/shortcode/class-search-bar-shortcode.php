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
		$categories = Tag_Class::g()->get( array() );
		$followers  = Follower_Class::g()->get( array(
			'role' => 'administrator',
		) );

		$empty_user = Follower_Class::g()->get( array( 'schema' => true ), true );
		array_unshift( $followers, $empty_user );
		$param = shortcode_atts( array(
			'term'                   => '',
			'status'                 => 'any',
			'categories_id_selected' => array(),
			'follower_id_selected'   => array(),
		), $param, 'task_manager_search_bar' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/main', array(
			'categories' => $categories,
			'followers'  => $followers,
			'param'      => $param,
		) );

		return ob_get_clean();
	}

}

new Search_Bar_Shortcode();
