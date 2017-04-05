<?php
/**
 * Vue pour afficher les catégories dans la barre de recherche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<input type="hidden" name="categories_id_selected" />
<ul class="tags">
	<li class="wpeo-tag-title"><?php esc_html_e( 'Keyword', 'task-manager' ); ?></li>
	<?php
	if ( ! empty( $categories ) ) :
		foreach ( $categories as $category ) :
			View_Util::exec( 'navigation', 'backend/tag', array(
				'category' => $category,
			) );
		endforeach;
	endif;
	?>
</ul>
