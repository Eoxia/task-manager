<?php
/**
 * Vue pour afficher une catégorie dans la barre de recherche.
 *
 * @package Task Manager
 *
 * @since 1.0.0
 * @version 1.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="wpeo-tag-search" data-tag-id="<?php echo esc_attr( $category->data['term_taxonomy_id'] ); ?>"><?php echo esc_html( $category->data['name'] ); ?></li>
