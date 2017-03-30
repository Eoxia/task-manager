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

	<li class="wpeo-new-tag-search">
		<form class="form" action="<?php echo esc_attr( 'admin-ajax.php' ); ?>" method="POST">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'create_tag' ); ?>" />
			<?php wp_nonce_field( 'create_tag' ); ?>

			<input type="text" name="tag_name" placeholder="<?php esc_html_e( 'New tag name', 'task-manager' ); ?>" />
			<span data-loader="tags" data-parent="form" class="action-input dashicons dashicons-plus-alt"></span>
		</form>
	</li>
</ul>
