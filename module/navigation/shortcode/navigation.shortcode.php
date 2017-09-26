<?php
/**
 * Gestion des shortcodes en relation aux followers.
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
class Search_Bar_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_shortcode( 'task_manager_search_bar', array( $this, 'callback_task_manager_search_bar' ) );
	}

	/**
	 * Permet d'afficher la barre de recherche en haut de page.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_task_manager_search_bar( $param ) {
		$categories = Tag_Class::g()->get( array() );
		$followers = Follower_Class::g()->get( array(
			'role' => 'administrator',
		) );

		$empty_user = new \StdClass();
		$empty_user->id = '';
		$empty_user->displayname = '';
		array_unshift( $followers, $empty_user );

		$param = shortcode_atts( array(
			'term' => '',
			'categories_id_selected' => array(),
			'follower_id_selected' => array(),
		), $param, 'task_manager_search_bar' );

		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/main', array(
			'categories' => $categories,
			'followers' => $followers,
			'param' => $param,
		) );
	}
}

new Search_Bar_Shortcode();
