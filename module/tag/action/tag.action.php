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
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 99 );

		add_action( 'wp_ajax_to_archive', array( $this, 'ajax_to_archive' ) );
		add_action( 'wp_ajax_to_unarchive', array( $this, 'ajax_to_unarchive' ) );
		/** Chargement des tags existants pour affectation */
		add_action( 'wp_ajax_load_tags', array( $this, 'ajax_load_tags' ) );
		add_action( 'wp_ajax_close_tag_edit_mode', array( $this, 'ajax_close_tag_edit_mode' ) );

		/** Affectation d'un ou plusieurs tag */
		add_action( 'wp_ajax_tag_affectation', array( $this, 'ajax_tag_affectation' ) );
		add_action( 'wp_ajax_tag_unaffectation', array( $this, 'ajax_tag_unaffectation' ) );

		/** Création d'un tag */
		add_action( 'wp_ajax_create_tag', array( $this, 'ajax_create_tag' ) );
	}

	/**
	 * Ajoutes un sous menu "Categories" qui renvoie vers la page pour créer les catégories de Task Manager.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'wpeomtm-dashboard', __( 'Categories', 'task-manager' ), __( 'Categories', 'task-manager' ), 'manage_task_manager', 'edit-tags.php?taxonomy=wpeo_tag' );
	}

	/**
	 * Passes la tâche en status "archive".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_to_archive() {
		check_ajax_referer( 'to_unarchive' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		$task->status = 'archive';

		Task_Class::g()->update( $task );

		do_action( 'tm_archive_task', $task );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'archivedTaskSuccess',
		) );
	}

	/**
	 * Passes la tâche en status "publish".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_to_unarchive() {
		check_ajax_referer( 'to_archive' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => 'archive',
		), true );

		$task->status = 'publish';

		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'unarchivedTaskSuccess',
		) );
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

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'tag', 'backend/main-edit', array(
			'tags' => $tags,
			'task' => $task,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'loadedTagSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Repasses la fenêtre des catégories en mode "vue".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_close_tag_edit_mode() {
		check_ajax_referer( 'close_tag_edit_mode' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		ob_start();
		echo do_shortcode( '[task_manager_task_tag task_id=' . $task_id . ']' );
		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'closedTagEditMode',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Affectation d'un tag a un élément
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_tag_affectation() {
		check_ajax_referer( 'tag_affectation' );

		/** Récupération de l'identifiant du tag a associer */
		$tag_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		if ( empty( $tag_id ) || empty( $task_id ) || empty( $task ) ) {
			wp_send_json_error();
		}

		$task->taxonomy[ Tag_Class::g()->get_taxonomy() ][] = $tag_id;
		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'unaffectedTagSuccess',
			'nonce' => wp_create_nonce( 'tag_unaffectation' ),
		) );
	}

	/**
	 * Désaffecte un tag d'un élément
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 * @todo taxonomy en dur
	 */
	public function ajax_tag_unaffectation() {
		check_ajax_referer( 'tag_unaffectation' );

		$tag_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $tag_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		wp_remove_object_terms( $task_id, $tag_id, 'wpeo_tag' );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'affectedTagSuccess',
			'nonce' => wp_create_nonce( 'tag_affectation' ),
		) );
	}

	/**
	 * Création d'un tag dans la base de données
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_create_tag() {
		check_ajax_referer( 'create_tag' );

		$tag_name = ! empty( $_POST['tag_name'] ) ? sanitize_text_field( $_POST['tag_name'] ) : '';

		if ( empty( $tag_name ) ) {
			wp_send_json_error();
		}

		$term = wp_create_term( $tag_name, Tag_Class::g()->get_taxonomy() );
		$category = Tag_Class::g()->get( array(
			'include' => array( $term['term_id'] ),
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/tag', array(
			'category' => $category,
		) );
		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'tag',
			'callback_success' => 'createdTagSuccess',
			'view' => ob_get_clean(),
		) );
	}

}

new Tag_Action();
