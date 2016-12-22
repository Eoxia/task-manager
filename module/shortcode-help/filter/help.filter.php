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
 * Classe permettant de gère les filtres WordPress d'affichage des boutons d'insertion de shortcode dans les pages/articles/etc
 */
class Task_Help_Filter extends Singleton_util {

	/**
	 * Overwrite parent constructor
	 */
	public function construct() { }

	/**
	 * Fonction de callback pour l'initialisation des différents outils dans le wysiwyg
	 */
	public function callback_wysiwyg_init() {
		add_filter( 'mce_buttons', array( $this, 'callback_mce_buttons' ) );
		add_filter( 'mce_external_plugins', array( $this, 'callback_mce_external_plugins' ) );
	}

	/**
	 * Fonction de callback permettant d'ajouter les boutons dans le WYSIWYG
	 *
	 * @param  array $buttons La liste des boutons actuellement présent dans le WYSIWYG.
	 *
	 * @return array La nouvelle liste de boutons a afficher dans le WYSIWYG
	 */
	public function callback_mce_buttons( $buttons ) {
		array_push( $buttons, 'task' );

		return $buttons;
	}

	/**
	 * Fonction de callback pour
	 *
	 * @param  array $plugin_array La liste des plugins actuellement présent dans le WYSIWYG.
	 *
	 * @return array               La nouvelle liste de plugins dans le WYSIWYG
	 */
	public function callback_mce_external_plugins( $plugin_array ) {
		$plugin_array['task'] = PLUGIN_TASK_MANAGER_URL . '/core/asset/js/task-button.js';

		return $plugin_array;
	}

}

new Task_Help_Filter();
