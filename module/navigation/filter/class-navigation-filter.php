<?php
/**
 * Initialise les filtres utilisés par le "moteur de recherche" du dashboard de Task Manager
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
 * Initialise les filtres utilisés par le "moteur de recherche" du dashboard de Task Manager
 */
class Navigation_Filter {

	/**
	 * Déclaration des différents filtres utilisés dans la navigation du dashboard.
	 */
	public function __construct() {
		add_filter( 'tm_dashboard_header', array( $this, 'callback_display_main_search_bar' ), 10, 2 );
		//add_filter( 'tm_dashboard_subheader', array( $this, 'callback_display_navigation_shortcut' ), 10, 2 );
		add_filter( 'eoxia_main_header_ul_before', array( $this, 'callback_display_header_navigation_search_bar' ), 10, 2 );
		add_filter( 'eoxia_main_header_li', array( $this, 'callback_display_header_navigation' ), 10, 2 );
		add_filter( 'eoxia_main_header_nav_bottom', array( $this, 'callback_display_header_navigation_bottom' ), 10, 2 );
	}

	/**
	 * Affichage du formulaire principal de recherche.
	 *
	 * @param string $content             Le contenu actuel de ce qui doit être affiché au travers du filtre.
	 * @param array  $current_search_args Les paramètres de la recherche.
	 *
	 * @return string          Le nouveau contenu modifié par notre filtre pour affichage.
	 */
	public function callback_display_main_search_bar( $content, $current_search_args ) {
		$shortcode_final_args = '';
		foreach ( $current_search_args as $shortcode_params_key => $shortcode_params_value ) {
			$shortcode_final_args .= $shortcode_params_key . '="' . $shortcode_params_value . '" ';
		}

		$content .= do_shortcode( '[task_manager_search_bar ' . $shortcode_final_args . ']' );

		return $content;
	}

	/**
	 * Affichage des raccourcis de recherche.
	 *
	 * @param string $content             Le contenu actuel de ce qui doit être affiché au travers du filtre.
	 * @param array  $current_search_args Les paramètres de la recherche.
	 *
	 * @return string          Le nouveau contenu modifié par notre filtre pour affichage.
	 */
	public function callback_display_navigation_shortcut( $content, $current_search_args  ) {

		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );
		$shortcuts = $shortcuts[0]['child'];

		$url = $_SERVER['REQUEST_URI'];
		$url = explode( '?', $url );

		if ( ! empty( $url[1] ) ) {
			$url = '?' . $url[1];
		}

		$current_folder_key = null;

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'navigation',
			'backend/navigation-shortcut',
			array(
				'search_args' => $current_search_args,
				'shortcuts'   => $shortcuts,
				'url'         => $url,
			)
		);
		$content .= ob_get_clean();

		return $content;
	}

	public function callback_display_header_navigation_search_bar ( $content ) {
		if ( isset( $_GET['notification'] ) ) {
			update_post_meta( (int) $_GET['notification'], 'read', 1 );
		}

		if ( $_GET['page'] == "wpeomtm-dashboard" || $_GET['page'] == "tm-my-tasks" || $_GET['page'] == "tm-notification" || $_GET['page'] == "tm-dashboard" || $_GET['page'] == "indicator-page"   ) {
			$notifications = get_posts( array(
				'post_type'    => 'wpeo-notification',
				'numberposts'  => 6,
				'post_status'  => 'publish',
				'author'       => get_current_user_id(),
				'meta_key'     => 'read',
				'meta_compare' => '!=',
				'meta_value'   => 1,
			) );

			if ( ! empty( $notifications ) ) {
				foreach ( $notifications as &$notification ) {
					$notification = Notify_Class::g()->get_notification_data( $notification );
				}
			}

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'navigation',
				'backend/navigation-header-search-bar',
				array(
					'notifications' => $notifications,
					'number_notifications' => count( $notifications ) > 5 ? '5+' : count( $notifications ),
				)
			);
			$content .= ob_get_clean();

		}
			return $content;
	}

	public function callback_display_header_navigation( $content ) {
		if ( 'wpeomtm-dashboard' === $_REQUEST['page'] && ! isset( $_GET['id'] ) ) {
			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'navigation',
				'backend/navigation-header-button'
			);
			$content .= ob_get_clean();
		}
			return $content;
	}

	public function callback_display_header_navigation_bottom( $content ) {
		if ( $_GET['page'] == "wpeomtm-dashboard" || $_GET['page'] == "tm-my-tasks" || wp_doing_ajax() ) {
			$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );
			$shortcuts = $shortcuts[0]['child'];

			$url = $_SERVER['REQUEST_URI'];
			$url = explode( '?', $url );

			if ( ! empty( $url[1] ) ) {
				$url = '?' . $url[1];
			}

			$current_folder_key = null;

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'navigation',
				'backend/navigation-shortcut',
				array(
					//'search_args' => $current_search_args,
					'shortcuts' => $shortcuts,
					'url'       => $url,
				)
			);
			$content .= ob_get_clean();

		}
			return $content;
	}
}

new Navigation_Filter();
