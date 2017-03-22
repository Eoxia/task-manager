<?php
/**
 * Fichier de gestion des "actions" pour les tags
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
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
		add_action( 'wp_ajax_to_archive', array( $this, 'ajax_to_archive' ) );
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
	 * Ajoutes la catégorie "archive" à le tâche ainsi que le status "archive".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_to_archive() {
		check_ajax_referer( 'to_archive' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		$task->status = 'archive';

		Task_Class::g()->update( $task );

		wp_send_json_success();
	}

	/**
	 * Récupère les tags existants dans la base et les retournent pour affichage
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_tags() {
		check_ajax_referer( 'load_tags' );

		$tags = Tag_Class::g()->get();
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		ob_start();
		View_Util::exec( 'tag', 'backend/list-tag-edit', array(
			'tags' => $tags,
			'task' => Task_Class::g()->get( array(
				'id' => $task_id,
			), true ),
		) );

		wp_send_json_success( array(
			'module' => 'tag',
			'callback_success' => 'loadedTagSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Affectation d'un tag a un élément
	 */
	function ajax_tag_affectation() {
		check_ajax_referer( 'tag_affectation' );

		/** Récupération de l'identifiant du tag a associer */
		$tag_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		if ( empty( $tag_id ) || empty( $task_id ) || empty( $task ) ) {
			wp_send_json_error();
		}

		$task->taxonomy[ Tag_Class::g()->get_taxonomy() ][] = $tag_id;
		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'module' => 'tag',
			'callback_success' => 'tagAffectationSuccess',
		) );
	}

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
