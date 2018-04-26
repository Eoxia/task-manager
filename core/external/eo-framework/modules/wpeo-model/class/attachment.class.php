<?php
/**
 * Gestion des attachments (POST, PUT, GET, DELETE)
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Attachment_Class' ) ) {

	/**
	 * Gestion des attachments (POST, PUT, GET, DELETE)
	 */
	class Attachment_Class extends Post_Class {

		/**
		 * Le nom du modèle
		 *
		 * @var string
		 */
		protected $model_name = '\eoxia\Attachment_Model';

		/**
		 * Le type du post
		 *
		 * @var string
		 */
		protected $type = 'attachment';

		/**
		 * Le type du post
		 *
		 * @var string
		 */
		protected $base = 'eo-attachment';

		/**
		 * La clé principale pour post_meta
		 *
		 * @var string
		 */
		protected $meta_key = 'eo_attachment';

		/**
		 * Nom de la taxonomy
		 *
		 * @var string
		 */
		protected $attached_taxonomy_type = 'attachment_category';


		/**
		 * Le nom pour le resgister post type
		 *
		 * @var string
		 */
		protected $post_type_name = 'Attachments';

		/**
		 * Utiles pour récupérer la clé unique
		 *
		 * @todo Rien à faire ici
		 * @var string
		 */
		protected $identifier_helper = 'attachment';

		/**
		 * Le chemin vers le modèle
		 *
		 * @var string
		 */
		protected $model_path = '';

		/**
		 * Récupères le chemin vers le dossier frais-pro dans wp-content/uploads
		 *
		 * @param string $path_type (Optional) Le type de path.
		 *
		 * @return string Le chemin vers le document
		 */
		public function get_dir_path( $path_type = 'basedir' ) {
			$upload_dir = wp_upload_dir();
			$response   = str_replace( '\\', '/', $upload_dir[ $path_type ] );
			return $response;
		}

		/**
		 * Récupération de la liste des modèles de fichiers disponible pour un type d'élément
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param array  $current_element_type La liste des types pour lesquels il faut récupérer les modèles de documents.
		 * @param string $extension           L'extension à utilisé.
		 * @return array                      Un statut pour la réponse, un message si une erreur est survenue, le ou les identifiants des modèles si existants.
		 */
		public function get_model_for_element( $current_element_type, $extension = 'odt' ) {
			$response = array(
				'status'     => true,
				'model_id'   => null,
				'model_path' => str_replace( '\\', '/', $this->model_path . 'core/assets/document_template/' . $current_element_type[0] . '.' . $extension ),
				'model_url'  => str_replace( '\\', '/', $this->model_path . 'core/assets/document_template/' . $current_element_type[0] . '.' . $extension ),
				'message'    => sprintf( 'Le modèle utilisé est: %1$score/assets/document_template/%2$s.' . $extension, $this->model_path, $current_element_type[0] ),
			);

			$tax_query = array(
				'relation' => 'AND',
			);

			if ( ! empty( $current_element_type ) ) {
				foreach ( $current_element_type as $element ) {
					$tax_query[] = array(
						'taxonomy' => $this->attached_taxonomy_type,
						'field'    => 'slug',
						'terms'    => $element,
					);
				}
			}

			$query = new \WP_Query( array(
				'fields'         => 'ids',
				'post_status'    => 'inherit',
				'posts_per_page' => 1,
				'tax_query'      => $tax_query,
				'post_type'      => 'attachment',
			) );

			if ( $query->have_posts() ) {
				$upload_dir = wp_upload_dir();

				$model_id               = $query->posts[0];
				$attachment_file_path   = str_replace( '\\', '/', get_attached_file( $model_id ) );
				$response['model_id']   = $model_id;
				$response['model_path'] = str_replace( '\\', '/', $attachment_file_path );
				$response['model_url']  = str_replace( str_replace( '\\', '/', $upload_dir['basedir'] ), str_replace( '\\', '/', $upload_dir['baseurl'] ), $attachment_file_path );
				$response['message']    = sprintf( 'Le modèle utilisé est: %1$s', $attachment_file_path );
			}

			return $response;
		}

		/**
		 * Récupération de la prochaine version pour un type de document
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param array   $types       Les catégories du document.
		 * @param integer $element_id  L'ID de l'élément.
		 *
		 * @return int                 La version +1 du document actuellement en cours de création.
		 */
		public function get_document_type_next_revision( $types, $element_id ) {
			global $wpdb;
			// Récupération de la date courante.
			$today = getdate();
			// Définition des paramètres de la requête de récupération des documents du type donné pour la date actuelle.
			$get_model_args = array(
				'nopaging'    => true,
				'post_parent' => $element_id,
				'post_type'   => $this->post_type,
				'post_status' => array( 'publish', 'inherit' ),
				'tax_query'   => array(
					array(
						'taxonomy' => $this->attached_taxonomy_type,
						'field'    => 'slug',
						'terms'    => $types,
						'operator' => 'AND',
					),
				),
				'date_query'  => array(
					array(
						'year'  => $today['year'],
						'month' => $today['mon'],
						'day'   => $today['mday'],
					),
				),
			);

			$revision = new \WP_Query( $get_model_args );
			return ( $revision->post_count + 1 );
		}

		/**
		 * Création du document dans la base de données puis appel de la fonction de génération du fichier
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param object $element       L'élément parent ou le document sera attaché.
		 * @param array  $types         Les catégories auxquelles associer le document généré.
		 * @param array  $document_meta Les données a écrire dans le modèle de document.
		 * @param string $extension     L'extension à utiliser.
		 *
		 * @return array                Le résultat de la création du document.
		 */
		public function create_document( $element, $types, $document_meta, $extension = 'odt' ) {
			$response = array(
				'status'   => true,
				'message'  => '',
				'filename' => '',
				'path'     => '',
				'document' => null,
			);

			$model_status = $this->get_model_for_element( wp_parse_args( array( 'model', 'default_model' ), $types ), $extension );

			$document_taxonomy = array();
			// Récupère la liste des identifiants des catégories a affecter au documents.
			// Catégorie principale "printed" qui correspond aux fichiers générés dans l'interface.
			$printed_category    = get_term_by( 'slug', 'printed', $this->attached_taxonomy_type );
			$document_taxonomy[] = (int) $printed_category->term_id;

			// Liste des catégories spécifiques.
			foreach ( $types as $type ) {
				$category            = get_term_by( 'slug', $type, $this->attached_taxonomy_type );
				$document_taxonomy[] = (int) $category->term_id;
			}

			// Insères l'attachement en base de donnée ainsi que ses métadonnées.
			$document_args = array(
				'status'        => 'inherit',
				'title'         => $element->data['title'],
				'parent_id'     => $element->data['parent_id'],
				'model_path'    => $model_status['model_path'],
				'document_meta' => $document_meta,
				'taxonomy'      => array(
					$this->attached_taxonomy_type => $document_taxonomy,
				),
			);

			$response['document'] = $this->update( $document_args );

			return $response;
		}

		/**
		 * Vérification de l'existence d'un fichier à partir de la définition d'un document.
		 *
		 * 1- On remplace l'url du site "site_url( '/' )" par le chemin "ABSPATH" contenant les fichiers du site: on vérifie si le fichier existe.
		 * 2- Si le fichier n'existe pas:
		 *  2.a- On récupère la meta associée automatiqumeent par WordPress.
		 *  2.b- Si la méta n'est pas vide, on vérifie que sa valeur concaténée au chemin absolu des uploads "wp_upload_dir()" de WordPress soit bien un fichier
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param Document_Model $document La définition du document à vérifier.
		 *
		 * @return array                   Tableau avec le status d'existence du fichier (True/False) et le lien de téléchargement du fichier.
		 */
		public function check_file( $document ) {
			// Définition des valeurs par défaut.
			$file_check = array(
				'exists'    => false,
				'path'      => '',
				'mime_type' => '',
				'link'      => '',
			);

			if ( ! empty( $document->data['link'] ) ) {
				$file_check['path'] = str_replace( site_url( '/' ), ABSPATH, $document->data['link'] );
				$file_check['link'] = $document->data['link'];
			}

			$upload_dir = wp_upload_dir();

			// Vérification principale. cf 1 ci-dessus.
			if ( is_file( $file_check['path'] ) ) {
				$file_check['exists'] = true;
			}

			// La vérification principale n'a pas fonctionnée. cf 2 ci-dessus.
			if ( ! $file_check['exists'] && ! empty( $document->data['_wp_attached_file'] ) ) {
				$file_check['path'] = $upload_dir['basedir'] . '/' . $document->data['_wp_attached_file'];
				$file_check['link'] = $upload_dir['baseurl'] . '/' . $document->data['_wp_attached_file'];
				if ( is_file( $file_check['path'] ) ) {
					$file_check['exists'] = true;
				}
			}

			// Si le fichier existe on récupère le type mime.
			if ( $file_check['exists'] ) {
				$file_check['mime_type'] = wp_check_filetype( $file_check['path'] );
			}

			return $file_check;
		}
	}
} // End if().
