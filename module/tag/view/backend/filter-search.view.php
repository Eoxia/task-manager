<?php
/**
 * Template pour l'affichage d'onglets dans le tableau de bord des tâches
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="tag-search">
	<ul>
		<?php if ( ! empty( $list_tag ) ) : ?>
		<li class="wpeo-tag-title"><?php esc_html_e( 'Tags', 'task-manager' ); ?></li>
			<?php foreach ( $list_tag as $tag ) : ?>
				<li class="wpeo-tag-search" data-tag-id="<?php echo esc_attr( $tag->id ); ?>"><?php echo esc_html( $tag->name ); ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li class="wpeo-new-tag-search">
			<input type="text" name="new_tag" placeholder="<?php esc_html_e( 'Or add a new tag...', 'task-manager' ); ?>" /><span class="wpeo-new-tag-search-btn dashicons dashicons-plus-alt"></span>
		</li>
	</ul>
</li>
