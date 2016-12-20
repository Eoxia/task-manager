<?php
/**
 * Fichier permettant de gérer l'affichage de l'aide pour les shortcodes d'affichage des tâches
 *
 * @package Task Manager
 * @subpackage Module/Shortcode-Help
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion de l'affichage de l'aide pour les shortcodes d'affichage des tâches
 */
class Task_Help_Action {

	/**
	 * Instanciation de l'afficahge de l'aide d'utilisation des shortcodes
	 */
	public function __construct() {
		global $wp_task_manager_menu;
		add_action( 'wp_ajax_ed_get_list_task', array( $this, 'callback_ed_get_list_task' ) );

		/** Ajout d'un onglet d'aide sur le tableau de bord pour l'utilisation d'un shortcode pour l'affichage  */
		add_action( 'load-toplevel_page_wpeomtm-dashboard', array( $this, 'my_admin_add_help_tab' ) );
	}

	public function my_admin_add_help_tab() {
		$screen = get_current_screen();

	 // Add my_help_tab if current screen is My Admin Page
	 $screen->add_help_tab( array(
			 'id'	=> 'my_help_tab',
			 'title'	=> __('My Help Tab'),
			 'content'	=> '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
	 ) );
	}

	/**
	 * Fonction de callback pour l'affichage de la liste des tâches pour insertion d'un shortcode
	 */
	public function callback_ed_get_list_task() {
		global $task_controller;
		$list_task = $task_controller->index( array( 'post_parent' => 0 ) );

		$list_task_json = array(
	  	'type' => 'listbox',
	  	'name' => 'task_id',
	  	'label' => __( 'Task', 'task-manager' ),
	  	'values' => array(),
		);

		if ( ! empty( $list_task ) ) {
			foreach ( $list_task as $element ) {
				$list_task_json['values'][] = array(
		  		'text' => '#' . $element->id . ' - ' . $element->title,
			  	'value' => $element->id,
				);
			}
		}

		wp_send_json_success( array( 'list_task' => $list_task_json ) );
	}

}

new Task_Help_Action();
