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
$tag_list_is_empty = true;
?>
<ul class="wpeo-tag-wrap action-attribute" data-action="load_tags" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_tags' ) ); ?>" >

<?php if ( ! empty( $list_tag ) ) : ?>
	<?php foreach ( $list_tag as $tag ) : ?>
		<?php
		if ( in_array( $tag->id, $object->taxonomy['wpeo_tag'] ) ) :
			if ( 0 !== $tag->id ) :
				$tag_list_is_empty = false;
			endif;
		?>
	<li class="wpeo-tag-tag-selected"><?php echo esc_html( $tag->name ); ?></li><
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if ( true === $tag_list_is_empty ) : ?>
	<li class="wpeo-tag-add-tag" ><?php esc_html_e( 'Click here to add a tag', 'task-manager' ); ?></li>
<?php endif; ?>

</ul>
