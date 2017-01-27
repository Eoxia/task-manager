<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-footer-tag" data-id="<?php echo !empty( $object ) ? $object->id : ''; ?>" data-listtagid="<?php echo ( !empty( $list_tag_id ) ) ? json_encode( $list_tag_id ) : ''; ?>">
	<?php 
	if ( !empty( $list_tag_in_object ) ):
		foreach ( $list_tag_in_object as $tag ):
			if ( in_array( $tag->id, $object->taxonomy['wpeo_tag'] ) ):
				?><li class="wpeo-tag-tag-selected"><?php echo $tag->name; ?></li><?php 
			endif;
		endforeach;
	endif;
	?>
	
</ul>