<?php
/**
 * Gestion API.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit;

/**
 * API Action Class.
 */
class API_Action {

	/**
	 * Constructeur
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_check_cap', array( $this, 'check_cap' ), 1, 2 );

		add_action( 'rest_api_init', array( $this, 'callback_rest_api_init' ) );

		add_action( 'show_user_profile', array( $this, 'callback_edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_edit_user_profile' ) );

		add_action( 'wp_ajax_generate_api_key', array( $this, 'generate_api_key' ) );
	}

	/**
	 * Vérifie que l'utilisateur à les droits.
	 *
	 * @since 2.0.0
	 *
	 * @param  boolean         $cap     True ou false.
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return boolean                  True ou false.
	 */
	public function check_cap( $cap, $request ) {
		$headers = $request->get_headers();

		if ( empty( $headers['wpapikey'] ) ) {
			return false;
		}

		$wp_api_key = $headers['wpapikey'];

		$user = API::g()->get_user_by_token( $wp_api_key[0] );

		if ( empty( $user ) ) {
			return false;
		}

		wp_set_current_user( $user->ID );

		return true;
	}

	/**
	 * Ajoutes la route pour PayPal.
	 *
	 * @since 2.0.0
	 */
	public function callback_rest_api_init() {
		register_rest_route( 'task_manager/v1', '/get-info', array(
			'methods'             => array( 'GET' ),
			'callback'            => array( $this, 'get_info' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		add_filter( 'rest_pre_serve_request', function( $value ) {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
		header( 'Access-Control-Allow-Credentials: true' );
		header( 'Access-Control-Allow-Headers: wpapikey' );

		return $value;

	});
	}

	/**
	 * Ajoute les champs spécifiques à note de frais dans le compte utilisateur.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_User $user L'objet contenant la définition
	 * complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user ) {
		$token = get_user_meta( $user->ID, '_tm_api_key', true );

		\eoxia\View_Util::exec( 'task-manager', 'api', 'field-api', array(
			'id'    => $user->ID,
			'token' => $token,
		) );
	}

	/**
	 * Génère une clé API pour un utilisateur
	 *
	 * @since 2.0.0
	 */
	public function generate_api_key() {
		check_ajax_referer( 'generate_api_key' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$token = API::g()->generate_token();
		update_user_meta( $id, '_tm_api_key', $token );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'api', 'field-api', array(
			'id'    => $id,
			'token' => $token,
		) );

		wp_send_json_success( array(
			'namespace'        => 'wpshop',
			'module'           => 'API',
			'callback_success' => 'generatedAPIKey',
			'view'             => ob_get_clean(),
		) );
	}

	/**
	 * Permet de vérifier que l'application externe soit bien connecté.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return WP_REST_Response          La réponse au format JSON.
	 */
	public function get_info( $request ) {
      $name     = get_bloginfo();
      $url_icon = get_site_icon_url( 128 );

			$headers = $request->get_headers();

			if ( empty( $headers['wpapikey'] ) ) {
				return false;
			}

			$wp_api_key = $headers['wpapikey'];

			$user = API::g()->get_user_by_token( $wp_api_key[0] );

			if ( empty( $user ) ) {
				return false;
			}

	   return new \WP_REST_Response( array(
       'name'     => $name,
       'url_icon' => $url_icon,
			 'user_id'  => $user->ID
     ) );
	}
}

new API_Action();
