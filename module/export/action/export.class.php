<?php
/**
 * Les actions relatives a l'export des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.1
 * @version 1.5.1
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives a l'export des tâches.
 */
class Export_Class extends \eoxia\Singleton_Util {

	/**
	 * Initialise les actions liées a l'export des tâches.
	 *
	 * @since 1.5.1
	 * @version 1.5.1
	 */
	protected function construct() {}

	/**
	 * Write a file with exported datas
	 *
	 * @param  Task_Model $task    La tâches dont il faut exporter les données.
	 * @param  string     $content Les données à inscrire dans le fichier.
	 *
	 * @return array                   Les informations concernant le fichier généré.
	 */
	public function export_to_file( $task, $content ) {
		$upload = wp_upload_dir();

		$file_name = $task->slug . current_time( 'timestamp' ) . '.txt';
		$file_info = array(
			'name' => $task->slug . current_time( 'timestamp' ) . '.txt',
			'path' => $upload['path'] . '/' . $file_name,
			'url'  => $upload['url'] . '/' . $file_name,
		);

		$fp = fopen( $file_info['path'], 'w' );
		fputs( $fp, $content );
		fclose( $fp );

		return $file_info;
	}

	/**
	 * Met en forme l'export des données pour une tâche.
	 *
	 * @param  Task_Model   $task   La tâche pour laquelle il faut générer l'export.
	 * @param  Point_Models $datas  Les points correspondant à la demande d'export.
	 * @param  array        $args   Une liste d'arguments pour la récupération des des données à afficher.
	 *
	 * @return string               Les données formatées pour l'export.
	 */
	public function build_data( $task, $datas, $args ) {
		$points_completed   = array();
		$points_uncompleted = array();

		$points_completed = array_filter( $datas, function( $point ) {
			return true === $point->completed;
		} );

		$points_uncompleted = array_filter( $datas, function( $point ) {
			return false === $point->completed;
		} );

		$export_data  = $task->id . ' - ' . $task->title . "\r\n\r\n";
		$export_data .= __( 'Uncompleted', 'task-manager' ) . "\r\n";

		if ( ! empty( $points_uncompleted ) ) {
			foreach ( $points_uncompleted as $point ) {
				$export_data .= ( $args['with_id'] ? $point->id . ' - ' : ' > ' ) . trim( str_replace( '<br>', "\r\n", $point->content ) ) . "\r\n";
				if ( $args['with_comments'] ) {
					$point_comments = Task_Comment_Class::g()->get_comments( $point->id, $args );
					if ( ! empty( $point_comments ) ) {
						foreach ( $point_comments as $comment ) {
							$export_data .= '	' . ( $args['with_id'] ? $comment->id . ' - ' : ' > ' ) . trim( str_replace( '<br>', "\r\n	" . str_repeat( ' ', ( strlen( ( $args['with_id'] ? $comment->id . ' - ' : ' > ' ) ) + 3 ) ), $comment->content ) ) . "\r\n\r\n";
						}
					}
					$export_data .= "\r\n";
				}
			}
		}

		$export_data .= "\r\n\r\n" . __( 'Completed', 'task-manager' ) . "\r\n";

		if ( ! empty( $points_completed ) ) {
			foreach ( $points_completed as $point ) {
				$export_data .= ( $args['with_id'] ? $point->id . ' - ' : ' > ' ) . trim( str_replace( '<br>', "\r\n", $point->content ) ) . "\r\n";
				if ( $args['with_comments'] ) {
					$point_comments = Task_Comment_Class::g()->get_comments( $point->id, $args );
					if ( ! empty( $point_comments ) ) {
						foreach ( $point_comments as $comment ) {
							$export_data .= '	' . ( $args['with_id'] ? $comment->id . ' - ' : ' > ' ) . trim( str_replace( '<br>', "\r\n	" . str_repeat( ' ', ( strlen( ( $args['with_id'] ? $comment->id . ' - ' : ' > ' ) ) + 3 ) ), $comment->content ) ) . "\r\n\r\n";
						}
					}
					$export_data .= "\r\n\r\n";
				}
			}
		}

		return $export_data;
	}

}
