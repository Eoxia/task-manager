<?php
/**
 * Gestion des filtres relatives aux commentaires
 *
 * @author Eoxia <dev@eoxia.com>
 * @copyright 2018 Eoxia.
 * @since 1.6.0
 * @version 1.8.0
 * @package Task-Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Gestion des filtres relatives aux commentaires
 */
class Comment_Filter {

	/**
	 * Le constructeur
	 *
	 * @since   1.6.0
	 * @version 1.8.0
	 */
	public function __construct() {
		$current_type = Task_Comment_Class::g()->get_type();
		add_filter( "eo_model_{$current_type}_after_get", array( $this, 'parse_content' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_put", array( $this, 'compile_time' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_post", array( $this, 'compile_time' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_post", array( $this, 'callback_after_save_comments' ), 10, 2 );

		add_filter( 'tm_comment_edit_after', array( $this, 'callback_tm_comment_edit_after' ), 10, 2 );
		add_filter( 'tm_comment_advanced_view', array( $this, 'callback_tm_comment_advanced_view' ), 10, 2 );

		if ( ! empty( Task_Class::g()->contents['headers'] ) ) {
			foreach ( Task_Class::g()->contents['headers'] as $key => $header ) {
				if ( method_exists( $this, 'tm_projects_wpeo_time_def' ) ) {
					add_filter( 'tm_projects_wpeo_time_def', array( $this, 'tm_projects_wpeo_time_def' ), 10, 2 );
				}

				if ( method_exists( $this, 'fill_value_' . $key . '_value' ) ) {
					add_filter( 'tm_projects_content_wpeo_time_' . $key . '_def', array(
						$this,
						'fill_value_' . $key . '_value'
					), 10, 2 );
				}
			}
		}
	}

	/**
	 * Compiles le temps.
	 *
	 * @param   Comment_Object  $object  L'objet Comment_Object.
	 * @param   array           $args    Des paramètres complémentaires pour permettre d'agir sur l'élement.
	 *
	 * @return Comment_Object        Les données de la tâche avec les données complémentaires.
	 * @version 1.6.0
	 *
	 * @since   1.6.0
	 */
	public function compile_time( $object, $args ) {
		$point = Point_Class::g()->get(
			array(
				'id'     => $object->data['parent_id'],
				'status' => array( '1', 'trash' ),
			),
			true
		);

		$task = Task_Class::g()->get(
			array(
				'id'          => $object->data['post_id'],
				'post_status' => array(
					'any',
					'archive',
					'trash',
				),
			),
			true
		);

		$point_updated_elapsed = $point->data['time_info']['elapsed'];
		$task_updated_elapsed  = $task->data['time_info']['elapsed'];

		if ( ! is_object( $point ) || ! is_object( $task ) ) {
			\eoxia\LOG_Util::log( sprintf( 'Point for update data compilation %s', wp_json_encode( $point ) ), 'task-manager-compile-update' );
			\eoxia\LOG_Util::log( sprintf( 'Task for update data compilation %s', wp_json_encode( $task ) ), 'task-manager-compile-update' );
			\eoxia\LOG_Util::log( sprintf( 'Object for update data compilation %s', wp_json_encode( $object ) ), 'task-manager-compile-update' );
		}

		if ( 'trash' === $object->data['status'] ) {
			$point_updated_elapsed -= $object->data['time_info']['elapsed'];
			$task_updated_elapsed  -= $object->data['time_info']['elapsed'];
		} else {
			if ( isset( $object->data['time_info']['old_elapsed'] ) ) {
				$point_updated_elapsed -= $object->data['time_info']['old_elapsed'];
				$task_updated_elapsed  -= $object->data['time_info']['old_elapsed'];
			}
			$point_updated_elapsed += $object->data['time_info']['elapsed'];
			$task_updated_elapsed  += $object->data['time_info']['elapsed'];
		}

		$point->data['time_info']['elapsed'] = $point_updated_elapsed;
		$task->data['time_info']['elapsed']  = $task_updated_elapsed;
		$point->data['content']              = addslashes( $point->data['content'] );
		$object->data['point']               = Point_Class::g()->update( $point->data );
		$object->data['task']                = Task_Class::g()->update( $task->data );

		return $object;
	}

	/**
	 * Parsage du contenu du commentaire afin de retrouver les ID correspondant à des tâches, points ou d'autres commentaires.
	 * Permet de rajouter une balise avec une infobulle contenant les 100 premiers caractères de l'élément trouvé dans le contenu.
	 *
	 * @param   Task_Comment_Model  $object  Les données du commentaire.
	 * @param   array               $args    Les données lors de la création de l'objet.
	 *
	 * @return Task_Comment_Model         Les données du commentaire modifiée par cette méthode.
	 * @version 1.8.0
	 *
	 * @since   1.8.0
	 */
	public function parse_content( $object, $args ) {
		$object->data['rendered'] = $object->data['content'];

		$prefixes_to_parse = array(
			'T' => '\task_manager\Task_Class',
			'P' => '\task_manager\Point_Class',
			'C' => '\task_manager\Task_Comment_Class',
		);

		if ( ! empty( $prefixes_to_parse ) ) {
			foreach ( $prefixes_to_parse as $prefix_to_parse => $model_to_use ) {
				preg_match_all( '/#' . $prefix_to_parse . '(\d*)/', $object->data['content'], $matches );

				if ( ! empty( $matches[1] ) ) {
					foreach ( $matches[1] as $matched ) {
						$parsed_object = $model_to_use::g()->get( array( 'id' => $matched ), true );

						if ( ! empty( $parsed_object->data['id'] ) ) {
							$prefix_id = $prefix_to_parse . $parsed_object->data['id'];
							$content   = $parsed_object->data['content'];
							if ( empty( $content ) ) {
								$content = $parsed_object->data['title'];
							}

							$content = htmlspecialchars( substr( $content, 0, 100 ) );

							// Le contenu en entier.
							$html = "<b class='wpeo-tooltip-event' aria-label='" . $content . "'>#" . $prefix_id . '</b>';

							$object->data['rendered'] = preg_replace( '/#' . $prefix_id . '/', $html, $object->data['rendered'] );
						}
					}
				}
			}
		}

		return $object;
	}

	/**
	 * Callback appelé après l'insertion d'un nouveau commentaire.
	 *
	 * @param   Task_Comment_Model  $object  La définition complète du commentaire avant passage dans le filtre.
	 * @param   array               $args    Des données complémentaires permettant d'effectuer le traitement du filtre.
	 *
	 * @return Task_Comment_Model         La définition du commentaire après passage du filtre.
	 */
	public function callback_after_save_comments( $object, $args ) {
		$point = Point_Class::g()->get(
			array(
				'id' => $object->data['parent_id'],
			),
			true
		);

		$point->data['count_comments'] ++;

		Point_Class::g()->update( $point->data );

		return $object;
	}

	/**
	 * Renvois la vue du commentaire
	 *
	 * @param  [type] $output  [vue].
	 * @param  [type] $comment [commentaire].
	 *
	 * @return [type] $output [contient la vue à afficher].
	 */
	public function callback_tm_comment_edit_after( $output, $comment ) {
		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		if ( $user->data['_tm_advanced_display'] ) {
			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'comment',
				'backend/edit-advanced',
				array(
					'comment' => $comment,
				)
			);
			$output .= ob_get_clean();
		}

		return $output;
	}

	/**
	 * Affiche la vue
	 *
	 * @param  [type] $output  [vue].
	 * @param  [type] $comment [commentaire].
	 *
	 * @return [type] $output [contient la vue à afficher].
	 */
	public function callback_tm_comment_advanced_view( $output, $comment ) {
		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		if ( $user->data['_tm_advanced_display'] ) {
			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'comment',
				'backend/comment-advanced',
				array(
					'comment' => $comment,
				)
			);
			$output .= ob_get_clean();
		}

		return $output;
	}

	public function tm_projects_wpeo_time_def( $output, $comment ) {
		$output['classes'] = 'table-type-comment';

		$output['attrs'][] = 'data-id="' . $comment->data['id'] . '"';
		$output['attrs'][] = 'data-post-id="' . $comment->data['post_id'] . '"';
		$output['attrs'][] = 'data-parent-id="' . $comment->data['parent_id'] . '"';
		$output['attrs'][] = 'data-nonce="' . wp_create_nonce( 'edit_comment' ) . '"';

		$output['element_id'] = $comment->data['id'];
		$output['project_id'] = $comment->data['post_id'];
		$output['parent_id']  = $comment->data['parent_id'];

		$output['type'] = 'comment';


		return $output;
	}

	public function fill_value_empty_value( $output, $point ) {
		$output['classes'] .= ' cell-toggle task-toggle-comment cell-disabled';

		return $output;
	}

	public function fill_value_state_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_archive_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_name_value( $output, $comment ) {
		$output['classes'] .= ' cell-content';
		$output['value'] = $comment->data['content'];

		return $output;
	}

	public function fill_value_id_value( $output, $comment ) {
		$output['classes'] .= ' cell-readonly';
		$output['value'] = $comment->data['id'];

		return $output;
	}

	public function fill_value_last_update_value( $output, $comment ) {
		$output['classes'] .= ' cell-readonly';

		return $output;
	}

	public function fill_value_time_value( $output, $comment ) {

		$output['value'] = $comment->data['time_info']['elapsed'];

		return $output;
	}

	public function fill_value_created_date_value( $output, $comment ) {
		$output['raw']       = $comment->data['date']['raw'];
		$output['date_time'] = $comment->data['date']['rendered']['date_time'];

		return $output;
	}

	public function fill_value_ended_date_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_indicators_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_affiliated_with_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_categories_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_attachments_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_number_comments_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_author_value( $output, $comment ) {
		$output['classes'] .= ' cell-readonly';
		$output['value']    = $comment->data['author_id'];

		return $output;
	}

	public function fill_value_associated_users_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_participants_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_waiting_for_value( $output, $comment ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_empty_add_value( $output, $comment ) {
		$output['classes'] .= ' cell-sticky';
		$output['comment']  = $comment;

		return $output;
	}
}

new Comment_Filter();
