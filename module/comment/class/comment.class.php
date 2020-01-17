<?php
/**
 * Gestion des commentaires
 *
 * @since 1.3.4
 * @version 1.6.1
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des commentaires
 */
class Task_Comment_Class extends \eoxia\Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = 'task_manager\Task_Comment_Model';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'wpeo_time';

	/**
	 * La route pour la rest API
	 *
	 * @var string
	 */
	protected $base = 'comment';

	/**
	 * Le type du commentaire
	 *
	 * @var string
	 */
	protected $type = 'wpeo_time';

	/**
	 * La version pour la rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * Statut personnalisé pour l'élément.
	 *
	 * @var string
	 */
	protected $status = '1';

	/**
	 * Récupères les commentaires d'un point.
	 *
	 * @since 1.4.0
	 * @version 1.7.0
	 *
	 * @param integer $point_id L'ID du point.
	 * @param array   $args Optionnel. Des arguments supplémentaire permettant de filtrer les commentaires retournés.
	 *
	 * @return array             La liste des commentaires du point.
	 */
	public function get_comments( $point_id, $args = array() ) {
		$default_args = array();

		if( $point_id ){
			$default_args[ 'parent' ] = $point_id;
		}

		$comments = self::g()->get( wp_parse_args( $args, $default_args ) );

		/*if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->data['author'] = get_userdata( $comment->data['author_id'] );
			}
		}*/

		return $comments;
	}

	/**
	 * Récupères les commentaires du point puis appel la vue "main" du module "comment".
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param  integer $task_id L'ID de la tâche.
	 * @param  integer $point_id L'ID du point.
	 * @param boolean $frontend Ou se trouve la view.
	 *
	 * @return void
	 * @todo: Faire passer le paramètre comment_id et le renommé en selected_comment_id.
	 */
	public function display( $task_id, $point_id, $frontend = false, $args = array() ) {
		$comment_id = ! empty( $_GET['comment_id'] ) ? (int) $_GET['comment_id'] : 0;

		// $number_comments = self::g()->get_comments( $point_id, array( 'count' => true ) ); // 28/06/2019
		$number_comments = count( self::g()->get( array( 'parent' => $point_id ) ) );

		$count_comments = 0;
		if( $number_comments > 0 ){
			$count_comments = intval( $number_comments / 10 );
			if( intval( $number_comments % 10 ) > 0 ){
				$count_comments++;
			}
		}

		$comments = self::g()->get_comments( $point_id, $args );

		$comment_schema = self::g()->get(
			array(
				'schema' => true,
			),
			true
		);

		$view = 'backend';
		if ( $frontend ) {
			$view = 'frontend';
		}

		$offset = 1;
		if( isset( $args[ 'offset' ] ) && $args[ 'offset' ] ){
			$offset = intval( $args[ 'offset' ] / 10 ) +1;
		}

		$point = Point_Class::g()->get( array( 'id' => $point_id ), true );

		Task_Class::g()->display_bodies( $comments, $point );
	}

	/**
	 * Est-ce que le point est le parent du commentaire ?
	 *
	 * @since 1.5.0
	 * @version 1.6.1
	 *
	 * @param integer $point_id    L'ID du point.
	 * @param integer $comment_id  L'ID du commentaire.
	 *
	 * @return boolean             True si le commentaire est un enfant. Sinon false.
	 */
	public function is_parent( $point_id, $comment_id ) {
		if ( 0 === $point_id || 0 === $comment_id ) {
			return false;
		}

		$comment = self::g()->get(
			array(
				'id' => $comment_id,
			),
			true
		);

		if ( $comment->data['parent_id'] === $point_id ) {
			return true;
		}

		return false;
	}

	public function edit_comment( $post_id, $point_id, $content, $date = '', $time = 0, $comment_id = 0 ) {
		if ( empty( $date ) ) {
			$date = current_time( 'mysql' );
		}

		$content = trim( $content );

		if ( ! empty( $comment_id ) ) {
			$comment = Task_Comment_Class::g()->get(
				array(
					'id' => $comment_id,
				),
				true
			);

			$comment->data['time_info']['old_elapsed'] = $comment->data['time_info']['elapsed'];
		} else {
			$comment = Task_Comment_Class::g()->get(
				array(
					'schema' => $comment_id,
				),
				true
			);
		}

		$comment->data['post_id']              = $post_id;
		$comment->data['parent_id']            = $point_id;
		$comment->data['date']                 = $date;
		$comment->data['content']              = $content;
		$comment->data['time_info']['elapsed'] = $time;
		$comment->data['status']               = '1';

		$comment = Task_Comment_Class::g()->update( $comment->data );

		return $comment;
	}
}

Task_Comment_Class::g();
