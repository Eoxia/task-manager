<?php
/**
 * Gestion des vues (template)
 *
 * @package Evarisk\Plugin
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des vues
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @version 1.1.0.0
 */
class View_util extends Singleton_util {

	/**
	 * Défini le chemin principal du plugin pour récupérer les vues / Define main path for view directory
	 *
	 * @var $path
	 * @return string The main plugin path.
	 */
	public static $path = '';

	/**
	 * Le constructeur obligatoirement pour utiliser la classe singleton_util
	 *
	 * @return void nothing
	 */
	protected function construct() {}

	/**
	 * Define the
	 *
	 * @param string $main_path Le chemin principal a utiliser pour accèder aux vue / The main path to use for finding views.
	 */
	public function set_path( $main_path ) {
		self::$path = $main_path;
	}

	/**
	 * Appelle la vue avec les paramètres et calcule automatiquement les MicroSeconds
	 *
	 * @param  string $module_name           Le nom du module.
	 * @param  string $view_path_without_ext Le chemin vers le fichier à partir du dossier "view" du module.
	 * @param  array  $args                  Les données à transmettre à la vue.
	 * @return void                        	 nothing
	 */
	public static function exec( $module_name, $view_path_without_ext, $args = array() ) {
		$path_to_view = self::$path . $module_name . '/view/' . $view_path_without_ext . '.view.php';

		log_class::g()->start_ms( __NAMESPACE__ . '_view_util_exec' );

		if ( ! file_exists( $path_to_view ) ) {
			log_class::g()->exec( __NAMESPACE__ . '_view', 'view_util_exec', 'Impossible de charger la vue : ' . $path_to_view, $args, 2 );
		}

		$args = apply_filters( $module_name . '_' . $view_path_without_ext, $args, $module_name, $view_path_without_ext );
		extract( $args );
		require( $path_to_view );
		log_class::g()->exec( __NAMESPACE__ . '_view_util_exec', 'view_util_exec', 'Chargement de la vue : ' . $path_to_view, $args );
	}
}
