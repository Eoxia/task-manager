<?php
/**
 * Les actions relatives aux outils.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.8.0
 * @copyright 2018 Eoxia.
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux outils.
 */
class Tools_Action {

	/**
	 * Initialise les actions liées aux outils.
	 *
	 * @since 1.5.0
	 * @version 1.7.1
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

		add_action( 'wp_ajax_task_manager_compile_data', array( $this, 'callback_compile_data' ) );
	}

	/**
	 * Initialise le menu dans l'onglet 'Outils' du menu de WordPress.
	 *
	 * @since 1.5.0
	 * @version 1.7.1
	 *
	 * @return void
	 */
	public function callback_admin_menu() {
		add_management_page( 'Task Manager', 'Task Manager', 'manage_options', 'taskmanager-tools', array( Tools_Class::g(), 'display' ) );
	}

	/**
	 * Compiles toutes les données de Task Manager dans un fichier de cache au format JSON.
	 *
	 * @since 1.8.0
	 * @version 1.8.0
	 *
	 * @return void
	 */
	public function callback_compile_data() {
		global $wpdb;

		$data_to_compile = array(
			'last' => array(),
			'list' => array(),
		);

		$tasks    = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type='wpeo-task'" );
		$points   = $wpdb->get_results( "SELECT comment_id, comment_content FROM {$wpdb->comments} WHERE comment_type='wpeo_point'" );
		$comments = $wpdb->get_results( "SELECT comment_id, comment_content FROM {$wpdb->comments} WHERE comment_type='wpeo_time'" );

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $task ) {
				$data_to_compile['list'][ 'T' . $task->ID ] = array(
					'id'      => $task->ID,
					'content' => $task->post_title,
					'type'    => 'task',
				);
			}
		}

		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				$data_to_compile['list'][ 'P' . $point->comment_id ] = array(
					'id'      => $point->comment_id,
					'content' => $point->comment_content,
					'type'    => 'point',
				);
			}
		}

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$data_to_compile['list'][ 'C' . $comment->comment_id ] = array(
					'id'      => $comment->comment_id,
					'content' => $comment->comment_content,
					'type'    => 'comment',
				);
			}
		}

		$data_to_compile = json_encode( $data_to_compile );
		$data_to_compile = preg_replace_callback(
			'/\\\\u([0-9a-f]{4})/i',
			function ( $matches ) {
				$sym = mb_convert_encoding( pack( 'H*', $matches[1] ), 'UTF-8', 'UTF-16' );
				return $sym;
			},
			$data_to_compile
		);

		$file = fopen( PLUGIN_TASK_MANAGER_PATH . 'core/assets/json/data.json', 'w+' );
		fwrite( $file, $data_to_compile );
		fclose( $file );

		wp_send_json_success();
	}
}

new Tools_Action();
