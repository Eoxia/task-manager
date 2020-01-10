<?php
/**
 * Gestion des actions des catégories.
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
 * Gestion des actions des catégories.
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
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'wpeomtm-dashboard', __( 'Categories', 'task-manager' ), __( 'Categories', 'task-manager' ), 'manage_task_manager', 'edit-tags.php?taxonomy=wpeo_tag' );
	}

	/**
	 * Passes la tâche en status "archive".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_to_archive() {
//		check_ajax_referer( 'to_unarchive' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['status'] = 'archive';

		$archive_term = get_term_by( 'slug', 'archive', Tag_Class::g()->get_type() );

		if ( ! empty( $archive_term->term_id ) ) {
			$task->data['taxonomy'][ Tag_Class::g()->get_type() ][] = $archive_term->term_id;
		}

		Task_Class::g()->update( $task->data, true );

		do_action( 'tm_archive_task', $task );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'archivedTaskSuccess',
			)
		);
	}

	/**
	 * Passes la tâche en status "publish".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_to_unarchive() {
		check_ajax_referer( 'to_archive' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['status'] = 'publish';

		$archive_term = get_term_by( 'slug', 'archive', Tag_Class::g()->get_type() );

		if ( ! empty( $archive_term->term_id ) ) {
			$key = array_search( $archive_term->term_id, $task->data['taxonomy'][ Tag_Class::g()->get_type() ], true );
			if ( false !== $key ) {
				array_splice( $task->data['taxonomy'][ Tag_Class::g()->get_type() ], $key, 1 );
			}

			wp_remove_object_terms( $task_id, $archive_term->term_id, Tag_Class::g()->get_type() );
		}

		Task_Class::g()->update( $task->data, true );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'unarchivedTaskSuccess',
			)
		);
	}

	/**
	 * Récupère les tags existants dans la base et les retournent pour affichage
	 *
	 * @since 1.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_tags() {
		check_ajax_referer( 'load_tags' );

		$tags    = Tag_Class::g()->get();
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'tag',
			'backend/main-edit',
			array(
				'tags' => $tags,
				'task' => $task,
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'loadedTagSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Repasses la fenêtre des catégories en mode "vue".
	 *
	 * @return void
	 *
	 * @since 1.0.0
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
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'closedTagEditMode',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Affectation d'un tag a un élément
	 * Si le slug est "archive" de la catégorie, passes le status de la tâche en archive.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_tag_affectation() {
		check_ajax_referer( 'tag_affectation' );

		/** Récupération de l'identifiant du tag a associer */
		$tag_id  = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		$archive_term  = get_term_by( 'slug', 'archive', Tag_Class::g()->get_type() );
		$go_to_archive = false;

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		if ( empty( $tag_id ) || empty( $task_id ) || empty( $task ) ) {
			wp_send_json_error();
		}

		$task->data['taxonomy'][ Tag_Class::g()->get_type() ][] = $tag_id;

		if ( $archive_term && $archive_term->term_id === $tag_id ) {
			$task->data['status'] = 'archive';
			$go_to_archive        = true;
		}

		Task_Class::g()->update( $task->data, true );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'affectedTagSuccess',
				'nonce'            => wp_create_nonce( 'tag_unaffectation' ),
				'go_to_archive'    => $go_to_archive,
			)
		);
	}

	/**
	 * Désaffecte un tag d'un élément
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_tag_unaffectation() {
		check_ajax_referer( 'tag_unaffectation' );

		$tag_id  = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $tag_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$go_to_all_task = false;
		$archive_term   = get_term_by( 'slug', 'archive', Tag_Class::g()->get_type() );

		if ( $archive_term && $archive_term->term_id === $tag_id ) {
			$task->data['status'] = 'publish';
			$go_to_all_task       = true;
		}

		Task_Class::g()->update( $task->data, true );

		wp_remove_object_terms( $task_id, $tag_id, Tag_Class::g()->get_type() );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'unaffectedTagSuccess',
				'nonce'            => wp_create_nonce( 'tag_affectation' ),
				'go_to_all_task'   => $go_to_all_task,
			)
		);
	}

	/**
	 * Création d'un tag dans la base de données
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_create_tag() {
		check_ajax_referer( 'create_tag' );

		$tag_name = ! empty( $_POST['tag_name'] ) ? sanitize_text_field( $_POST['tag_name'] ) : '';

		if ( empty( $tag_name ) ) {
			wp_send_json_error();
		}

		$term     = wp_create_term( $tag_name, Tag_Class::g()->get_type() );
		$category = Tag_Class::g()->get(
			array(
				'include' => array( $term['term_id'] ),
			),
			true
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'navigation',
			'backend/tag',
			array(
				'category' => $category,
			)
		);
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'tag',
				'callback_success' => 'createdTagSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

}

new Tag_Action();
