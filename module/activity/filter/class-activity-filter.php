<?php
/**
 * Gestions des filtres utilisés pour l'affichage de la dernière activité dans un post.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import\Filter
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestions des filtres utilisés pour laffichage de la dernière activité dans un post.
 */
class Activity_Filter {

	/**
	 * Appels des initialisations des filtres pour l'import des tâches.
	 */
	public function __construct() {
		add_filter( 'tm_posts_metabox_project_dashboard', array( $this, 'post_last_activity' ), 10, 3 );
	}

	/**
	 * Ajoute le bouton permettant d'importer des tâches avec du contenu sur un POST.
	 *
	 * @param string  $current_output Le contenu actuel à afficher.
	 * @param WP_POST $post          Le post sur lequel le filtre est actuellement appelé.
	 * @param array   $tasks           Le post sur lequel le filtre est actuellement appelé.
	 *
	 * @return string La vue avec notre bouton en plus.
	 */
	public function post_last_activity( $current_output, $post, $tasks ) {
		$task_ids_for_history = array();
		foreach ( $tasks[ $post->ID ]['data'] as $task ) {
			$task_ids_for_history[] = $task->data['id'];
		}

		$datas = Activity_Class::g()->get_activity( $task_ids_for_history, 0, 1 );

		$last_date = $datas['last_date'];
		unset( $datas['last_date'] );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/list', array(
			'datas'     => $datas,
			'last_date' => $last_date,
			'offset'    => 0,
		) );
		$last_activity = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/post-last-activity', array(
			'post'                 => $post,
			'task_ids_for_history' => implode( ',', $task_ids_for_history ),
			'last_activity'        => $last_activity,
			'last_date'            => \eoxia\Date_Util::g()->fill_date( $last_date ),
		) );
		$current_output .= ob_get_clean();

		return $current_output;
	}

}

new Activity_Filter();
