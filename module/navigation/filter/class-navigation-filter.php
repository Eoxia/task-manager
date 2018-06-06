<?php
/**
 * Initialise les filtres utilisés par le "moteur de recherche"
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.8.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux tâches.
 */
class Navigation_Filter {

	public function __construct() {
		add_filter( 'tm_dashboard_header', array( $this, 'callback_display_main_search_bar' ) );
		add_filter( 'tm_dashboard_subheader', array( $this, 'callback_display_navigation_shortcut' ) );
	}

	public function callback_display_main_search_bar( $content ) {
		$content .= do_shortcode( '[task_manager_search_bar term="' . $term . '" categories_id_selected="' . $categories_id_selected . '" follower_id_selected="' . $follower_id_selected . '"]' );

		return $content;
	}

	public function callback_display_navigation_shortcut( $content ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/navigation-shortcut', array() );
		$content .= ob_get_clean();

		return $content;
	}

}

new Navigation_Filter();
