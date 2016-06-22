<!-- The list of tag -->
<?php  if ( ! defined( 'ABSPATH' ) ) exit;
if ( !empty( $tag_controller->list_tag ) ) :
	foreach ( $tag_controller->list_tag as $tag ) :
		if ( in_array( $tag->id, 	$list_tag_id ) ):
			?><li data-nonce="<?php echo wp_create_nonce( 'ajax_edit_task_tag_' . $tag->id ); ?>" data-slug="<?php echo $tag->slug; ?>" data-id="<?php echo $tag->id; ?>" class="wpeo-tag-tag-selected"><?php echo $tag->name; ?></li><?php
		endif;
	endforeach;

	foreach ( $tag_controller->list_tag as $tag ) :
		if ( !in_array( $tag->id, $list_tag_id ) ):
			?><li data-nonce="<?php echo wp_create_nonce( 'ajax_edit_task_tag_' . $tag->id ); ?>" data-slug="<?php echo $tag->slug; ?>" data-id="<?php echo $tag->id; ?>"><?php echo $tag->name; ?></li><?php
		endif;
	endforeach;
	?>
	<li class="wpeo-tag-edit-tag-btn" data-nonce="<?php echo wp_create_nonce( 'ajax_view_task_tag' ); ?>"><i class="dashicons dashicons-edit"></i></li>
	<?php
endif;
?>
