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

<ul class="wpeo-tag-wrap">
	<?php
	if ( ! empty( $tags ) ) :
		foreach ( $tags as $tag ) :
			\eoxia\View_Util::exec( 'task-manager', 'tag', 'backend/tag', array(
				'tag' => $tag,
			) );
		endforeach;
	endif;
	?>
	<li class="wpeo-tag add action-attribute"
			data-action="load_tags"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_tags' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span class="dashicons dashicons-plus-alt"></span>
		<?php esc_html_e( 'Categories', 'task-manager' ); ?>
	</li>
</ul>
