<?php
/**
 * Le fichier filter du module Quick-Point.
 * ici sont définie les filtres utilisé par le module.
 *
 * @author ||||||||
 * @since 1.6.1
 * @version 1.6.1
 * @copyright 2018+
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des filtres relatives a la Modal .
 */
class Quick_Point_Filter {
	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_filter( 'eo_model_wpeo_point_after_post', array( $this, 'callback_after_save_point' ), 10, 2 );
	}
	/**
	 * Appel de la vue check_box.
	 *
	 * @param string $default -pas utile.
	 */
	public function callback_tm_point_before( $default ) {

		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-check-box' );

	}

	/**
	 * After_save_point .
	 *
	 * @param string $object les données de l'objet.
	 * @param string $args_cb  ????.
	 */
	public function callback_after_save_point( $object, $args_cb ) {
		check_ajax_referer( 'edit_point' );
		$bool_status = ( isset( $_POST['bool_status'] ) && 'true' === $_POST['bool_status'] ) ? true : false;
		$time_info   = ! empty( $_POST['time_info'] ) ? (int) $_POST['time_info'] : 0;
		if ( isset( $bool_status ) && 'true' === $bool_status ) {
			if ( isset( $bool_status ) ) {
				$object->data['completed'] = $bool_status;
				Point_Class::g()->update( $object->data );
			}
			Task_Comment_Class::g()->create(array(
				'comment_content' => $object->data['content'],
				'parent_id'       => $object->data['id'],
				'post_id'         => $object->data['post_id'],
				// Ajouter le temps ICI time_info -> elapsed.
				'time_info'       => array(
					'elapsed' => $time_info,
				),
			));
		}
		return $object;
	}
	/**
	 * Fonction qui verifie si quick existe si oui envoie la vue de l input temps .
	 * pour eviter d avoir l input dans la vu principale.
	 */
	public function input_text() {

			\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-input-text' );
	}
}
new Quick_Point_Filter();
