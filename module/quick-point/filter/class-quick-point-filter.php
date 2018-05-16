<?php
/**
 * Le fichier filter du module Quick-Point.
 * ici sont définie les filtres utilisé par le module.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
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
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 */
	public function __construct() {
		add_filter( 'eo_model_wpeo_point_after_post', array( $this, 'callback_after_save_point' ), 10, 2 );

		add_filter( 'tm_task_header', array( $this, 'callback_display_task_header' ), 10, 2 );
	}

	/**
	 * Appel de la vue check_box.
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 *
	 * @param string      $current_content Donnée de contenu par défaut. Par défaut elle est vide mais pour une meilleure compatibilité il faut la remplir.
	 * @param Point_Model $point           Définition complète du point.
	 *
	 * @return string                      Le contenu a renvoyer après avoir été traité par le filtre.
	 */
	public function callback_tm_point_before( $current_content, $point ) {

		if ( empty( $point->data['id'] ) ) {
			ob_start();
			\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-check-box' );
			$current_content .= ob_get_clean();
		}

		return $current_content;
	}

	/**
	 * Après la création du point, nous ajoutons des données supplémentaires telles que :
	 * Si le point est complété ou pas.
	 *
	 * Nous créons un commentaire attaché à ce point avec le temps passée.
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 *
	 * @param Point_Model $object  les données du point.
	 * @param array       $args_cb Contient toutes les données du formulaire en BRUT.
	 *
	 * @return Point_Model $object Les données du point modifiée.
	 */
	public function callback_after_save_point( $object, $args_cb ) {
		$tm_point_is_quick_point = ( isset( $_POST['tm_point_is_quick_point'] ) && 'true' === $_POST['tm_point_is_quick_point'] ) ? true : false; // WPCS: CSRF ok.
		if ( $tm_point_is_quick_point ) {
			$completed = ( isset( $_POST['completed'] ) && 'true' === $_POST['completed'] ) ? true : false; // WPCS: CSRF ok.
			$time_info = ! empty( $_POST['time_info'] ) ? (int) $_POST['time_info'] : 0; // WPCS: CSRF ok.

			$object->data['completed'] = $completed;
			Point_Class::g()->update( $object->data );

			Task_Comment_Class::g()->create(array(
				'comment_content' => $object->data['content'],
				'parent_id'       => $object->data['id'],
				'post_id'         => $object->data['post_id'],
				'time_info'       => array(
					'elapsed' => $time_info,
				),
			) );

			$object = Point_Class::g()->get( array( 'id' => $object->data['id'] ), true );
		}

		return $object;
	}

	/**
	 * Ajoutes l'input type text gérant le temps dépassé du point.
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 *
	 * @param string      $current_content Donnée de contenu par défaut. Par défaut elle est vide mais pour une meilleure compatibilité il faut la remplir.
	 * @param Point_Model $point           Définition complète du point.
	 *
	 * @return string                      Le contenu a renvoyer après avoir été traité par le filtre.
	 */
	public function callback_point_after( $current_content, $point ) {

		if ( empty( $point->data['id'] ) ) {
			ob_start();
			\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-input-text' );
			$current_content .= ob_get_clean();
		}

		return $current_content;
	}

	/**
	 * Ajoute le bouton permettant d'ouvrir la modal pour l'ajout d'un point/temps rapide sur une tâche.
	 *
	 * @param  string     $current_content Le contenu actuel qu'il faut modifier pour l'affichage du bouton d'ajout de point/temps rapide.
	 * @param  Task_Model $task            La tâche sur laquelle il faut ajouter le bouton.
	 *
	 * @return string                      Le contenu modifié par le filtre actuel en vue de l'affichage.
	 */
	public function callback_display_task_header( $current_content, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-button-opener', array(
			'task' => $task,
		) );
		$current_content .= ob_get_clean();

		return $current_content;
	}

}
new Quick_Point_Filter();
