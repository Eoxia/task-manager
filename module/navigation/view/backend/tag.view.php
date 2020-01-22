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

<li class="dropdown-item wpeo-tag-search no-hidden" data-content="<?php echo esc_attr( $category->data['slug'] ); ?>" data-id="<?php echo esc_attr( $category->data['term_taxonomy_id'] ); ?>">
	<span class="dropdown-result-title"><?php echo esc_html( $category->data['name'] ); ?></span>
</li>
