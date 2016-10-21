<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li>
	<ul>
		<?php if( !empty( $this->list_tag ) ) : ?>
			<?php foreach( $this->list_tag as $tag ) : ?>
				<li class="wpeo-tag-search" data-tag-id="<?php echo $tag->id; ?>"><?php echo $tag->name; ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li class="wpeo-new-tag-search">
			<input type="text" name="new_tag" /><span class="wpeo-new-tag-search-btn dashicons dashicons-plus-alt"></span>
		</li>
	</ul>
</li>
