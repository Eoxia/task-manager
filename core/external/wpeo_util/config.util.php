<?php
/**
 * Gestion de l'objet Config_Util::$init.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 0.1.0
 * @version 0.5.0
 * @copyright 2015-2017 Eoxia
 * @package WPEO_Util
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Config_Util' ) ) {
	/**
	 * Initialise les fichiers .config.json
	 */
	class Config_Util extends \eoxia\Singleton_Util {
		/**
		 * Un tableau contenant toutes les configurations des fichies config.json
		 *
		 * @var array
		 */
		public static $init = array();

		/**
		 * Le constructeur obligatoirement pour utiliser la classe \eoxia\Singleton_Util
		 *
		 * @return void nothing
		 */
		protected function construct() {}

		/**
		 * Initialise les fichiers de configuration
		 *
		 * @param  string $path_to_config_file Le chemin vers le fichier config.json.
		 *
		 * @return mixed                       WP_Error si il ne trouve pas le fichier config du module
		 */
		public function init_config( $path_to_config_file, $plugin_slug = '' ) {
			if ( empty( $path_to_config_file ) ) {
				return new \WP_Error( 'broke', __( 'Impossible de charger le fichier', 'digirisk' ) );
			}

			$tmp_config = \eoxia\JSON_Util::g()->open_and_decode( $path_to_config_file );

			if ( empty( $tmp_config->slug ) ) {
				return new \WP_Error( 'broke', __( 'Le module nécessite un slug', 'digirisk' ) );
			}

			if ( ! empty( $plugin_slug ) ) {
				$slug = $tmp_config->slug;
				$tmp_config->module_path = $tmp_config->path;
				$tmp_config->url = str_replace( '\\', '/', str_replace( str_replace( '/', '\\', ABSPATH ), site_url('/'), self::$init[ $plugin_slug ]->path . $tmp_config->path ) );
				$tmp_config->path = self::$init[ $plugin_slug ]->path . $tmp_config->path;
				if ( isset( $tmp_config->external ) && ! empty( $tmp_config->external ) ) {
					self::$init['external']->$slug = $tmp_config;
				} else {
					self::$init[ $plugin_slug ]->$slug = $tmp_config;
				}
			} else {
				self::$init[ $tmp_config->slug ] = $tmp_config;
			}
		}
	}
} // End if().
