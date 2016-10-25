<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li class="tag-search">
	<ul>
		<?php if( !empty( $this->list_tag ) ) : ?>
		<li class="wpeo-tag-title"><?php _e( 'Tags', 'task-manager' ); ?></li>
			<?php foreach( $this->list_tag as $tag ) : ?>
				<li class="wpeo-tag-search" data-tag-id="<?php echo $tag->id; ?>"><?php echo $tag->name; ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li class="wpeo-new-tag-search">
			<input type="text" name="new_tag" placeholder="<?php _e( 'Or add a new tag...', 'task-manager' ); ?>" /><span class="wpeo-new-tag-search-btn dashicons dashicons-plus-alt"></span>
		</li>
	</ul>
</li>
