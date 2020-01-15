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
class Shortcut_Action {

	/**
	 * Initialise les actions liées à la barre de recherche.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'fix_shortcuts' ) );
		add_action( 'wp_ajax_load_modal_create_shortcut', array( $this, 'callback_load_modal_create_shortcut' ) );
		add_action( 'wp_ajax_create_shortcut', array( $this, 'callback_create_shortcut' ) );
		add_action( 'wp_ajax_load_handle_shortcut', array( $this, 'callback_load_handle_shortcut' ) );
		add_action( 'wp_ajax_delete_shortcut', array( $this, 'callback_delete_shortcut' ) );
		add_action( 'wp_ajax_display_edit_shortcut_name', array( $this, 'callback_display_edit_shortcut_name' ) );
		add_action( 'wp_ajax_edit_shortcut_name', array( $this, 'callback_edit_shortcut_name' ) );
		add_action( 'wp_ajax_create_folder_shortcut', array( $this, 'create_folder_shortcut' ) );
		add_action( 'wp_ajax_tm_save_order_shortcut', array( $this, 'save_order' ) );

	}

	public function fix_shortcuts() {
		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		if ( isset( $shortcuts['wpeomtm-dashboard'] ) ) {
			$new_shortcuts = array();

			$new_shortcuts[] = array(
				'label' => 'Shortcuts',
				'page' => 'null',
				'type' => 'folder',
				'id'  => 0,
				'child' => array(),
			);

			foreach( $shortcuts['wpeomtm-dashboard'] as $key => $shortcut ) {
				$shortcut['id'] = $key + 1;
				$shortcut['child'] = array();
				$new_shortcuts[0]['child'][] = $shortcut;
			}

			update_user_meta( get_current_user_id(), '_tm_shortcuts', $new_shortcuts );
		}
	}

	/**
	 * Charge la creation du shortcode
	 *
	 * @return void
	 */
	public function callback_load_modal_create_shortcut() {
		$term          = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$task_id       = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id      = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$post_parent   = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$categories_id = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : '';
		$user_id       = ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : '';

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-create-shortcut'
		);
		$template = ob_get_clean();


		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-create-shortcut-content',
			array(
				'term'          => $term,
				'task_id'       => $task_id,
				'point_id'      => $point_id,
				'post_parent'   => $post_parent,
				'categories_id' => $categories_id,
				'user_id'       => $user_id,
			)
		);
		$content = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'modal-create-shortcut-buttons' );
		$buttons = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'shortcut',
				'callback_success' => 'LoadedShortcutSuccess',
				'template'     => $template,
				'view'         => $content,
				'buttons_view' => $buttons,
			)
		);
	}

	/**
	 * Créer le shortcode
	 *
	 * @return void
	 */
	public function callback_create_shortcut() {
		$name                  = ! empty( $_POST['shortcut_name'] ) ? sanitize_text_field( $_POST['shortcut_name'] ) : '';
		$data                  = array();
		$data['term']          = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$data['task_id']       = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$data['point_id']      = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$data['post_parent']   = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$data['categories_id'] = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : '';
		$data['user_id']       = ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : '';

		$construct_args = '';

		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( ! empty( $value ) ) {
					$construct_args .= '&' . $key . '=' . $value;
				}
			}
		}

		if ( empty( $name ) || empty( $construct_args ) ) {
			wp_send_json_error();
		}

		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		$shortcut = array(
			'label' => $name,
			'page'  => 'admin.php',
			'link'  => '?page=wpeomtm-dashboard' . $construct_args,
			'id'    => Shortcut_Class::g()->get_last_id( $shortcuts ),
		);

		$shortcuts[0]['child'][] = $shortcut;

		update_user_meta( get_current_user_id(), '_tm_shortcuts', $shortcuts );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'modal-create-shortcut-content-success' );
		$content_success = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'modal-create-shortcut-button-success' );
		$button_success = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'shortcut',
			array(
				'url'      => '',
				'shortcut' => $shortcut,
				'new'      => true,
				'key'      => count( $shortcuts['wpeomtm-dashboard'] ),
			)
		);
		$view_shortcut = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'shortcut',
				'callback_success' => 'createdShortcutSuccess',
				'view_button'      => $button_success,
				'view_content'     => $content_success,
				'view_shortcut'    => $view_shortcut,
			)
		);
	}

	/**
	 * Manipule le shortcode
	 *
	 * @return void
	 */
	public function callback_load_handle_shortcut() {
		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-handle-shortcut',
			array(
				'shortcuts' => $shortcuts,
			)
		);
		$view = ob_get_clean();


		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'modal-title' );
		$title_view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'shortcut', 'modal-handle-shortcut-buttons' );
		$buttons_view = ob_get_clean();
		wp_send_json_success(
			array(
				'view'         => $view,
				'modal_title'  => $title_view,
				'buttons_view' => $buttons_view,
			)
		);
	}

	/**
	 * Delete shorcut
	 *
	 * @return void
	 */
	public function callback_delete_shortcut() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : -1;

		if ( -1 == $id ) {
			wp_send_json_error();
		}

		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		$shortcuts[0]['child'] = Shortcut_Class::g()->delete_id( $shortcuts[0]['child'], $id );

		update_user_meta( get_current_user_id(), '_tm_shortcuts', $shortcuts );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'shortcut',
				'callback_success' => 'deletedShortcutSuccess',
			)
		);
	}

	public function callback_display_edit_shortcut_name(){
		$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : -1;
		$parent_id       = isset( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : -1;


		if ( -1 == $id ) {
			wp_send_json_error();
		}

		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );
		$shortcut  = Shortcut_Class::g()->get_shortcut_by_id( $id, $shortcuts );

		$key = Shortcut_Class::g()->get_key_by_id( $id, $shortcuts[0]['child'] );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-handle-shortcut-edit-item',
			array(
				'shortcut' => $shortcut,
				'parent_id' => $parent_id,
				'key'       => $key,
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'shortcut',
				'callback_success' => 'displayEditShortcutSuccess',
				'view'             => ob_get_clean()
			)
		);
	}

	public function callback_edit_shortcut_name() {
		$id       = isset( $_POST['id'] ) ? (int) $_POST['id'] : -1;
		$parent_id       = isset( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : -1;
		$name     = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$old_name = ! empty( $_POST['old_name'] ) ? sanitize_text_field( $_POST['old_name'] ) : '';

		if ( -1 == $id ) {
			wp_send_json_error();
		}

		if( $name == "" ){
			$name = $old_name;
		}

		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		$shortcuts[0]['child'] = Shortcut_Class::g()->update_name( $shortcuts[0]['child'], $id, $name );

		update_user_meta( get_current_user_id(), '_tm_shortcuts', $shortcuts );

		$shortcut = Shortcut_Class::g()->get_shortcut_by_id( $id, $shortcuts );

		$key = Shortcut_Class::g()->get_key_by_id( $id, $shortcuts[0]['child'] );


		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-handle-shortcut-item',
			array(
				'shortcut'  => $shortcut,
				'key'       => $key,
				'parent_id' => $parent_id,
			)
		);
		$view = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'shortcut',
				'callback_success' => 'editShortcutSuccess',
				'view'             => $view,
				'id'               => $id,
				'name'             => $name
			)
		);
	}

	public function create_folder_shortcut() {
		$name      = ! empty( $_POST['folder_name'] ) ? sanitize_text_field( $_POST['folder_name'] ) : __( 'No name', 'task-manager' );
		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		$shortcut = array(
			'type'  => 'folder',
			'label' => $name,
			'page'  => null,
			'link'  => null,
			'id'    => Shortcut_Class::g()->get_last_id( $shortcuts ),
			'child' => array(),
		);

		$shortcuts[0]['child'][] = $shortcut;

		$key = Shortcut_Class::g()->get_key_by_id( $shortcut['id'], $shortcuts[0]['child'] );

		update_user_meta( get_current_user_id(), '_tm_shortcuts', $shortcuts );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-handle-shortcut-items',
			array(
				'shortcuts' => $shortcut['child'],
				'parent_id' => 0,
				'id'        => $shortcut['id'],
				'key'       => $key,
			)
		);
		$view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'modal-handle-shortcut-item',
			array(
				'shortcut' => $shortcut,
				'parent_id' => 0,
				'key'       => $key,
			)
		);
		$new_item = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'shortcut',
			'tree-item',
			array(
				'shortcut' => $shortcut,
			)
		);
		$tree_item_view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'shortcut',
			'callback_success' => 'createdFolderShortcutSuccess',
			'view'             => $view,
			'new_item'         => $new_item,
			'tree_item_view'   => $tree_item_view,
		) );
	}

	public function save_order() {
		$order_shortcut = ! empty( $_POST['order_shortcut'] ) ? (array) $_POST['order_shortcut'] : array();
		$shortcuts = get_user_meta( get_current_user_id(), '_tm_shortcuts', true );

		$shortcuts = Shortcut_Class::g()->first_level( $shortcuts[0]['child'] );


		$new_order = array();
		$new_order[] = array(
			'label' => 'Shortcuts',
			'page' => 'null',
			'type' => 'folder',
			'id'  => 0,
			'child' => array(),
		);

		if ( ! empty( $order_shortcut ) ) {
			foreach ( $order_shortcut as $parent_id => $parent ) {
				foreach( $parent as $key => $id ) {
					if ( 0 === $parent_id ) {
						$new_order[0]['child'][ $key ] = Shortcut_Class::g()->get_shortcut_by_id( $id, $shortcuts );
					} else {
						$new_order[0]['child'][ $parent_id ]['child'][ $key ] = Shortcut_Class::g()->get_shortcut_by_id( $id, $shortcuts );
					}
				}
			}
		}

		update_user_meta( get_current_user_id(), '_tm_shortcuts', $new_order );

		ob_start();
		//echo apply_filters('tm_dashboard_subheader', '', ''); // WPCS: XSS ok.
		echo apply_filters( 'eoxia_main_header_nav_bottom' );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'shortcut',
			'callback_success' => 'savedOrder',
			'view'             => $view,
		) );
	}
}

new Shortcut_Action();
