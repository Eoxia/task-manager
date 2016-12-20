<?php
/**
 * Fichier permettant de gérer l'affichage des tâches selon le type d'élément sur lequel il est associé
 *
 * @package Task Manager
 * @subpackage Module/Liaison-Post-Type
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe permettant de gérer l'affichage des tâches selon le type d'élément sur lequel il est associé
 *
 * @package Task Manager
 */
class Liaison_Post_Type {

	/**
	 * Instanciation
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'callback_add_meta_boxes' ), 10, 2 );
	}

	/**
	 * Appel des différentes metabox pour l'affichage des tâches dans les différents type d'éléments
	 *
	 * @param string  $post_type Le type de l'élément sur lequel l'utilisateur se trouve dans l'administration.
	 * @param WP_POST $post      L'élément sur lequel l'utilisateur se trouve.
	 */
	public function callback_add_meta_boxes( $post_type, $post ) {
		add_meta_box( 'wpeo-task-metabox', __( 'Task', 'task-manager' ), array( $this, 'callback_render_metabox' ), $post_type, 'normal', 'default' );
	}

	/**
	 * Affiche les tâches pour un post type
	 *
	 * @param  WP_Post $post Le post courrant pour lequel il faut afficher les tâches.
	 */
	public function callback_render_metabox( $post ) {
		View_Util::exec( 'liaison-post-type', 'backend/main', array( 'post' => $post ) );
	}

}

new Liaison_Post_Type();
