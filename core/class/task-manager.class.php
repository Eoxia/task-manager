<?php
/**
 * Classes principale du plugin.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classes principale du plugin.
 */
class Task_Manager_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour l'utilisation de Singleton_Util.
	 *
	 * @since 0.1.0
	 * @version 1.5.0
	 */
	protected function construct() {}

	/**
	 * La méthode qui permet d'afficher la page
	 *
	 * @since 0.1.0
	 * @version 1.8.0
	 *
	 * @return void
	 */
	public function display() {
		$term          = ! empty( $_GET[ 'term' ] ) ? sanitize_text_field( $_GET[ 'term' ] ) : ''; // WPCS: CSRF ok.
		$categories_id = ! empty( $_GET[ 'categories_id' ] ) ? sanitize_text_field( $_GET[ 'categories_id' ] ) : ''; // WPCS: CSRF ok.
		$user_id       = ! empty( $_GET[ 'user_id' ] ) ? sanitize_text_field( $_GET[ 'user_id' ] ) : 0; // WPCS: CSRF ok.
		$post_parent   = ! empty( $_GET[ 'post_parent' ] ) ? (int) $_GET[ 'post_parent' ] : 0; // WPCS: CSRF ok.
		$task_id       = ! empty( $_GET[ 'task_id' ] ) ? sanitize_text_field( $_GET[ 'task_id' ] ) : ''; // WPCS: CSRF ok.
		$point_id      = ! empty( $_GET[ 'point_id' ] ) ? sanitize_text_field( $_GET[ 'point_id' ] ) : ''; // WPCS: CSRF ok.
		$quicktimes    = ! empty( $_GET[ 'quicktimemode' ] ) ? (int) $_GET[ 'quicktimemode' ] : 0; // WPCS: CSRF ok.

		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		$search_args = array(
			'status'        => 'any',
			'post_parent'   => $post_parent,
			'with_wrapper'  => 0,
			'term'          => $term,
			'task_id'       => $task_id,
			'point_id'      => $point_id,
			'categories_id' => $categories_id,
			'users_id'      => $user_id,
		);

		if ( isset( $_GET['notification'] ) ) {
			update_post_meta( (int) $_GET['notification'], 'read', 1 );
		}

		if ( isset( $_GET['quicktimemode'] ) ) {
			$quicktimes_real_number = $quicktimes - 1;

			Quick_Time_Class::g()->display_this_task_and_point( $quicktimes_real_number );
		} else {
			require_once PLUGIN_TASK_MANAGER_PATH . '/core/view/main.view.php';
		}

		if ( $_GET['page'] == "tm-my-tasks" ) {
			$this->display_my_task();
		} else {
			//require_once PLUGIN_TASK_MANAGER_PATH . '/core/view/main.view.php';
		}
	}

	public function display_my_task() {
		$tasks = Point_Class::g()->get( array(
			'users_id'       => array( get_current_user_id() ),
			'number' => \eoxia\Config_Util::$init['task-manager']->task->posts_per_page,
		) );

		if ( ! empty( $tasks ) ) {
			Task_Class::g()->display( $tasks );
		}
	}

	public function display_dashboard() {
		require_once PLUGIN_TASK_MANAGER_PATH . '/core/view/dashboard.view.php';
	}

	/**
	 * Récupères le patch note pour la version actuelle.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return string|object
	 */
	public function get_patch_note() {
		$patch_note_url = 'https://www.eoxia.com/wp-json/eoxia/v1/change_log/' . \eoxia\Config_Util::$init['task-manager']->version;

		$json = wp_remote_get( $patch_note_url, array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'verify_ssl' => false,
		) );


		$result = __( 'No update notes for this version.', 'digirisk' );

		if ( ! is_wp_error( $json ) && ! empty( $json ) && ! empty( $json['body'] ) ) {
			$result = json_decode( $json['body'] );
		}

		return array(
			'status'  => is_wp_error( $json ) ? false : true,
			'content' => $result,
		);
	}

	/**
	 * Initialise les données par défaut de Task Manager.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function init_default_data() {
		$current_version = get_option( \eoxia\Config_Util::$init['task-manager']->key_last_update_version, null );

		if ( null === $current_version ) {
			$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init['task-manager']->version );

			if ( 3 === strlen( $version ) ) {
				$version *= 10;
			}

			update_option( \eoxia\Config_Util::$init['task-manager']->key_last_update_version, $version );
		}
	}
}

new Task_Manager_Class();
