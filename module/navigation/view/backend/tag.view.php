<?php
/**
 * Vue pour afficher une catégorie dans la barre de recherche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<li class="wpeo-tag-search" data-tag-id="<?php echo esc_attr( $category->term_taxonomy_id ); ?>"><?php echo esc_html( $category->name ); ?></li>
