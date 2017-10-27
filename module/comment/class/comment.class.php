<?php
/**
 * Gestion des commentaires
 *
 * @since 1.3.4
 * @version 1.5.0
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
	 * La version pour la rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet depuis la base de donnée.
	 *
	 * @var array
	 */
	protected $after_get_function = array();

	/**
	 * La fonction appelée automatiquement après l'insertion de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $before_post_function = array( '\task_manager\compile_time' );

	/**
	 * La fonction appelée automatiquement après la modification de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $before_put_function = array( '\task_manager\compile_time' );

	protected $after_post_function = array();
	protected $after_put_function = array();

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
			'status' => '-34070',
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->author = get_userdata( $comment->author_id );
			}
		}

		return $comments;
	}
}

Task_Comment_Class::g();
