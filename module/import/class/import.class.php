<?php
/**
 * Gestions des filtres utilisés pour l'import des tâches et points.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import\Class
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * .
 */
class Import_Class extends \eoxia\Singleton_Util {

	/**
	 * Function necessaire pour Singleton_Util.
	 */
	protected function construct() {}

	/**
	 * Affiche le champs textarea où inscrire les données à importer.
	 *
	 * @return void
	 */
	public function display_textarea() {
		$tags    = Tag_Class::g()->get();
		\eoxia\View_Util::exec(
			'task-manager',
			'import',
			'backend/import-textarea',
			array(
				'default_content' => '',
				'tags'            => $tags
			)
		);
	}

	/**
	 * Traite les données envoyées pour l'import.
	 *
	 * @param integer $post_id Identifiant de l'élément de type post sur lequel on se trouve.
	 * @param string  $content Contenu envoyé pour import.
	 * @param integer $task_id Optionnel. Identifiant de la tâche sur laquelle importer.
	 *
	 * @return array           La liste des éléments créés et non créés.
	 */
	public function treat_content( $post_id, $content = '', $task_id = 0 ) {
		$element_list = array(
			'not_created' => array(
				'tasks'   => array(),
				'points'  => array(),
				'unknown' => array(),
			),
			'created'     => array(
				'tasks'   => array(),
				'points'  => array(),
				'unknown' => array(),
			),
		);

		$content_by_lines = preg_split( '/\r\n|\r|\n/', $content );
		$list_category_not_found = array();
		if ( ! empty( $content_by_lines ) ) {
			$list_id = array();
			foreach ( $content_by_lines as $index => $line ) {

				// On vérifier le type de la ligne que l'on est sur le point de traiter.
				$line_type_is_task = false;
				if ( false !== strpos( $line, '%task%' ) ) {
					$line_type_is_task = true;
					$line              = str_replace( '%task%', '', $line );
				}

				$line_type_is_point = false;
				if ( false !== strpos( $line, '%point%' ) ) {
					$line_type_is_point = true;
					$line               = str_replace( '%point%', '', $line );
				}

				$line_type_is_comment = false;/*
				if ( false !== strpos( $line, '%comment%' ) ) {
					$line_type_is_comment = true;
					$line               = str_replace( '%comment%', '', $line );
				}*/

				$line_type_is_category = false;
				if ( false !== strpos( $line, '%category%' ) ) { // Associer une catégorie (TAG) à une classe
					$line_type_is_category = true;
					$line              = str_replace( '%category%', '', $line );
				}
				// - - - -

				if ( ! empty( $line ) && $line_type_is_task ) {

					$created_task = Task_Class::g()->create(
						array(
							'title'     => $line,
							'parent_id' => $post_id,
						)
					);

					array_push( $list_id, $created_task->data[ 'id' ] );

					// On vérifie que la création ce soit bien passée.
					if ( ! empty( $created_task ) && ! empty( $created_task->data['id'] ) ) {
						$task_id                            = $created_task->data['id'];
						$element_list['created']['tasks'][] = $created_task;
					}
				} elseif ( ! empty( $line ) && $line_type_is_point ) {
					if ( ! empty( $task_id ) ) {
						$point_args    = array(
							'post_id' => $task_id,
							'content' => $line,
							'order'   => $index,
						);
						$created_point = Point_Class::g()->create( $point_args );

						// On vérifie que la création ce soit bien passée.
						if ( ! empty( $created_point ) && ! empty( $created_point->data['id'] ) ) {
							$point_id                            = $created_point->data['id'];
							$element_list['created']['points'][] = $created_point;
						}
					} else {
						$element_list['not_created']['points'][] = $line;
					}
				}elseif ( ! empty( $line ) && $line_type_is_category ) {
					if ( ! empty( $task_id ) ) {
						$category_args    = array(
							'tag_id' => $line,
							'content' => $line,
							'order'   => $index
						);

						$category = get_term_by( 'slug', trim( $line ), 'wpeo_tag' );

						if( ! empty( $category ) ){
							$created_task->data['taxonomy'][ Tag_Class::g()->get_type() ][] = $category->term_id;
							$task = Task_Class::g()->update( $created_task->data, true );
						}else{
							$list_category_not_found[] = array(
								'line' => trim( $line ),
								'id' => $created_task->data[ 'id' ]
							);
							// Error no category found
						}

					} else {
						$element_list['not_created']['tag'][] = $line;
					}
				} /*elseif ( ! empty( $line ) && $line_type_is_comment ) {
					if ( ! empty( $task_id ) && ! empty ( $point_id ) ) {
						$comment_args    = array(
							'post_id' => $task_id,
							'parent_id' => $point_id,
							'content' => $line,
							'time_info' => array(
								'elapsed' => 0
							)
						);
						$created_comment = Task_Comment_Class::g()->create( $comment_args );

						// On vérifie que la création ce soit bien passée.
						if ( ! empty( $created_task ) && ! empty( $created_point ) && ! empty( $created_comment->data['id'] ) ) {
							$element_list['created']['comments'][] = $created_point;
						}
					} else {
						$element_list['not_created']['comments'][] = $line;
					}
				}*/else {
					$element_list['not_created']['unknown'][] = $line;
				}
			}

			foreach( $list_id as $key => $value ){
				Task_Class::g()->recompile_task( $value );
			}
		}

		$return = [$element_list, $list_category_not_found];
		return $return;
	}

}
