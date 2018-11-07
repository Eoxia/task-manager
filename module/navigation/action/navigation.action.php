<?php
/**
 * Initialise les actions liées à la barre de recherche.
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
 * Les actions relatives aux tâches.
 */
class Navigation_Action {

	/**
	 * Initialise les actions liées à la barre de recherche.
	 *
	 * @since 1.0.0
	 * @version 1.3.6
	 */
	public function __construct() {
		add_action( 'wp_ajax_search', array( $this, 'callback_search' ) );

	}

	/**
	 * Utilises le shorcode "tasks" pour récupérer les tâches correspondant au critères de la recherche.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 * @todo nonce
	 */
	public function callback_search() {
		$term                          = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$data                          = Task_Helper::parse_term( $term );

		ob_start();
		Navigation_Class::g()->display_search_result( '', 'any', $data['categories_id'], $data['users_id'] );
		$search_result_view = ob_get_clean();

		ob_start();
		echo do_shortcode( '[task id="' . $data['id'] . '" users_id="' . $data['users_id'] . '" categories_id="' . $data['categories_id'] . '" posts_per_page="' . \eoxia\Config_Util::$init['task-manager']->task->posts_per_page . '" with_wrapper="0"]' );
		$tasks_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'navigation',
			'callback_success' => 'searchedSuccess',
			'view'             => array(
				'tasks'         => $tasks_view,
				'search_result' => $search_result_view,
			),
		) );
	}
}

new Navigation_Action();
