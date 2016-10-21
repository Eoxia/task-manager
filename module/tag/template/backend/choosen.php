<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li>
	<select data-placeholder="<?php _e( 'Search by Tag...', 'wpeotag-i18n' ); ?>" style="width: 350px;" multiple tabindex="3" class="wpeo-tag-filter">
		<?php if( !empty( $this->list_tag ) ) : ?>
			<?php foreach( $this->list_tag as $tag ) : ?>
				<option value="<?php echo $tag->id; ?>"><?php echo $tag->name; ?></option>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
</li>
