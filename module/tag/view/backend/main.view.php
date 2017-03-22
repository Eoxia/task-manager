<?php
/**
 * Vue pour afficher la liste des catégories dans une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<ul class="wpeo-tag-wrap action-attribute"
	data-action="load_tags"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_tags' ) ); ?>"
	data-id="<?php echo esc_attr( $task->id ); ?>"
	data-module="tag"
	data-before-method="before_load_tags">

	<?php
	if ( ! empty( $tags ) ) :
		foreach ( $tags as $tag ) :
			View_Util::exec( 'tag', 'backend/tag', array(
				'tag' => $tag,
			) );
		endforeach;
	else :
		?><li class="wpeo-tag-add-tag"><?php esc_html_e( 'Click here to add a tag', 'task-manager' ); ?></li><?php
	endif;
	?>
</ul>
