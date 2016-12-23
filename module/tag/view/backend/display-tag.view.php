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

if ( ! empty( $list_tag ) ) :
	foreach ( $list_tag as $tag ) :
		if ( in_array( $tag->id, ! empty( $_POST['list_tag_id'] ) ? $_POST['list_tag_id'] : array() ) ) :
			?><li data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_nonce_edit_task_tag_' . $tag->id ) ); ?>" data-slug="<?php echo $tag->slug; ?>" data-id="<?php echo $tag->id; ?>" class="wpeo-tag-tag-selected"><?php echo $tag->name; ?></li><?php
		endif;
	endforeach;

	foreach ( $list_tag as $tag ) :
		if ( ! in_array( $tag->id, ! empty( $_POST['list_tag_id'] ) ? $_POST['list_tag_id'] : array() ) ) :
			?><li data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_nonce_edit_task_tag_' . $tag->id ) ); ?>" data-slug="<?php echo $tag->slug; ?>" data-id="<?php echo $tag->id; ?>"><?php echo $tag->name; ?></li><?php
		endif;
	endforeach;
	?>
	<li class="wpeo-tag-edit-tag-btn"><i class="dashicons dashicons-edit"></i></li>
	<?php
endif;
?>
