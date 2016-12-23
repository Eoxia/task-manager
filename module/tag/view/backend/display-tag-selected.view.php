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
<ul class="wpeo-tag-wrap" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpeo_nonce_load_tag_' . $object->id ) ); ?>">
<?php if ( ! empty( $list_tag ) ) : ?>
	<?php foreach ( $list_tag as $tag ) : ?>
		<?php if ( in_array( $tag->id, $object->taxonomy['wpeo_tag'] ) ) : ?>
	<li class="wpeo-tag-tag-selected"><?php echo esc_html( $tag->name ); ?></li><
		<?php endif; ?>
	<?php endforeach; ?>
<?php else : ?>
	<li class="wpeo-tag-add-tag"><?php esc_html_e( 'Click here to add a tag', 'task-manager' ); ?></li>
<?php endif; ?>
</ul>
