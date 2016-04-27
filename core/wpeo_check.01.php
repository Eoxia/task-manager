<?php 

if ( !defined( 'ABSPATH' ) ) exit;
if ( !class_exists( 'wpeo_check_01' ) ) {
	class wpeo_check_01 {
		public static function check( $action_nonce ) {
			/** Est-ce que _wpnonce existe ? */
			if ( !isset( $_GET['_wpnonce'] ) && !isset( $_POST['_wpnonce'] ) ) {
				wp_send_json_error();
			}
			
			/**
			 * Vérification du nonce avec la fonction wp_verify_nonce de WordPress. Cette fonction
			 * sanitize la valeur de $nonce
			 */
			$nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : $_POST['_wpnonce'];
			if ( !wp_verify_nonce( $nonce, $action_nonce ) ) {		
				wp_send_json_error();
			}
			
			/**
			 * Est ce que je suis bien sur une page admin ?
			 */
	// 		$adminurl = strtolower( admin_url() );
	// 		$referer = strtolower( wp_get_referer() );
	// 		echo __LINE__ . $adminurl . '<br />';
	// 		echo __LINE__ . $referer;
			
	// 		if( strpos( $referer, $adminurl ) !== 0 ) {		
	// 			wp_send_json_error();
	// 		}
						
			/**
			 * Vérification des capabilities de l'utilisateur courant avec la fonction current_user_can
			 * de WordPress. Est-ce qu'il à droit de crée une tâche ?
			 */
			if ( !current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}
		}
	}
}
?>