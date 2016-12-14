<?php
/**
 * Gestion des fichiers JSON
 *
 * @package Evarisk\Plugin
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des fichiers JSON
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @version 1.1.0.0
 */
class Json_util extends Singleton_util {
	/**
	 * Le constructeur obligatoirement pour utiliser la classe singleton_util
	 *
	 * @return void nothing
	 */
	protected function construct() {}

	/**
	 * Ouvres et décode le fichier JSON $path_to_json
	 *
	 * @param  string $path_to_json Le chemin vers le fichier JSON.
	 * @return array              	Les données du fichier JSON
	 */
	public function open_and_decode( $path_to_json ) {
		if ( ! file_exists( $path_to_json ) ) {
			if ( function_exists( 'eo_log' ) ) {
				eo_log( 'digi_open_and_decode', array(
					'message' => 'Impossible d\'ouvrir le fichier json: ' . $path_to_json,
				), 2 );
			} else {
				return new \WP_Error( 'broke', __( 'Impossible d\'ouvrir le fichier JSON', 'digirisk' ) );
			}
		}

		$config_content = file_get_contents( $path_to_json );

		$data = json_decode( $config_content );

		if ( null === $data && json_last_error() !== JSON_ERROR_NONE ) {
			if ( function_exists( 'eo_log' ) ) {
				eo_log( 'digi_open_and_decode', array(
					'message' => 'Les données dans le fichier json: ' . $path_to_json . ' semble erronées',
				), 2 );
			} else {
				return new \WP_Error( 'broke', __( 'Les données du fichier JSON semble erronnés', 'digirisk' ) );
			}
		}

		if ( function_exists( 'eo_log' ) ) {
			eo_log( 'digi_open_and_decode', array(
				'message' => 'Le fichier json: ' . $path_to_json . ' sont : ' . wp_json_encode( $data ),
			), 2 );
		}

		return $data;
	}

	/**
	 * Décodes la chaine de caractère $json_to_decode
	 *
	 * @param  string $json_to_decode La chaine de caractère JSON.
	 * @return array              		Les données décodées
	 */
	public function decode( $json_to_decode ) {
		if ( ! is_string( $json_to_decode ) ) {
			return $json_to_decode;
		}

		$json_decoded = json_decode( $json_to_decode, true );

		if ( ! $json_decoded ) {
			$json_to_decode = str_replace( '\\', '', $json_to_decode );
			$json_decoded = json_decode( $json_to_decode, true );

			if ( ! $json_decoded ) {
				return $json_to_decode;
			}
		}

		return $json_decoded;
	}
}
