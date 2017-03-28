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
	 * - task_manager_dashboard_content
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

		View_Util::exec( 'navigation', 'backend/main', array(
			'categories' => $categories,
		) );
	}
}

new Search_Bar_Shortcode();
