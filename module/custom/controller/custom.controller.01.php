<?php
/**
 * Fichier de définition de la classe permettant de gérer les tâches dans les pages d'éditions des posts/custom post type
 *
 * @package task-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'custom_controller_01' ) ) {

	/**
	 * Classe de gestion de l'affichage des tâches dans les pages d'éditions des posts/custom post type
	 */
	class Custom_Controller {

		/**
		 * Récupération du temps total passé sur un élement de type post/custom post type
		 *
		 * @var integer
		 */
		var $total_element_elapsed_time = 0;

		/**
		 * Appel des fonctions d'accroche permettant d'étendre les affichages et les actions
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'callback_add_meta_boxes' ), 10, 2 );

			add_action( 'task_content', array( $this, 'callback_time_summary' ), 5, 2 );
		}

		/**
		 * Ajout de la metabox permettant d'afficher les tâches dans les éléments
		 *
		 * @param string  $post_type Le type de l'élément sur lequel on se trouve.
		 * @param WP_Post $post      La définition complète de l'élément sur lequel on se trouve.
		 */
		public function callback_add_meta_boxes( $post_type, $post ) {
			add_meta_box( 'wpeo-task-metabox', __( 'Task', 'task-manager' ), array( $this, 'callback_render_metabox' ), $post_type, 'normal', 'default' );
		}

		/**
		 * Fonction de callback pour l'afficahge de la metabox dans les éléments post
		 *
		 * @param  WP_Post $post La définition complète de l'élément sur lequel on se trouve.
		 */
		public function callback_render_metabox( $post ) {
			require_once( wpeo_template_01::get_template_part( WPEO_CUSTOM_DIR, WPEO_CUSTOM_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
		}

		/**
		 * Fonction de callback permettant le calcul du temps total passé sur les tâches d'un élément
		 *
		 * @param  string $string Le contenu actuel du filter appelé.
		 * @param  Object $task   La tâche courante ou l'on souhaite récupèrer les temps passé.
		 */
		public function callback_time_summary( $string, $task ) {
			$this->total_element_elapsed_time += $task->option['time_info']['elapsed'];
		}

	}

	$custom_controller = new Custom_Controller();

}
