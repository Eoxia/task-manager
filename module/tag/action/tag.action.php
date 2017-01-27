<?php
/**
 * Fichier de gestion des "actions" pour les tags
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des "actions" pour les tags
 */
class Tag_Action {

	/**
	 * Instanciation des crochets pour les "actions" utilisées par les tags
	 */
	public function __construct() {
		/** Chargement des tags existants pour affectation */
		add_action( 'wp_ajax_load_tags', array( $this, 'ajax_load_tags' ) );

		/** Affectation d'un ou plusieurs tag */
		add_action( 'wp_ajax_tag_affectation', array( $this, 'ajax_tag_affectation' ) );
		add_action( 'wp_ajax_tag_unaffectation', array( $this, 'ajax_tag_unaffectation' ) );

		/** Chargement des tâches ayant le tag "archive" */
		// add_action( 'wp_ajax_load_archived_task', array( $this, 'load_archived_task' ) );

		/** Création d'un tag */
		add_action( 'wp_ajax_create-tag', array( &$this, 'ajax_create_tag' ) );
	}

	/**
	 * Récupère les tags existants dans la base et les retournent pour affichage
	 */
	public function ajax_load_tags() {
		check_ajax_referer( 'load_tags' );

		$list_tag = Tag_Class::g()->get();
		$element_id = ! empty( $_POST ) && ! empty( $_POST['id'] ) && is_int( (int) $_POST['id'] ) && ( 0 !== (int) $_POST['id'] ) ? (int) $_POST['id'] : 0;

		ob_start();
		View_Util::exec( 'tag', 'backend/tag', array( 'list_tag' => $list_tag, 'object' => Task_Class::g()->get( array( 'id' => $element_id ), true ) ) );
		$tags_display = ob_get_clean();

		wp_send_json_success( array( 'module' => 'tag', 'callback_success' => 'load_tag_success', 'view' => $tags_display ) );
	}

	/**
	 * Affectation d'un tag a un élément
	 */
	function ajax_tag_affectation() {
		check_ajax_referer( 'tag_affectation' );

		/** Récupération de l'identifiant du tag a associer */
		$tag_id = ! empty( $_POST ) && ! empty( $_POST['id'] ) && is_int( (int) $_POST['id'] ) && ( 0 !== (int) $_POST['id'] ) ? (int) $_POST['id'] : 0;

		/** On récupère l'élément sur lequel on va devoir associer le tag */
		$element_id = ! empty( $_POST ) && ! empty( $_POST['parent_id'] ) && is_int( (int) $_POST['parent_id'] ) && ( 0 !== (int) $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$task = Task_Class::g()->get( array( 'id' => $element_id ), true );

		/** On vérifie si le tag est déjà associé ou bien si il faut l'associer */
		$selected = ! empty( $_POST['selected'] ) && ( 'yes' === $_POST['selected'] ) ? true : false;

		/** Récupération de la définition du tag "archive" dans le cas ou c'est ce tag qu'il faut associer ou dissocier des actions supplémentaires sont a effectuer */
		$archive_tag = get_term_by( 'slug', 'archive', Tag_Class::g()->get_taxonomy() );

		if ( null !== $task ) {
			$task->taxonomy[ Tag_Class::g()->get_taxonomy() ][] = $tag_id;
			if ( $tag_id === $archive_tag->term_id ) {
				$task->status = 'archive';
			}

			Task_Class::g()->update( $task );

			log_class::g()->exec( 'task_manager_tag', 'task_manager_tag', sprintf( __( 'The tag #%1$d have been successfully added to task #%2$s by the user %3$d', 'task-manager' ), $tag_id, $element_id, get_current_user_id() ) );
		} else {
			log_class::g()->exec( 'task_manager_tag', 'task_manager_tag', sprintf( __( 'We are unable to get the task #%1$d where to affect the tag #%2$s', 'task-manager' ), $element_id, $tag_id ) );
		}

		$tags_display = 'yo toto';
		wp_send_json_success( array( 'module' => 'tag', 'callback_success' => 'tag_affectation_success', 'view' => $tags_display ) );
	}

	/**
	 * Récupère les tâches archivées
	 */
	// public function load_archived_task() {
	// 	check_ajax_referer( 'load_archived_task' );
	//
	// 	$list_tag = Tag_Class::g()->get();
	//
	// 	ob_start();
	// 	View_Util::exec( 'tag', 'backend/display-tag', array( 'list_tag' => $list_tag ) );
	//
	// 	wp_send_json_success( array( 'module' => 'tag', 'callback_success' => 'load_archived_task', 'view' => ob_get_clean() ) );
	// }

	/**
	 * Création d'un tag dans la base de données
	 */
	public function ajax_create_tag() {
		$response = array();

		$term = wp_create_term( $_POST['tag_name'], $tag_controller->get_taxonomy() );
		$response = Tag_Class::g()->show( $term['term_id'] );

		wp_send_json_success( $response );
	}

}

new Tag_Action();
