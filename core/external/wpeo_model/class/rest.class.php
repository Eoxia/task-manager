<?php
/**
 * Gestion des routes
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.4.0
 * @version 1.5.0
 * @copyright 2015-2017
 * @package wpeo_model
 * @subpackage class
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( '\eoxia\Rest_Class' ) ) {
	/**
	 * Gestion des utilisateurs (POST, PUT, GET, DELETE)
	 */
	class Rest_Class extends Singleton_Util {

		/**
		 * [construct description]
		 */
		protected function construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ), 20);
		}

		/**
		 * Défini et ajoute les routes dans l'api rest de WordPress
		 */
		public function register_routes() {
			$element_namespace = new \ReflectionClass( get_called_class() );

			register_rest_route( $element_namespace->getNamespaceName() . '/v' . Config_Util::$init['external']->wpeo_model->api_version , '/' . $this->base . '/schema', array(
				array(
					'method' 		=> \WP_REST_Server::READABLE,
					'callback'	=> array( $this, 'get_schema' ),
				),
			) );

			register_rest_route( $element_namespace->getNamespaceName() . '/v' . Config_Util::$init['external']->wpeo_model->api_version , '/' . $this->base, array(
				array(
					'methods' 		=> \WP_REST_Server::READABLE,
					'callback'	=> function( $request ) {
						return $this->get();
					},
					'permission_callback' => function() {
						// if ( ! current_user_can( $this->capabilities[ 'get' ] ) ) {
						// 	return false;
						// }
						return true;
					},
				),
				array(
					'methods' 		=> \WP_REST_Server::CREATABLE,
					'callback'	=> function( $request ) {
						return $this->get();
					},
					'permission_callback' => function() {
						// if ( ! current_user_can( $this->capabilities[ 'post' ] ) ) {
						// 	return false;
						// }
						return true;
					},
				),
			), true );

			register_rest_route( $element_namespace->getNamespaceName() . '/v' . Config_Util::$init['external']->wpeo_model->api_version , '/' . $this->base . '/(?P<id>[\d]+)', array(
				array(
					'method' => \WP_REST_Server::READABLE,
					'callback'	=> function( $request ) {
						return $this->get( array( 'id' => $request['id'] ), true );
					},
					'permission_callback' => function() {
						// if ( ! current_user_can( $this->capabilities[ 'get' ] ) ) {
						// return false;
						// }
						return true;
					},
				),
			), true );

		}

	}

}
