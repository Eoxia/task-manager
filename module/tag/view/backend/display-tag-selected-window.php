<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-footer-tag">
	<?php
	if ( !empty( $list_ta ) ):
		foreach ( $list_tag as $tag ):
			if ( in_array( $tag->id, $object->taxonomy['wpeo_tag'] ) ):
				?><li class="wpeo-tag-tag-selected"><?php echo $tag->name; ?></li><?php
			endif;
		endforeach;
	endif;
	?>

</ul>
