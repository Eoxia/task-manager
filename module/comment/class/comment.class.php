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
	 * @since 1.4.0-ford
	 * @version 1.4.0-ford
	 *
	 * @param  integer $point_id L'ID du point.
	 * @return array             La liste des commentaires du point.
	 */
	public function get_comments( $point_id ) {
		$comments = self::g()->get( array(
			'parent' => $point_id,
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->data['author'] = get_userdata( $comment->data['author_id'] );
			}
		}

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
	 *
	 * @return void
	 * @todo: Faire passer le paramètre comment_id et le renommé en selected_comment_id.
	 */
	public function display( $task_id, $point_id ) {
		$comment_id = ! empty( $_GET['comment_id'] ) ? (int) $_GET['comment_id'] : 0;

		$comments = self::g()->get_comments( $point_id );

		$comment_schema = self::g()->get( array(
			'schema' => true,
		), true );

		\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/main', array(
			'task_id'             => $task_id,
			'point_id'            => $point_id,
			'comments'            => $comments,
			'comment_selected_id' => $comment_id,
			'comment_schema'      => $comment_schema,
		) );
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

		$comment = self::g()->get( array(
			'id' => $comment_id,
		), true );

		if ( $comment->data['parent_id'] === $point_id ) {
			return true;
		}

		return false;
	}
}

Task_Comment_Class::g();
